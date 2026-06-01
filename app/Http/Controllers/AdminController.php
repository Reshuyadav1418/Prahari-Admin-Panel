<?php

namespace App\Http\Controllers;

use App\Models\Challan;
use App\Models\Cases;
use App\Models\Category;
use App\Models\Prahari;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
   
   public function cases(Request $request)
{
    if ($request->ajax()) {

        $query = Cases::with(['prahari', 'category'])
            ->select([
                'id',
                'prahari_id',
                'category_id',
                'vehicle_number',
                'location',
                'status',
                'evidence_file',
                'violation_datetime'
            ]);

        // FILTER
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $cases = $query->get();

        return DataTables::of($cases)
            ->addIndexColumn()

            ->addColumn('prahari_name', function($case) {
                return $case->prahari->name ?? 'N/A';
            })

            ->addColumn('category_name', function($case) {
                return $case->category->name ?? 'N/A';
            })

            // SIMPLE STATUS WITH COLOR
            ->addColumn('status', function($case) {
                if ($case->status == 'open') {
                    return '<span style="color:green;">Open</span>';
                } elseif ($case->status == 'in_progress') {
                    return '<span style="color:orange;">Rejected</span>';
                } else {
                    return '<span style="color:blue;">Approved</span>';
                }
            })

            // ACTION BUTTONS WITH ICONS
            ->addColumn('action', function($case) {
                $evidenceBtn = "";
                if ($case->evidence_file) {
                    $url = (str_starts_with($case->evidence_file, 'http')) 
                            ? $case->evidence_file 
                            : asset('storage/' . $case->evidence_file);
                    $viewUrl = route('admin.cases.show', ['id' => $case->id]);
                    $evidenceBtn = "<a href=\"{$viewUrl}\" style=\"text-decoration:none;\" title=\"View Case Details\">";
                    $evidenceBtn .= "<button type=\"button\" style=\"background:none;border:none;cursor:pointer;font-size:18px;color:#17a2b8;margin-right:8px;\">👁</button></a>";
                }
                $approveBtn = "<button type=\"button\" style=\"background:none;border:none;cursor:pointer;font-size:18px;color:#28a745;\" onclick=\"approveCase({$case->id})\" title=\"Approve\">✓</button>";
                $deleteBtn = "<button type=\"button\" style=\"background:none;border:none;cursor:pointer;font-size:16px;color:#dc3545;\" onclick=\"deleteCase({$case->id})\" title=\"Delete\">🗑</button>";
                return $evidenceBtn . $approveBtn . $deleteBtn;
            })

            ->rawColumns(['status','action'])
            ->make(true);
    }

    $praharis = Prahari::select('id', 'name')->where('status', 1)->get();
    $categories = Category::select('id', 'name', 'amount')->where('status', '1')->get();

    return view('layouts.admin.admin_cases', compact('praharis', 'categories'));
    }

    public function showCase($id)
    {
        $case = Cases::with(['prahari', 'category'])->findOrFail($id);
        return view('layouts.admin.admin_case_detail', compact('case'));
    }

    public function challan(Request $request){
        if ($request->ajax()) {
            $challans = Challan::with(['prahari'])
                ->join('cases', 'cases.id', '=', 'challans.case_id')
                ->where('cases.status', 'closed')
                ->select('challans.*');

            if ($request->status) {
                $challans->where('challans.status', $request->status);
            }

            return DataTables::of($challans)
                ->addIndexColumn()
                ->addColumn('challan_id', function($challan) {
                    return 'CHALLAN-' . $challan->id;
                })
                ->addColumn('case_id', function($challan) {
                    return 'CASE-' . $challan->case_id;
                })
                ->addColumn('prahari_id', function($challan) {
                    return 'PRAHARI-' . $challan->prahari_id;
                })
                ->addColumn('prahari_name', function($challan) {
                    return $challan->prahari->name ?? 'N/A';
                })
                ->addColumn('amount', function($challan) {
                    return '₹ ' . number_format($challan->amount, 2);
                })
                ->addColumn('date', function($challan) {
                    return date('d-m-Y', strtotime($challan->challan_date));
                })
                ->addColumn('status_display', function($challan) {
                    $colors = ['pending' => '#ffc107', 'paid' => '#28a745', 'cancelled' => '#dc3545'];
                    $color = $colors[$challan->status] ?? '#000';
                    return '<span style="color:'.$color.';font-weight:bold;">' . ucfirst($challan->status) . '</span>';
                })
                ->addColumn('action', function($challan) {
                    $deleteBtn = '<button style="background:none;border:none;color:#dc3545;font-size:16px;cursor:pointer;" onclick="deleteChallanRecord('.$challan->id.')" title="Delete">🗑</button>';
                    if ($challan->status !== 'paid') {
                        $markPaidBtn = '<button style="background:none;border:none;color:#28a745;font-size:16px;cursor:pointer;margin-right:8px;" onclick="markChallanPaid('.$challan->id.')" title="Mark Paid">💰</button>';
                    } else {
                        $markPaidBtn = '<button style="background:none;border:none;color:#6b7280;font-size:14px;cursor:pointer;margin-right:8px;" onclick="markChallanPending('.$challan->id.')" title="Mark Pending">Paid</button>';
                    }
                    return $markPaidBtn . $deleteBtn;
                })
                ->rawColumns(['status_display', 'action'])
                ->make(true);
        }
        return view('layouts.admin.admin_challan');
    }

    public function destroyChallan($id)
    {
        $challan = Challan::findOrFail($id);
        $challan->delete();

        return response()->json(['message' => 'Challan deleted successfully.']);
    }

    public function markChallanPaid($id)
    {
        $challan = Challan::findOrFail($id);
        $challan->status = 'paid';
        $challan->save();

        // create withdrawal for 20% payout if not exists
        $payout = round(($challan->amount * 0.20), 2);
        $exists = Transaction::where('challan_id', $challan->id)
            ->where('prahari_id', $challan->prahari_id)
            ->where('amount_paid', $payout)
            ->exists();

        if (! $exists) {
            $prahari = Prahari::find($challan->prahari_id);
            Transaction::create([
                'prahari_id' => $challan->prahari_id,
                'challan_id' => $challan->id,
                'amount_paid' => $payout,
                'bank_account_number' => $prahari->bank_account_number ?? '',
                'status' => 'pending'
            ]);
        }

        return response()->json(['message' => 'Challan marked paid and withdrawal created.']);
    }

    public function markChallanPending($id)
    {
        $challan = Challan::findOrFail($id);
        $challan->status = 'pending';
        $challan->save();

        return response()->json(['message' => 'Challan marked as pending.']);
    }

    public function payments(Request $request)
    {
        if ($request->ajax()) {
            $payments = Transaction::with('prahari')
                ->select([
                    'id',
                    'prahari_id',
                    'challan_id',
                    'amount_paid',
                    'bank_account_number',
                    'status',
                    'created_at'
                ]);

            if ($request->tab == 'withdrawals') {
                $payments->where('status', 'pending');
            } elseif ($request->tab == 'all') {
                // optionally filter anything else if needed
            }

            if ($request->status) {
                $payments->where('status', $request->status);
            }

            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('request_id', function($payment) {
                    return 'WO100' . $payment->id;
                })
                ->addColumn('prahari_name', function($payment) {
                    return $payment->prahari->name ?? 'N/A';
                })
                ->addColumn('amount', function($payment) {
                    return '₹ ' . number_format($payment->amount_paid, 2);
                })
                ->addColumn('bank_account', function($payment) {
                    $bank = $payment->bank_account_number;
                    if(strlen($bank) > 4) {
                        return str_repeat('*', 9) . substr($bank, -4);
                    }
                    return $bank;
                })
                ->addColumn('date', function($payment) {
                    return date('d M Y', strtotime($payment->created_at));
                })
                ->addColumn('status', function($payment) {
                    return ucfirst($payment->status);
                })
                ->addColumn('action', function($payment) {
                    if ($payment->status == 'pending') {
                        $approveBtn = "<button type=\"button\" style=\"background:none;border:none;cursor:pointer;font-size:18px;color:#28a745;\" onclick=\"approvePayment({$payment->id})\" title=\"Approve\">✓</button>";
                        $rejectBtn = "<button type=\"button\" style=\"background:none;border:none;cursor:pointer;font-size:18px;color:#dc3545;margin-left:8px;\" onclick=\"rejectPayment({$payment->id})\" title=\"Reject\">✕</button>";
                        return $approveBtn . $rejectBtn;
                    } else {
                            return '<button type="button" style="background:transparent;border:none;color:#dc3545;font-size:16px;cursor:pointer;" onclick="deletePayment('.$payment->id.')" title="Delete"><i class="bi bi-trash"></i></button>';
                        }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $praharis = Prahari::select('id', 'name')->get();
        $challans = Challan::select('id')->get();

        return view('layouts.admin.admin_payment', compact('praharis', 'challans'));
    }

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'prahari_id' => 'required|exists:praharis,id',
            'challan_id' => 'nullable|exists:challans,id',
            'amount_paid' => 'required|numeric|min:0',
            'bank_account_number' => 'required|string|max:50',
            'status' => 'required|in:pending,success,failed',
        ]);

        Transaction::create($validated);

        return response()->json(['message' => 'Payment saved successfully.']);
    }

    public function approvePayment($id)
    {
        $payment = Transaction::findOrFail($id);
        $payment->status = 'success';
        $payment->save();

        return response()->json(['message' => 'Payment approved successfully.']);
    }

    public function rejectPayment($id)
    {
        $payment = Transaction::findOrFail($id);
        $payment->status = 'failed';
        $payment->save();

        return response()->json(['message' => 'Payment rejected successfully.']);
    }

    public function destroyPayment($id)
    {
        $payment = Transaction::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully.']);
    }

    /**
     * Prahari statistics endpoint for admin API
     */
    public function prahariStats(Request $request, $id)
    {
        $prahari = Prahari::find($id);
        if (!$prahari) {
            return response()->json(['success' => false, 'message' => 'PraharI not found.'], 404);
        }

        $totalCases = Cases::where('prahari_id', $id)->count();
        $openCases = Cases::where('prahari_id', $id)->where('status', 'open')->count();
        $closedCases = Cases::where('prahari_id', $id)->where('status', 'closed')->count();

        $totalChallans = Challan::where('prahari_id', $id)->count();
        $pendingChallans = Challan::where('prahari_id', $id)->where('status', 'pending')->count();
        $paidChallans = Challan::where('prahari_id', $id)->where('status', 'paid')->count();

        // Sum only successful (approved) transactions as earnings
        $totalEarnings = Transaction::where('prahari_id', $id)->where('status', 'success')->sum('amount_paid');

        return response()->json([
            'success' => true,
            'data' => [
                'total_cases' => $totalCases,
                'open_cases' => $openCases,
                'closed_cases' => $closedCases,
                'total_challans' => $totalChallans,
                'pending_challans' => $pendingChallans,
                'paid_challans' => $paidChallans,
                'total_earnings' => (float) $totalEarnings,
            ]
        ]);
    }

  public function praharis(Request $request)
{
    if ($request->ajax()) {

        $praharis = Prahari::select([
            'id',
            'name',
            'aadhar_number',
            'phone',
            'bank_account_number',
            'status',
            'created_at'
        ])->get();

        return DataTables::of($praharis)
            ->addIndexColumn()

            // SIMPLE PRAHARI ID
            ->addColumn('prahari_id', function($prahari) {
                return 'PRAHARI-' . $prahari->id;
            })

            // SIMPLE AADHAR
            ->addColumn('aadhar_display', function($prahari) {
                return $prahari->aadhar_number;
            })

            // SIMPLE PHONE
            ->addColumn('phone_display', function($prahari) {
                return $prahari->phone;
            })

            // SIMPLE STATUS
            ->addColumn('status', function($prahari) {
                return $prahari->status ? 'Active' : 'Inactive';
            })

            // SIMPLE DATE
            ->addColumn('joined_date', function($prahari) {
                return date('d-m-Y', strtotime($prahari->created_at));
            })

            // SIMPLE ACTION
            ->addColumn('action', function($prahari) {
                return '
        <button class="btn btn-sm btn-outline-dark me-1" 
    onclick="editPrahari('.$prahari->id.')">
    <i class="bi bi-pencil-square"></i>
</button>

<button class="btn btn-sm btn-outline-dark" 
    onclick="deletePrahari('.$prahari->id.')">
    <i class="bi bi-trash"></i>
</button>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    return view('layouts.admin.admin_prahari');
}
    

    public function categories(Request $request){
        if ($request->ajax()) {
            $category = Category::select(['id','name','amount','description','status'])->get();
            return DataTables::of($category)
                ->addIndexColumn()
                ->addColumn('category_id', function($category) {
                    return 'CAT-' . $category->id;
                })
              
                ->addColumn('action', function($category) {
                    $editBtn = '<button class="btn btn-sm btn-outline-dark me-1" onclick="editCategory(' . $category->id . ')" title="Edit"><i class="bi bi-pencil-square"></i></button>';
                    $deleteBtn = '<button class="btn btn-sm btn-outline-dark" onclick="deleteCategory(' . $category->id . ')" title="Delete"><i class="bi bi-trash"></i></button>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('layouts.admin.category');
    }

    public function storeCategory(Request $request)
    {
          $validated = $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'description' => 'nullable|string|max:500',
        'status' => 'required|in:1,0',
    ]);

    Category::create($validated);

    return response()->json([
        'message' => 'Category added successfully.'
    ]);
      
    }

    public function editCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'description' => 'nullable|string|max:500',
                'status' => 'required|in:1,0',
            ]);

            $category->update($validated);

            return response()->json(['success' => true, 'message' => 'Category updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 400);
        }
    }

    public function storePrahari(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'aadhar_number' => 'required|string|max:20|unique:praharis,aadhar_number',
            'phone' => 'required|string|max:20|unique:praharis,phone',
            'bank_account_number' => 'required|string|max:50|unique:praharis,bank_account_number',
            'status' => 'required|in:1,0',
        ]);

        Prahari::create($validated);

        return response()->json(['message' => 'Prahari added successfully.']);
    }

    public function editPrahari($id)
    {
        $prahari = Prahari::findOrFail($id);
        return response()->json($prahari);
    }

    public function updatePrahari(Request $request, $id)
    {
        $prahari = Prahari::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'aadhar_number' => 'required|string|max:20|unique:praharis,aadhar_number,' . $id,
            'phone' => 'required|string|max:20|unique:praharis,phone,' . $id,
            'bank_account_number' => 'required|string|max:50|unique:praharis,bank_account_number,' . $id,
            'status' => 'required|in:1,0',
        ]);

        $prahari->update($validated);

        return response()->json(['message' => 'Prahari updated successfully.']);
    }

    public function destroyPrahari($id)
    {
        $prahari = Prahari::findOrFail($id);
        $prahari->delete();

        return response()->json(['message' => 'Prahari deleted successfully.']);
    }
    

    public function storeCase(Request $request)
{
    $validated = $request->validate([
        'prahari_id' => 'required|exists:praharis,id',
        'category_id' => 'required|exists:categories,id',
        'vehicle_number' => 'required|string|max:50',
        'location' => 'required|string|max:255',
        'violation_datetime' => 'nullable|date_format:Y-m-d\TH:i',
    ]);

    // Set default status as open
    $validated['status'] = 'open';
    $validated['evidence_file'] = 'N/A'; // Default value

    Cases::create($validated);

    return response()->json([
        'message' => 'Case added successfully'
    ]);
}

    public function updateCaseStatus(Request $request, $id)
    {
        $case = Cases::with('category')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $case->status = $validated['status'];
        $case->save();

        if ($validated['status'] === 'closed') {
            if (!$case->challan) {
                Challan::create([
                    'prahari_id' => $case->prahari_id,
                    'case_id' => $case->id,
                    'category_id' => $case->category_id,
                    'vehicle_number' => $case->vehicle_number,
                    'amount' => $case->category->amount ?? 0.00,
                    'status' => 'pending',
                    'challan_date' => now()->toDateString(),
                ]);
            }
        }

        return response()->json(['message' => 'Case approved successfully.']);
    }

    public function approveCase($id)
    {
        try {
            $case = Cases::with(['prahari', 'category'])->findOrFail($id);

            // Update case status to closed (approved)
            $case->status = 'closed';
            $case->save();

            // Create challan if not exists
            if (!$case->challan) {
                $challanAmount = $case->category->amount ?? 0.00;
                
                Challan::create([
                    'prahari_id' => $case->prahari_id,
                    'case_id' => $case->id,
                    'category_id' => $case->category_id,
                    'vehicle_number' => $case->vehicle_number,
                    'amount' => $challanAmount,
                    'status' => 'pending',
                    'challan_date' => now()->toDateString(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Case approved successfully and challan created.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function rejectCase($id)
    {
        try {
            $case = Cases::findOrFail($id);
            $case->status = 'in_progress';
            $case->save();

            return response()->json([
                'success' => true,
                'message' => 'Case rejected successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function deleteCase($id)
    {
        try {
            $case = Cases::findOrFail($id);
            $case->delete();

            return response()->json([
                'success' => true,
                'message' => 'Case deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function deleteChallanRecord($id)
    {
        try {
            $challan = Challan::findOrFail($id);
            $challan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Challan deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }


    public function reports()
    {
        return view('layouts.admin.admin_reports');
    }

    public function getReportData(Request $request)
    {
        // Option 1: Use provided dates
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Option 2: If no dates provided, get ALL TIME data
        if(!$startDate || !$endDate) {
            $startDate = \Carbon\Carbon::parse('2000-01-01')->toDateString();
            $endDate = \Carbon\Carbon::now()->toDateString();
        }

        $totalCases = \App\Models\Cases::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
        $totalChallans = \App\Models\Challan::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
        
        // Debug: Check all transactions in date range
        $allTransactions = \App\Models\Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        
        // Get total revenue - simple sum
        $totalRevenue = $allTransactions->sum('amount_paid');

        // Cases Trend Data (Grouped by Date)
        $casesTrend = \App\Models\Cases::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $casesLabels = $casesTrend->pluck('date');
        $casesData = $casesTrend->pluck('count');

        // Revenue Trend Data (Grouped by Date)
        $revenueTrend = \App\Models\Transaction::selectRaw('DATE(created_at) as date, SUM(amount_paid) as total')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $revenueLabels = $revenueTrend->pluck('date')->values();
        $revenueData = $revenueTrend->pluck('total')->map(function($value) {
            return floatval($value) ?: 0;
        })->values();

        // Debug info
        $debugInfo = [
            'transaction_count' => $allTransactions->count(),
            'date_range' => "$startDate to $endDate",
            'raw_revenue_sum' => $totalRevenue,
            'sample_transactions' => $allTransactions->take(3)->map(function($t) {
                return [
                    'id' => $t->id,
                    'amount_paid' => $t->amount_paid,
                    'status' => $t->status,
                    'created_at' => $t->created_at
                ];
            })->toArray()
        ];

        return response()->json([
            'totalCases' => number_format($totalCases),
            'totalChallans' => number_format($totalChallans),
            'totalRevenue' => number_format($totalRevenue, 2),
            'casesTrend' => [
                'labels' => $casesLabels->values(),
                'data' => $casesData->values()
            ],
            'revenueTrend' => [
                'labels' => $revenueLabels,
                'data' => $revenueData
            ],
            'debug' => $debugInfo
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('layouts.admin.admin_profile', compact('user'));
    }

    public function uploadProfileImage(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('profile_image')->store('profile_images', 'public');

        $user->profile_image = $path;
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile image updated successfully.');
    }

    public function removeProfileImage(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->profile_image = null;
            $user->save();
        }

        return redirect()->route('admin.profile')->with('success', 'Profile image removed successfully.');
    }

    public function settings()
    {
        $sidebarSettings = [
            'show_dashboard' => \App\Models\Setting::get('show_dashboard', 1),
            'show_praharis' => \App\Models\Setting::get('show_praharis', 1),
            'show_cases' => \App\Models\Setting::get('show_cases', 1),
            'show_challans' => \App\Models\Setting::get('show_challans', 1),
            'show_payments' => \App\Models\Setting::get('show_payments', 1),
            'show_reports' => \App\Models\Setting::get('show_reports', 1),
            'show_admins' => \App\Models\Setting::get('show_admins', 1),
            'show_settings' => \App\Models\Setting::get('show_settings', 1),
        ];

        return view('layouts.admin.admin_settings', compact('sidebarSettings'));
    }

    public function saveSettings(Request $request)
    {
        $settings = $request->except(['_token', 'app_logo']);
        
        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Unchecked toggles won't be in request, we manually check for sidebar visibility toggles
        $sidebarKeys = [
            'show_dashboard', 'show_praharis', 'show_cases', 'show_challans', 
            'show_payments', 'show_reports', 'show_admins', 'show_settings'
        ];
        
        foreach ($sidebarKeys as $key) {
            $value = $request->has($key) ? 1 : 0;
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings saved successfully!']);
    }

    // ADMIN MANAGEMENT FUNCTIONS
    
    public function admins(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAdminsData();
        }

        return view('layouts.admin.admin_admins');
    }

    public function getAdminsData(Request $request = null)
    {
        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->select(['id', 'name', 'email', 'role', 'created_at'])
            ->get();

        return DataTables::of($admins)
            ->addIndexColumn()
            ->addColumn('created_at', function($admin) {
                return date('d-m-Y', strtotime($admin->created_at));
            })
            ->addColumn('role', function($admin) {
                return ucwords(str_replace('_', ' ', $admin->role));
            })
            ->addColumn('action', function($admin) {
                $editBtn = '<button type="button" style="background:none;border:none;cursor:pointer;font-size:18px;color:#007bff;margin-right:10px;" onclick="editAdmin(' . $admin->id . ')" title="Edit">✎</button>';
                $deleteBtn = '<button type="button" style="background:none;border:none;cursor:pointer;font-size:16px;color:#dc3545;" onclick="deleteAdmin(' . $admin->id . ')" title="Delete">🗑</button>';
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|in:admin,super_admin',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Admin created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function editAdmin($id)
    {
        try {
            $admin = User::where('role', '!=', 'user')->findOrFail($id);
            
            return response()->json([
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function updateAdmin(Request $request, $id)
    {
        try {
            $admin = User::where('role', '!=', 'user')->findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|in:admin,super_admin',
                'new_password' => 'nullable|string|min:6',
            ]);

            $admin->name = $validated['name'];
            $admin->email = $validated['email'];
            $admin->role = $validated['role'];

            if (!empty($validated['new_password'])) {
                $admin->password = Hash::make($validated['new_password']);
            }

            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Admin updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function deleteAdmin($id)
    {
        try {
            $admin = User::where('role', '!=', 'user')->findOrFail($id);
            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }
}
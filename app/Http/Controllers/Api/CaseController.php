<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\Prahari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'prahari') {
            return response()->json([
                'status' => false,
                'message' => 'Only authenticated prahari users can add cases.'
            ], 403);
        }

        $prahari = Prahari::where('phone', $user->phone)->first();

        if (!$prahari) {
            return response()->json([
                'status' => false,
                'message' => 'Prahari profile not found. Please contact admin.'
            ], 404);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'vehicle_number' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'violation_datetime' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $case = Cases::create([
            'prahari_id' => $prahari->id,
            'category_id' => $validated['category_id'],
            'vehicle_number' => $validated['vehicle_number'],
            'location' => $validated['location'],
            'violation_datetime' => $validated['violation_datetime'],
            'status' => 'open',
            'evidence_file' => 'N/A',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Case added successfully.',
            'data' => $case,
        ], 201);
    }
}

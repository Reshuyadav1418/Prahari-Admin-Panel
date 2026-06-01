<?php

use Illuminate\Support\Facades\DB;
use App\Models\Prahari;
use App\Models\Transaction;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$names = ['Ramesh Kumar', 'Suresh Yadav', 'Amit Singh', 'Vikash Rana', 'Manoj Verma'];
$amounts = [2000, 1500, 3000, 2500, 1000];
$statuses = ['pending', 'success', 'pending', 'failed', 'success'];
$dates = ['2024-05-13', '2024-05-12', '2024-05-11', '2024-05-10', '2024-05-09'];
$accounts = ['111122225678', '999988881234', '444455559876', '123412342222', '000000001111'];

foreach($names as $index => $name) {
    $prahari = Prahari::firstOrCreate(
        ['aadhar_number' => '12345678901'.$index],
        [
            'name' => $name,
            'phone' => '987654321'.$index,
            'bank_account_number' => $accounts[$index],
            'status' => '1'
        ]
    );
    $t = new Transaction();
    $t->prahari_id = $prahari->id;
    $t->amount_paid = $amounts[$index];
    $t->bank_account_number = $accounts[$index];
    $t->status = $statuses[$index];
    $t->created_at = $dates[$index] . ' 10:00:00';
    $t->save();
}

echo "Seeded successfully!";

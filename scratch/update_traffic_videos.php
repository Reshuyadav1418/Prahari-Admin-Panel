<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Cases;

$videos = [
    'https://github.com/intel-iot-devkit/sample-videos/raw/master/traffic.mp4',
    'https://github.com/intel-iot-devkit/sample-videos/raw/master/car-detection.mp4',
    'https://github.com/intel-iot-devkit/sample-videos/raw/master/driver-action-recognition.mp4',
    'https://github.com/intel-iot-devkit/sample-videos/raw/master/person-bicycle-car-detection.mp4'
];

$cases = Cases::all();
foreach ($cases as $index => $case) {
    $case->evidence_file = $videos[$index % count($videos)];
    $case->save();
}

echo "Updated " . $cases->count() . " cases with real traffic/surveillance videos.\n";

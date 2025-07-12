<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use Carbon\Carbon;

echo "=== TIME ANALYSIS ===\n";
echo 'Current time: ' . Carbon::now()->format('Y-m-d H:i:s') . "\n";
echo 'Timezone: ' . Carbon::now()->timezone . "\n";
echo 'UTC time: ' . Carbon::now('UTC')->format('Y-m-d H:i:s') . "\n";

// Test the exact same logic as in the controller
$testScheduledInput = '2025-07-12T11:00';
$scheduledAt = Carbon::createFromFormat('Y-m-d\TH:i', $testScheduledInput);
$now = Carbon::now();

echo "\n=== SCHEDULING TEST ===\n";
echo 'Input: ' . $testScheduledInput . "\n";
echo 'Parsed scheduled time: ' . $scheduledAt->format('Y-m-d H:i:s') . "\n";
echo 'Current time: ' . $now->format('Y-m-d H:i:s') . "\n";
echo 'Is future: ' . ($scheduledAt->isAfter($now) ? 'yes' : 'no') . "\n";
echo 'Diff in seconds: ' . $scheduledAt->diffInSeconds($now) . "\n";
echo 'Scheduled timezone: ' . $scheduledAt->timezone . "\n";
echo 'Current timezone: ' . $now->timezone . "\n";

// Test with signed diff
echo 'Diff in seconds (signed): ' . $scheduledAt->diffInSeconds($now, false) . "\n";
echo 'Seconds until scheduled: ' . $now->diffInSeconds($scheduledAt, false) . "\n";

echo "\n=== PROPER DELAY CALCULATION ===\n";
if ($scheduledAt->isAfter($now)) {
    $delay = $now->diffInSeconds($scheduledAt, false);
    echo 'Proper delay calculation: ' . $delay . " seconds\n";
} else {
    echo "Scheduled time is in the past\n";
}

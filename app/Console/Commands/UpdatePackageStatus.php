<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Package;
use Carbon\Carbon;

class UpdatePackageStatus extends Command
{

    protected $signature = 'app:update-package-status';
    protected $description = 'Update package statuses based on end_date and end_time';


    public function handle()
    {
        $now = Carbon::now();

        $packages = Package::where('status', '1')->get();

        foreach ($packages as $package) {
            if ($package->end_date) {
                // If end_time is provided, use it; otherwise default to 23:59:59
                $endTime = $package->end_time ?? '23:59:59';

                // Merge date + time into a Carbon instance
                $endDateTime = Carbon::parse($package->end_date . ' ' . $endTime);

                if ($now->greaterThan($endDateTime)) {
                    $package->status = '2';
                    $package->save();
                }
            }
        }

        $this->info('Package statuses updated successfully.');
    }
}

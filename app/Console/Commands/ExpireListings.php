<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use Carbon\Carbon;

class ExpireListings extends Command
{
    protected $signature = 'listings:expire';
    protected $description = 'Mark expired listings as inactive and update property status';

    public function handle()
    {
        $expiredListings = Listing::with('property')
            ->where('is_active', true)
            ->where('expiry_date', '<', Carbon::now())
            ->get();

        $count = 0;
        foreach ($expiredListings as $listing) {
            $listing->expire();
            $count++;
        }

        $this->info("Expired {$count} listing(s).");
        return Command::SUCCESS;
    }
}

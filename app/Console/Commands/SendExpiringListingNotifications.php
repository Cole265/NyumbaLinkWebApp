<?php
// app/Console/Commands/SendExpiringListingNotifications.php
// Create this file to handle expiring listing notifications

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;
use App\Notifications\ListingExpiringSoon;
use Carbon\Carbon;

class SendExpiringListingNotifications extends Command
{
    protected $signature = 'listings:notify-expiring';
    protected $description = 'Send notifications for listings expiring soon';

    public function handle()
    {
        // Get listings expiring in 3 days
        $expiringIn3Days = Listing::with(['property.landlord.user'])
            ->where('is_active', true)
            ->whereDate('expiry_date', Carbon::now()->addDays(3))
            ->get();

        foreach ($expiringIn3Days as $listing) {
            $listing->property->landlord->user->notify(
                new ListingExpiringSoon($listing, $listing->property, 3)
            );
        }

        $this->info("Sent {$expiringIn3Days->count()} expiring listing notifications.");
    }
}
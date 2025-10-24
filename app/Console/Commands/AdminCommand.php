<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AdminCommand extends Command
{
    protected $signature = 'hesoyam';
    protected $description = 'Promote a renter to admin and verify their email';

    public function handle()
    {
        $email = $this->ask('Enter the user\'s email address');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("🚫 No user found with email: $email");
            return Command::FAILURE;
        }

        if ($user->role === 'renter') {
            $user->role = 'admin';

            // Set email as verified
            $user->email_verified_at = Carbon::now();

            $user->save();

            $this->info("✅ User '{$user->email}' promoted to 'admin' and email verified.");
            return Command::SUCCESS;
        } else {
            $this->warn("ℹ️ User '{$user->email}' is not a 'renter'. No changes made.");
            return Command::SUCCESS;
        }
    }
}

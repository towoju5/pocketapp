<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {identifier : Email or username of the user to promote}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grants admin access to a user by email or username — the only way in before any admin exists, since /admin itself requires admin access.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $user = User::where('email', $identifier)->orWhere('username', $identifier)->first();

        if (!$user) {
            $this->error("No user found matching \"{$identifier}\".");

            return self::FAILURE;
        }

        $user->is_admin = true;
        $user->save();

        $this->info("{$user->username} ({$user->email}) is now an admin.");

        return self::SUCCESS;
    }
}

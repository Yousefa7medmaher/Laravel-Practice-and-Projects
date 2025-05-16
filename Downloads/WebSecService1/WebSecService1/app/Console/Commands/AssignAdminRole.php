<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignAdminRole extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Assign admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $user->assignRole('admin');
        $this->info("Successfully assigned admin role to user {$user->name}");
        return 0;
    }
} 
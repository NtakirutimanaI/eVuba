<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Employee;

class SyncUsersToEmployees extends Command
{
    // Command signature
    protected $signature = 'sync:users-to-employees';

    // Command description
    protected $description = 'Sync all existing users with roles admin, manager, employee into employees table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all users with role admin, manager, or employee
        $users = User::whereIn('role', ['admin', 'manager', 'employee'])->get();

        if ($users->isEmpty()) {
            $this->info('No users found with roles admin, manager, or employee.');
            return 0;
        }

        $this->info('Starting sync of users to employees table...');

        foreach ($users as $user) {
            Employee::updateOrCreate(
                ['id' => $user->id],
                [
                    'name'           => $user->name,
                    'email'          => $user->email,
                    'phone'          => $user->phone ?? null,
                    'position'       => ucfirst($user->role),
                    'specialization' => null,
                    'department'     => null,
                    'created_at'     => $user->created_at,
                    'updated_at'     => $user->updated_at,
                ]
            );
            $this->info("Synced user ID {$user->id}: {$user->name}");
        }

        $this->info('All eligible users have been synced to employees table successfully!');
        return 0;
    }
}

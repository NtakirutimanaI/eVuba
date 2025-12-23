<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Employee;

class SyncEmployees extends Command
{
    protected $signature = 'sync:employees';
    protected $description = 'Sync all users with role employee to employees table';

    public function handle()
    {
        // Get all users with role = employee
        $employees = User::where('role', 'employee')->get();

        foreach ($employees as $user) {
            // Check if already exists
            if (!Employee::where('id', $user->id)->exists()) {
                Employee::create([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'position' => 'Employee', // default position
                ]);
            }
        }

        $this->info('Employees table synced successfully!');
    }
}

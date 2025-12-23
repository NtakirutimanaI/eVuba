<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Seed a few notifications for each user
            $notifications = [
                [
                    'id' => Str::uuid(),
                    'type' => 'App\Notifications\SystemAlert',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'title' => 'Welcome to eVuba',
                        'message' => 'Thank you for joining our professional platform.',
                        'icon' => 'fa-door-open',
                        'action_url' => '#'
                    ]),
                    'read_at' => null,
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'id' => Str::uuid(),
                    'type' => 'App\Notifications\TaskAssigned',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'title' => 'New Task Assigned',
                        'message' => 'You have been assigned a new high-priority project task.',
                        'icon' => 'fa-tasks',
                        'action_url' => '/tasks'
                    ]),
                    'read_at' => now(),
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ],
                [
                    'id' => Str::uuid(),
                    'type' => 'App\Notifications\SecurityAlert',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'title' => 'Security Update',
                        'message' => 'Your profile security settings were successfully updated.',
                        'icon' => 'fa-shield-alt',
                        'action_url' => '/profile'
                    ]),
                    'read_at' => null,
                    'created_at' => now()->subMinutes(30),
                    'updated_at' => now()->subMinutes(30),
                ]
            ];

            DB::table('notifications')->insert($notifications);
        }
    }
}

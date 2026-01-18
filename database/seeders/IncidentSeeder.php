<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incident;
use App\Models\User;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user as the reporter
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found. Please create a user first!');
            return;
        }

        $incidents = [
            [
                'title' => 'Server Down',
                'description' => 'Main server is not responding.',
                'status' => 'Open',
                'user_id' => $user->id,
                'created_at' => '2026-01-16',
                'updated_at' => '2026-01-16',
            ],
            [
                'title' => 'Login Bug',
                'description' => 'Users cannot log in.',
                'status' => 'Investigating',
                'user_id' => $user->id,
                'created_at' => '2026-01-15',
                'updated_at' => '2026-01-15',
            ],
            [
                'title' => 'Email Issue',
                'description' => 'Emails are not being sent.',
                'status' => 'Resolved',
                'user_id' => $user->id,
                'created_at' => '2026-01-14',
                'updated_at' => '2026-01-14',
            ],
            [
                'title' => 'Payment Failure',
                'description' => 'Payment gateway returns errors.',
                'status' => 'Closed',
                'user_id' => $user->id,
                'created_at' => '2026-01-12',
                'updated_at' => '2026-01-12',
            ],
        ];

        foreach ($incidents as $incident) {
            Incident::create($incident);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MonthlyContribution;
use Carbon\Carbon;

class ContributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array of 10 Kenyan names for testing
        $names = [
            'John Mwangi',
            'Grace Wanjiku',
            'Peter Kimani',
            'Faith Njeri',
            'David Otieno',
            'Mercy Achieng',
            'James Kariuki',
            'Esther Wairimu',
            'Paul Karanja',
            'Mary Chebet',
        ];

        $currentYear = Carbon::now()->year;

        foreach ($names as $name) {
            $user = User::firstOrCreate(
                ['email' => $this->generateEmail($name)],
                [
                    'name' => $name,
                    'username' => $this->generateUsername($name),
                    'phone' => $this->generatePhone(),
                    'password' => bcrypt('password'), // default password
                ]
            );

            // Seed contributions for all 12 months
            foreach (range(1, 12) as $month) {
                $amountDue = rand(400, 600);
                $penalty   = rand(0, 200);
                $status    = rand(0, 1) ? 'paid' : 'unpaid';

                MonthlyContribution::create([
                    'user_id'      => $user->id,
                    'month'        => $month,
                    'year'         => $currentYear,
                    'amount_due'   => $amountDue,
                    'penalty'      => $penalty,
                    'total_amount' => $amountDue + $penalty,
                    'status'       => $status,
                ]);
            }
        }
    }

    /**
     * Generate a simple email from the name.
     */
    private function generateEmail(string $name): string
    {
        return strtolower(str_replace(' ', '.', $name)) . '@example.com';
    }

    /**
     * Generate a simple username from the name.
     */
    private function generateUsername(string $name): string
    {
        return strtolower(str_replace(' ', '.', $name)) . rand(100, 999);
    }

    /**
     * Generate a random Kenyan phone number.
     */
    private function generatePhone(): string
    {
        return '2547' . rand(10000000, 99999999);
    }
}

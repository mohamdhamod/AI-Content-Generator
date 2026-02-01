<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin users
        $adminUsers = [
            [
                'name' => "Mohammad Hammoud",
                'phone' => '+963936263906',
                'email' => "mohamdhamod46@gmail.com"
            ],
        ];

        foreach ($adminUsers as $adminUser) {
            $this->createUser($adminUser, RoleEnum::ADMIN);
        }

        // Manager users
        $managerUsers = [
            [
                'name' => "Content Manager",
                'phone' => '+963900000001',
                'email' => "manager@medical-ai.com"
            ],
        ];

        foreach ($managerUsers as $managerUser) {
            $this->createUser($managerUser, RoleEnum::MANAGER);
        }

        // Demo subscriber users
        $subscriberUsers = [
            [
                'name' => "Demo Clinic",
                'phone' => '+963900000002',
                'email' => "demo@medical-ai.com",
                'subscription' => [
                    'subscription_id' => 2, // Professional plan
                    'duration_months' => 1,
                ],
            ],
        ];

        foreach ($subscriberUsers as $subscriberUser) {
            $this->createUser($subscriberUser, RoleEnum::SUBSCRIBER, 'Demo@2026');
        }
    }

    protected function createUser(array $userData, $role, $password = null)
    {
        if (empty($userData['email'])) {
            return;
        }

        $subscriptionData = $userData['subscription'] ?? null;
        unset($userData['subscription']);

        DB::transaction(function () use ($userData, $role, $password, $subscriptionData) {
            $isExistingUser = User::where('email', $userData['email'])->exists();

            $attributesToUpdate = array_merge($userData, [
                'email_verified_at' => now(),
            ]);

            if (!$isExistingUser) {
                $attributesToUpdate['password'] = $password ?? 'Cl1ns3rv@46';
            } elseif ($password !== null) {
                $attributesToUpdate['password'] = $password;
            }

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $attributesToUpdate
            );

            if ($role) {
                $user->syncRoles([$role]);
            }

            // Create subscription for subscriber users
            if (is_array($subscriptionData) && !empty($subscriptionData) && $role === RoleEnum::SUBSCRIBER) {
                $this->createUserSubscription($user, $subscriptionData);
            }
        });
    }

    protected function createUserSubscription(User $user, array $subscriptionData): void
    {
        $startDate = now();
        $durationMonths = $subscriptionData['duration_months'] ?? 1;
        $expiryDate = (clone $startDate)->addMonths($durationMonths);

        UserSubscription::updateOrInsert(
            [
                'user_id' => $user->id,
                'subscription_id' => $subscriptionData['subscription_id'] ?? 1,
            ],
            [
                'digistore_order_id' => 'DEMO-' . strtoupper(uniqid()),
                'amount' => 0, // Demo subscription
                'currency' => 'EUR',
                'status' => 'active',
                'started_at' => $startDate,
                'expires_at' => $expiryDate,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}

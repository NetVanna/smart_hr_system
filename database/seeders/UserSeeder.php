<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@smarthr.com'],
            [
                'name'     => 'System Super Admin',
                'password' => Hash::make('password123'),
                'role'     => 'Super Admin',
            ]
        );

        // 2. Demo Company & Company Admin
        $company = \App\Models\Company::firstOrCreate(
            ['email' => 'contact@attendee.demo'],
            [
                'name'                => 'HR Attendee Corp',
                'phone'               => '012345678',
                'subscription_plan'   => 'Pro Plan',
                'subscription_status' => 'Active',
                'onboarding_step'     => 'completed',
            ]
        );

        // Company Admin
        User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'company_id'  => $company->id,
                'name'        => 'Michael Mitc',
                'phone'       => '012345678',
                'employee_id' => 'EMP-001',
                'password'    => Hash::make('password123'),
                'role'        => 'Company Admin',
                'telegram_username' => 'michael_mitc',
            ]
        );

        // HR Manager
        User::firstOrCreate(
            ['email' => 'hrmanager@demo.com'],
            [
                'company_id'  => $company->id,
                'name'        => 'Sokha Chea',
                'phone'       => '012888999',
                'employee_id' => 'HR-001',
                'password'    => Hash::make('password123'),
                'role'        => 'HR Manager',
                'telegram_username' => 'sokha_hr',
            ]
        );

        // Employee
        User::firstOrCreate(
            ['email' => 'employee@demo.com'],
            [
                'company_id'  => $company->id,
                'name'        => 'Jane Hawkins',
                'phone'       => '098765432',
                'employee_id' => 'EMP-002',
                'password'    => Hash::make('password123'),
                'role'        => 'Employee',
            ]
        );

        // 3. Demo Branches in Cambodia
        $branches = [
            [
                'name'            => 'Phnom Penh Head Office',
                'code'            => 'PP-01',
                'phone'           => '023 888 999',
                'address'         => '#123 Street 271, Sangkat Boeng Tumpun, Khan Mean Chey, Phnom Penh',
                'latitude'        => 11.5564,
                'longitude'       => 104.9282,
                'geofence_radius' => 150,
                'is_active'       => true,
            ],
            [
                'name'            => 'Siem Reap Provincial Branch',
                'code'            => 'SR-01',
                'phone'           => '063 777 888',
                'address'         => 'Street 08, Svay Dangkum, Siem Reap City',
                'latitude'        => 13.3633,
                'longitude'       => 103.8564,
                'geofence_radius' => 200,
                'is_active'       => true,
            ],
            [
                'name'            => 'Battambang Regional Hub',
                'code'            => 'BB-01',
                'phone'           => '053 666 555',
                'address'         => 'Road 1, Sangkat Svay Pao, Battambang City',
                'latitude'        => 13.0957,
                'longitude'       => 103.2022,
                'geofence_radius' => 100,
                'is_active'       => true,
            ],
            [
                'name'            => 'Sihanoukville Coastal Branch',
                'code'            => 'SHV-01',
                'phone'           => '034 555 444',
                'address'         => 'Ekareach Street, Sangkat 2, Preah Sihanouk City',
                'latitude'        => 10.6275,
                'longitude'       => 103.5221,
                'geofence_radius' => 100,
                'is_active'       => true,
            ],
        ];

        foreach ($branches as $b) {
            \App\Models\Branch::firstOrCreate(
                ['company_id' => $company->id, 'code' => $b['code']],
                $b
            );
        }

        // Active Subscription for HR Attendee Corp
        \App\Models\Subscription::firstOrCreate(
            ['company_id' => $company->id, 'plan' => 'Pro Plan'],
            [
                'price'      => 49.00,
                'start_date' => now()->subDays(10),
                'end_date'   => now()->addDays(20),
                'status'     => 'Active',
            ]
        );

        // 4. Additional Cambodian SaaS Tenants for SuperAdmin Overview
        // Company 2: Angkor Logistics (Siem Reap) - Active Paid
        $angkor = \App\Models\Company::firstOrCreate(
            ['email' => 'contact@angkorlogistics.kh'],
            [
                'name'                => 'Angkor Express Logistics Co., Ltd.',
                'phone'               => '063 999 111',
                'address'             => 'National Road 6, Svay Dangkum, Siem Reap',
                'subscription_plan'   => 'Enterprise',
                'subscription_status' => 'Active',
                'onboarding_step'     => 'completed',
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin@angkorlogistics.kh'],
            [
                'company_id'  => $angkor->id,
                'name'        => 'Vannak Heng',
                'phone'       => '063999111',
                'password'    => Hash::make('password123'),
                'role'        => 'Company Admin',
                'telegram_username' => 'vannak_angkor',
            ]
        );
        \App\Models\Subscription::firstOrCreate(
            ['company_id' => $angkor->id, 'plan' => 'Enterprise'],
            [
                'price'      => 99.00,
                'start_date' => now()->subDays(15),
                'end_date'   => now()->addDays(15),
                'status'     => 'Active',
            ]
        );

        // Company 3: Mekong Tech (Phnom Penh) - Pending Approval with ABA KHQR Receipt Slip
        $mekong = \App\Models\Company::firstOrCreate(
            ['email' => 'contact@mekongtech.kh'],
            [
                'name'                => 'Mekong Digital Solutions',
                'phone'               => '023 777 222',
                'address'             => 'Monivong Blvd, Sangkat Boeung Keng Kang 1, Phnom Penh',
                'subscription_plan'   => 'Growth',
                'subscription_status' => 'Pending',
                'onboarding_step'     => 'completed',
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin@mekongtech.kh'],
            [
                'company_id'  => $mekong->id,
                'name'        => 'Dara Meas',
                'phone'       => '023777222',
                'password'    => Hash::make('password123'),
                'role'        => 'Company Admin',
                'telegram_username' => 'dara_mekong',
            ]
        );
        \App\Models\Subscription::firstOrCreate(
            ['company_id' => $mekong->id, 'plan' => 'Growth'],
            [
                'price'         => 79.00,
                'start_date'    => now(),
                'end_date'      => now()->addMonth(),
                'status'        => 'Pending Approval',
                'receipt_path'  => 'receipts/sample_khqr_slip.png',
            ]
        );

        // Company 4: Battambang Agrotech - 14-Day Trial
        $battambang = \App\Models\Company::firstOrCreate(
            ['email' => 'contact@battambangagro.kh'],
            [
                'name'                => 'Battambang Green Agrotech Co.',
                'phone'               => '053 444 888',
                'address'             => 'Street 3, Sangkat Rattanak, Battambang City',
                'subscription_plan'   => '14-Day Free Trial',
                'subscription_status' => 'Trial',
                'onboarding_step'     => 'completed',
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin@battambangagro.kh'],
            [
                'company_id'  => $battambang->id,
                'name'        => 'Bopha Pich',
                'phone'       => '053444888',
                'password'    => Hash::make('password123'),
                'role'        => 'Company Admin',
                'telegram_username' => 'bopha_agro',
            ]
        );

        // 5. Support Tickets for SuperAdmin
        \App\Models\Ticket::firstOrCreate(
            ['company_id' => $mekong->id, 'subject' => 'Payment Verification for KHQR Transfer'],
            [
                'user_id'  => User::where('email', 'admin@mekongtech.kh')->first()?->id ?? 1,
                'message'  => 'Hello SuperAdmin, we just transferred $79 via ABA KHQR for the Growth plan. Please verify our slip and activate our workspace.',
                'priority' => 'High',
                'status'   => 'Open',
            ]
        );

        \App\Models\Ticket::firstOrCreate(
            ['company_id' => $angkor->id, 'subject' => 'Requesting Branch Geofence Expansion'],
            [
                'user_id'  => User::where('email', 'admin@angkorlogistics.kh')->first()?->id ?? 1,
                'message'  => 'Could you help us configure multi-branch radius up to 500m for our warehouse depot near Siem Reap airport?',
                'priority' => 'Medium',
                'status'   => 'In Progress',
            ]
        );
    }
}

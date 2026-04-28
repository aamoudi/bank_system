<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Debit;
use App\Models\IncomeType;
use App\Models\ExpenseType;
use App\Models\Currency;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class GuestDemoDataSeeder extends Seeder
{
    public function run()
    {
        // =============================================
        // 1) GET OR CREATE GUEST USER
        // =============================================
        $guest = User::where('email', 'guest@masareefi.com')->first();

        if (!$guest) {
            $guest = User::create([
                'first_name'        => 'Guest',
                'last_name'         => 'Demo',
                'email'             => 'guest@masareefi.com',
                'password'          => Hash::make('password'),
                'gender'            => 'M',
                'active'            => true,
                'city_id'           => City::first()->id,
                'email_verified_at' => now(),
            ]);

            $role = Role::findByName('User', 'user');
            if ($role) {
                $guest->assignRole($role);
            }
        }

        // =============================================
        // 2) CREATE GUEST CHILDREN (sub-users)
        // =============================================
        $childrenData = [
            [
                'first_name'  => 'Sarah',
                'last_name'   => 'Demo',
                'email'       => 'sarah.demo@masareefi.com',
                'gender'      => 'F',
                'id_number'   => '123456781',
                'city_id'     => City::inRandomOrder()->first()->id,
            ],
            [
                'first_name'  => 'John',
                'last_name'   => 'Demo',
                'email'       => 'john.demo@masareefi.com',
                'gender'      => 'M',
                'id_number'   => '123456782',
                'city_id'     => City::inRandomOrder()->first()->id,
            ],
            [
                'first_name'  => 'Emma',
                'last_name'   => 'Demo',
                'email'       => 'emma.demo@masareefi.com',
                'gender'      => 'F',
                'id_number'   => '123456783',
                'city_id'     => City::inRandomOrder()->first()->id,
            ],
        ];

        $children = [];
        foreach ($childrenData as $childData) {
            $child = User::where('email', $childData['email'])->first();
            if (!$child) {
                $child = User::create([
                    'first_name'        => $childData['first_name'],
                    'last_name'         => $childData['last_name'],
                    'email'             => $childData['email'],
                    'password'          => Hash::make('password'),
                    'gender'            => $childData['gender'],
                    'active'            => true,
                    'city_id'           => $childData['city_id'],
                    'id_number'         => $childData['id_number'],
                    'parent'            => $guest->id,
                    'email_verified_at' => now(),
                ]);

                $role = Role::findByName('User', 'user');
                if ($role) {
                    $child->assignRole($role);
                }
            }
            $children[] = $child;
        }

        // =============================================
        // 3) GET CURRENCIES
        // =============================================
        $usd = Currency::where('name', 'LIKE', '%USD%')->first()
            ?? Currency::first();
        $sar = Currency::where('name', 'LIKE', '%SAR%')->first()
            ?? Currency::skip(1)->first();
        $ils = Currency::where('name', 'LIKE', '%ILS%')->first()
            ?? Currency::skip(2)->first();
        $jod = Currency::where('name', 'LIKE', '%JOD%')->first()
            ?? Currency::skip(3)->first();

        // =============================================
        // 4) CREATE WALLETS FOR GUEST
        // =============================================
        $walletsData = [
            [
                'name'        => 'Main USD Wallet',
                'balance'     => 5420.75,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Savings SAR',
                'balance'     => 18750.00,
                'currency_id' => $sar->id,
                'active'      => true,
            ],
            [
                'name'        => 'Daily Expenses ILS',
                'balance'     => 3200.50,
                'currency_id' => $ils->id,
                'active'      => true,
            ],
            [
                'name'        => 'Emergency Fund JOD',
                'balance'     => 1500.00,
                'currency_id' => $jod->id,
                'active'      => true,
            ],
        ];

        $wallets = [];
        foreach ($walletsData as $walletData) {
            $wallet = Wallet::where('user_id', $guest->id)
                ->where('name', $walletData['name'])
                ->first();

            if (!$wallet) {
                $wallet = Wallet::create(array_merge(
                    $walletData,
                    ['user_id' => $guest->id]
                ));
            }
            $wallets[] = $wallet;
        }

        // =============================================
        // 5) CREATE INCOME TYPES FOR GUEST
        // =============================================
        $incomeData = [
            [
                'name'        => 'Monthly Salary',
                'amount'      => 3500,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Freelance Projects',
                'amount'      => 850,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Rental Income',
                'amount'      => 1200,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Investment Returns',
                'amount'      => 420,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Part-time Tutoring',
                'amount'      => 300,
                'currency_id' => $ils->id,
                'active'      => true,
            ],
            [
                'name'        => 'E-commerce Sales',
                'amount'      => 650,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
        ];

        foreach ($incomeData as $income) {
            $exists = IncomeType::where('user_id', $guest->id)
                ->where('name', $income['name'])
                ->exists();

            if (!$exists) {
                IncomeType::create(array_merge(
                    $income,
                    [
                        'user_id'          => $guest->id,
                        'transaction_flag' => 0,
                    ]
                ));
            }
        }

        // =============================================
        // 6) CREATE EXPENSE TYPES FOR GUEST
        // =============================================
        $expenseData = [
            [
                'name'        => 'Rent',
                'amount'      => 800,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Groceries',
                'amount'      => 350,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Utilities',
                'amount'      => 120,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Transportation',
                'amount'      => 200,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Health Insurance',
                'amount'      => 150,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Internet & Phone',
                'amount'      => 80,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Entertainment',
                'amount'      => 100,
                'currency_id' => $usd->id,
                'active'      => true,
            ],
            [
                'name'        => 'Education',
                'amount'      => 250,
                'currency_id' => $ils->id,
                'active'      => true,
            ],
        ];

        foreach ($expenseData as $expense) {
            $exists = ExpenseType::where('user_id', $guest->id)
                ->where('name', $expense['name'])
                ->exists();

            if (!$exists) {
                ExpenseType::create(array_merge(
                    $expense,
                    ['user_id' => $guest->id]
                ));
            }
        }

        // =============================================
        // 7) CREATE DEBITS FOR GUEST
        // =============================================
        $debitsData = [
            [
                'title'        => 'Car Loan',
                'total'        => 12000,
                'remain'       => 7500,
                'type'         => 'Debtor',
                'payment_type' => 'Multi',
                'date'         => '2024-01-15',
                'user_id'      => $children[0]->id,
                'currecny_id'  => $usd->id,
            ],
            [
                'title'        => 'Borrowed from Friend',
                'total'        => 500,
                'remain'       => 500,
                'type'         => 'Debtor',
                'payment_type' => 'Single',
                'date'         => '2024-03-10',
                'user_id'      => $children[1]->id,
                'currecny_id'  => $usd->id,
            ],
            [
                'title'        => 'Lent to Brother',
                'total'        => 1500,
                'remain'       => 1000,
                'type'         => 'Creditor',
                'payment_type' => 'Multi',
                'date'         => '2024-02-20',
                'user_id'      => $children[2]->id,
                'currecny_id'  => $ils->id,
            ],
            [
                'title'        => 'Home Appliances',
                'total'        => 2000,
                'remain'       => 800,
                'type'         => 'Debtor',
                'payment_type' => 'Multi',
                'date'         => '2023-11-05',
                'user_id'      => $children[0]->id,
                'currecny_id'  => $sar->id,
            ],
            [
                'title'        => 'Business Investment',
                'total'        => 5000,
                'remain'       => 5000,
                'type'         => 'Creditor',
                'payment_type' => 'Single',
                'date'         => '2024-04-01',
                'user_id'      => $children[1]->id,
                'currecny_id'  => $usd->id,
            ],
        ];

        foreach ($debitsData as $debit) {
            $exists = Debit::where('user_id', $guest->id)
                ->where('title', $debit['title'])
                ->exists();

            if (!$exists) {
                DB::table('debits')->insert(array_merge(
                    $debit,
                    [
                        'main_user_id' => $guest->id,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]
                ));
            }
        }

        // =============================================
        // 8) ASSIGN PERMISSIONS TO GUEST
        // =============================================
        $readPermissions = [
            'Read-Users',
            'Read-Income-Types',
            'Read-Expense-Type',
            'Read-Wallets',
            'Read-Debits',
        ];

        foreach ($readPermissions as $permName) {
            $permission = \Spatie\Permission\Models\Permission::where('name', $permName)
                ->where('guard_name', 'user')
                ->first();

            if ($permission && !$guest->hasPermissionTo($permission)) {
                $guest->givePermissionTo($permission);
            }
        }

        $this->command->info('✅ Guest demo data seeded successfully!');
        $this->command->info('   Guest email: guest@masareefi.com');
        $this->command->info('   Guest password: password');
        $this->command->info('   Children: ' . count($children));
        $this->command->info('   Wallets: ' . count($wallets));
        $this->command->info('   Income types: ' . count($incomeData));
        $this->command->info('   Expense types: ' . count($expenseData));
        $this->command->info('   Debits: ' . count($debitsData));
    }
}

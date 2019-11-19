<?php

use Illuminate\Database\Seeder;

class InitializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Role::create([
            'id' => 1,
            'name' => 'non role'
        ]);
        App\Models\Role::create([
            'id' => 2,
            'name' => 'admin'
        ]);
        App\Models\Role::create([
            'id' => 3,
            'name' => 'applicant'
        ]);
        App\Models\Role::create([
            'id' => 4,
            'name' => 'referee'
        ]);
        App\Models\Role::create([
            'id' => 5,
            'name' => 'viewer'
        ]);
        App\Models\Role::create([
            'id' => 6,
            'name' => 'superadmin'
        ]);

        App\Models\User::create([
                'id' => 1,
                'email' => 'sahakian@g.hmc.edu',
                'password_salt' => 10,
                'password' => Hash::make('222222'),
                'state' => 'active',
                'requested_role_id' => 0,
                'role_id' => 6,
                'confirmation' => 1
                ]);

        App\Models\Person::create([
            'user_id' => 1,
            'type' => null
        ]);
    }
}

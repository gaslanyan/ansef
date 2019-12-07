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

        App\Models\Degree::create([
            'id' => 1,
            'text' => 'None'
        ]);
        App\Models\Degree::create([
            'id' => 2,
            'text' => 'Bachelor'
        ]);
        App\Models\Degree::create([
            'id' => 3,
            'text' => 'Masters'
        ]);
        App\Models\Degree::create([
            'id' => 4,
            'text' => 'Doctorate'
        ]);

        App\Models\User::create([
                'id' => 1,
                'email' => 'sahakian@g.hmc.edu',
                'password_salt' => 10,
                'password' => bcrypt('22222222'),
                'state' => 'active',
                'requested_role_id' => 0,
                'role_id' => 6,
                'confirmation' => 1
                ]);

        App\Models\Person::create([
            'user_id' => 1,
            'type' => null,
            'specialization' => '',
            'first_name' => '',
            'last_name' => ''
        ]);

        App\Models\User::create([
            'id' => 2,
            'email' => 'vvsahakian@me.com',
            'password_salt' => 10,
            'password' => bcrypt('22222222'),
            'state' => 'active',
            'requested_role_id' => 0,
            'role_id' => 3,
            'confirmation' => 1
        ]);

        App\Models\Person::create([
            'user_id' => 2,
            'type' => null,
            'specialization' => '',
            'first_name' => '',
            'last_name' => ''
        ]);

        App\Models\User::create([
            'id' => 3,
            'email' => 'sahakian@hmc.edu',
            'password_salt' => 10,
            'password' => bcrypt('22222222'),
            'state' => 'active',
            'requested_role_id' => 0,
            'role_id' => 4,
            'confirmation' => 1
        ]);

        App\Models\Person::create([
            'user_id' => 3,
            'type' => null,
            'specialization' => '',
            'first_name' => '',
            'last_name' => ''
        ]);

        App\Models\User::create([
            'id' => 4,
            'email' => 'dopplerthepom@gmail.com',
            'password_salt' => 10,
            'password' => bcrypt('22222222'),
            'state' => 'active',
            'requested_role_id' => 0,
            'role_id' => 5,
            'confirmation' => 1
        ]);

        App\Models\Person::create([
            'user_id' => 4,
            'type' => null,
            'specialization' => '',
            'first_name' => '',
            'last_name' => ''
        ]);

        App\Models\Category::create([
            'id' => 1,
            'abbreviation' => 'PS',
            'title' => 'Physical Sciences',
            'weight' => 1,
            'parent_id' => null
        ]);
        App\Models\Category::create([
            'id' => 2,
            'abbreviation' => 'NS',
            'title' => 'Natural Sciences',
            'weight' => 1,
            'parent_id' => null
        ]);
        App\Models\Category::create([
            'id' => 3,
            'abbreviation' => 'astroex',
            'title' => 'Experimental astrophysics',
            'weight' => 1,
            'parent_id' => 1
        ]);
        App\Models\Category::create([
            'id' => 4,
            'abbreviation' => 'bio',
            'title' => 'Biology',
            'weight' => 1,
            'parent_id' => 2
        ]);
        App\Models\Competition::create([
            'id' => 1,
            'title' => 'AN20',
            'description' => 'Standard ANSEF competition',
            'announcement_date' => date("Y-m-d", strtotime("-2 day")),
            'submission_start_date' => date("Y-m-d", strtotime("-1 day")),
            'submission_end_date' => date("Y-m-d", strtotime("+1 day")),
            'project_start_date' => date("Y-m-d", strtotime("+2 day")),
            'min_budget' => 1000,
            'max_budget' => 5000,
            'min_level_deg_id' => 2,
            'max_level_deg_id' => 1,
            'min_age' => 0,
            'max_age' => 100,
            'allow_foreign' => '0',
            'comments' => 'Competition comments',
            'first_report' => date("Y-m-d", strtotime("+15 day")),
            'second_report' => date("Y-m-d", strtotime("+25 day")),
            'state' => 'enable',
            'recommendations' => 2,
            'categories' => '["1","2"]',
            'additional' => '{"additional_charge_name":null,"additional_charge":"0","additional_percentage_name":"Overhead percentage","additional_percentage":"7"}',
            'instructions' => 'Instructions for proposal document'
        ]);
        App\Models\BudgetCategory::create([
            'name' => 'PI Salary',
            'min' => 0,
            'max' => 5000,
            'weight' => 1,
            'comments' => 'Monthly amount, number of months',
            'competition_id' => 1
        ]);


    }
}

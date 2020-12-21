<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserSeeder::class);

        $users = [ 
            [
                'master' => 1,
                'name' => 'master',
                'email' => 'master@erp.com',
                'password' => bcrypt('secret')
            ],
            [
                'master' => 1,
                'name' => 'pmu',
                'email' => 'pmu@ord.com',
                'password' => bcrypt('secret')
            ]
        ];

        \DB::table('users')->insert($users);

        $roles = [ 
            ['role' => 'Team Head'],
            ['role' => 'Team Sub Head'],
            ['role' => 'User'],
            ['role' => 'Sub User'],
            ['role' => 'Partner 1'],
            ['role' => 'Partner 2'],
            ['role' => 'Client'],
        ];
        \DB::table('roles')->insert($roles);

        $statuses = [ 
            ['status' => 'Initiated','keyword' => 'Initiate'],
            ['status' => 'Work in Progess','keyword' => 'Work in Progess'],
            ['status' => 'On Revision','keyword' => 'On Revision'],
            ['status' => 'On Hold','keyword' => 'On Hold'],
            ['status' => 'Rejected','keyword' => 'Reject'],
            ['status' => 'Approved','keyword' => 'Approve'],
            ['status' => 'Completed','keyword' => 'Complete'],
            ['status' => 'Pending','keyword' => 'Pending'],
            ['status' => 'PMU','keyword' => 'shared with PMU'],
            ['status' => 'Client','keyword' => 'shared with Client'],
            ['status' => 'Closed','keyword' => 'Closed'],
        ];
        \DB::table('statuses')->insert($statuses);

        
        $departments = [ 
            ['department' => 'Creative'],
            ['department' => 'Media'],
            ['department' => 'Digital'],
            ['department' => 'Social'],
            ['department' => 'BTL/Surveys']
        ];
        \DB::table('departments')->insert($departments);

        $districts = [ 
            ['district' => 'Badin'],
            ['district' => 'Sujawal'],
            ['district' => 'Tharparkar'],
            ['district' => 'UmerKot'],
            ['district' => 'Sanghar'],
            ['district' => 'Jacobabad'],
            ['district' => 'Kashmore'],
            ['district' => 'Ghotki'],
            ['district' => 'KambarShahdadkot'],
            ['district' => 'Khairpur']
        ];
        \DB::table('districts')->insert($districts);
    }
}

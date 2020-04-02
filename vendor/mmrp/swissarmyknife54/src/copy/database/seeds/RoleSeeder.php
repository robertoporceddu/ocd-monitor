<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Root',
            'slug' => normalize_name('Root'),
            'created_at' => \Carbon\Carbon::now()
        ]);

        $attach_permissions = [];

        for($i = 1; $i <= 66; $i++){
            $attach_permissions[] = [
               'permission_id' => $i,
                'role_id' => 1,
                'created_at' => \Carbon\Carbon::now()
            ];
        }

        DB::table('permission_role')->insert($attach_permissions);

        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'created_at' => \Carbon\Carbon::now()
        ]);
    }
}

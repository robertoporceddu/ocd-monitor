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
        if(!DB::table('roles')->where('slug',normalize_name('Root'))->count()) {
            DB::table('roles')->insert([
                'name' => 'Root',
                'slug' => normalize_name('Root'),
                'created_at' => \Carbon\Carbon::now()
            ]);
            echo "Adding Root Role\n";
        }

        for($i = 1; $i <= DB::table('permissions')->count(); $i++) {
            if(!DB::table('permission_role')->where('permission_id',$i)->where('role_id',1)->count()) {
                DB::table('permission_role')->insert([
                    'permission_id' => $i,
                    'role_id' => 1,
                    'created_at' => \Carbon\Carbon::now()
                ]);
                echo "Adding Permission $i to Root Role\n";
            }
        }

        // if(!DB::table('role_user')->where('user_id',1)->where('role_id',1)->count()) {
        //     DB::table('role_user')->insert([
        //         'user_id' => 1,
        //         'role_id' => 1,
        //         'created_at' => \Carbon\Carbon::now()
        //     ]);
        //     echo "Associate Root Role with Root User\n";
        // }
    }
}

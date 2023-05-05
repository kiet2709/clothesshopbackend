<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models as Database;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();

        $roles = [
        	['ADMIN', 'Admin of system'],
            ['USER', 'User simple'],
        ];

        foreach ($roles as $role) {
            Database\Role::create([
                'name' => $role[0],
                'description' => $role[1],
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}

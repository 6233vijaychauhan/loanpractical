<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check = User::where('role_id',1)->count();

        if($check == 0){
            DB::table('users')->insert([
                'role_id' => 1,
                'name' => "Admin",
                'email' => "admin@gmail.com",
                'password' => bcrypt('12345678'),
                'status' => 1,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}

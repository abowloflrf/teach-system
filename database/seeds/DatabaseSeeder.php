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
        DB::table('users')->insert([
            'name' => '学生甲',
            'email' => '1000@ustb.edu.cn',
            'role' => 1,
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '学生乙',
            'email' => '1001@ustb.edu.cn',
            'role' => 1,
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '老师A',
            'email' => '2000@ustb.edu.cn',
            'role' => 2,
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '老师B',
            'email' => '2001@ustb.edu.cn',
            'role' => 2,
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '老师C',
            'email' => '2002@ustb.edu.cn',
            'role' => 2,
            'password' => bcrypt('password'),
        ]);
        DB::table('users')->insert([
            'name' => '唯一秘书',
            'email' => '3000@ustb.edu.cn',
            'role' => 3,
            'password' => bcrypt('password'),
        ]);
    }
}

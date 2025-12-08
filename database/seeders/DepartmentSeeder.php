<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['code' => 'CSE', 'name' => 'Computer Science & Engineering', 'head_user_id' => null],
            ['code' => 'ECE', 'name' => 'Electronics & Communication Engineering', 'head_user_id' => null],
            ['code' => 'ME', 'name' => 'Mechanical Engineering', 'head_user_id' => null],
            ['code' => 'CE', 'name' => 'Civil Engineering', 'head_user_id' => null],
            ['code' => 'EEE', 'name' => 'Electrical & Electronics Engineering', 'head_user_id' => null],
            ['code' => 'IT', 'name' => 'Information Technology', 'head_user_id' => null],
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'code' => $department['code'],
                'name' => $department['name'],
                'head_user_id' => $department['head_user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Teacher;

class SubjectsTeachersSeeder extends Seeder
{
    public function run(): void
    {
        // Create subjects
        $subjects = [
            ['name' => 'Java Programming', 'code' => 'CS301', 'is_active' => true],
            ['name' => 'Database Management', 'code' => 'CS302', 'is_active' => true],
            ['name' => 'Web Development', 'code' => 'CS303', 'is_active' => true],
            ['name' => 'Data Structures', 'code' => 'CS304', 'is_active' => true],
            ['name' => 'Operating Systems', 'code' => 'CS305', 'is_active' => true],
        ];

        foreach ($subjects as $subjectData) {
            Subject::create($subjectData);
        }

        // Create teachers
        $teachers = [
            ['name' => 'Dr. Rajesh Kumar', 'email' => 'rajesh.kumar@university.edu', 'department' => 'Computer Science', 'designation' => 'Professor'],
            ['name' => 'Prof. Anita Sharma', 'email' => 'anita.sharma@university.edu', 'department' => 'Computer Science', 'designation' => 'Associate Professor'],
            ['name' => 'Dr. Suresh Patel', 'email' => 'suresh.patel@university.edu', 'department' => 'Computer Science', 'designation' => 'Assistant Professor'],
            ['name' => 'Dr. Vijay Singh', 'email' => 'vijay.singh@university.edu', 'department' => 'Information Technology', 'designation' => 'Professor'],
            ['name' => 'Prof. Meena Reddy', 'email' => 'meena.reddy@university.edu', 'department' => 'Information Technology', 'designation' => 'Assistant Professor'],
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create($teacherData);
        }

        // Assign teachers to subjects
        $java = Subject::where('code', 'CS301')->first();
        $java->teachers()->attach([1, 2, 3]); // 3 teachers for Java

        $db = Subject::where('code', 'CS302')->first();
        $db->teachers()->attach([2, 4]); // 2 teachers for DB

        $web = Subject::where('code', 'CS303')->first();
        $web->teachers()->attach([3, 5]); // 2 teachers for Web Dev

        $ds = Subject::where('code', 'CS304')->first();
        $ds->teachers()->attach([1, 4, 5]); // 3 teachers for DS

        $os = Subject::where('code', 'CS305')->first();
        $os->teachers()->attach([1, 2]); // 2 teachers for OS
    }
}

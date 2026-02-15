<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Division;
use App\Models\Batch;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Subjects for Semester 4, IT Branch
        $subjects = [
            ['code' => 'TUC202', 'name' => 'FUNDAMENTALS OF DATABASE MANAGEMENT SYSTEMS', 'short' => 'DBMS', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'CEU201', 'name' => 'FUNDAMENTALS OF SOFTWARE ENGINEERING', 'short' => 'SE', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'CEUC202', 'name' => 'COMPUTER ORGANIZATION AND ARCHITECTURE', 'short' => 'COA', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'ITUE204', 'name' => 'DESIGN AND ANALYSIS OF ALGORITHMS', 'short' => 'DAA', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'C-BUE200', 'name' => 'FOUNDATION OF DATA SCIENCE AND ANALYTICS', 'short' => 'DSA', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'ITUE205', 'name' => 'FUNDAMENTALS OF INFORMATION SECURITY', 'short' => 'HNY', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'SBS', 'name' => 'Software Based Subjects', 'short' => 'SBS', 'type' =>  'Lab', 'credits' => 1],
            ['code' => 'PMP', 'name' => 'Professional Mentoring Program', 'short' => 'PMP', 'type' => 'Lab', 'credits' => 1],
            ['code' => 'NHB', 'name' => 'National Health Board', 'short' => 'NHB', 'type' => 'Lecture', 'credits' => 1],
        ];

        foreach ($subjects as $subjectData) {
            Subject::firstOrCreate(
                ['subject_code' => $subjectData['code'], 'semester' => 4, 'branch' => 'IT'],
                [
                    'code' => $subjectData['code'],
                    'subject_name' => $subjectData['name'],
                    'name' => $subjectData['name'],
                    'subject_type' => $subjectData['type'],
                    'credits' => $subjectData['credits'],
                    'description' => $subjectData['name'],
                    'is_active' => true,
                    'has_lab' => $subjectData['type'] === 'Lab',
                ]
            );
        }

        // Create Subjects for Semester 6, IT Branch
        $sem6Subjects = [
            ['code' => 'IT348', 'name' => 'CRYPTOGRAPHY & NETWORK SECURITY', 'short' => 'MMA', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'IT365', 'name' => 'LANGUAGE PROCESSORS', 'short' => 'PMP', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'IT366', 'name' => 'MOBILE APPLICATION DEVELOPMENT', 'short' => 'SMP', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'IT367', 'name' => 'CLOUD COMPUTING', 'short' => 'SBS', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'IT368', 'name' => 'PROJECT-III', 'short' => 'BHP', 'type' => 'Lab', 'credits' => 2],
            ['code' => 'HS132.02', 'name' => 'A CONTRIBUTORY PERSONALITY DEVELOPMENT', 'short' => 'NP', 'type' => 'Lecture', 'credits' => 1],
            ['code' => 'IT398', 'name' => 'RESEARCH WRITING AND ETHICS', 'short' => 'BHP', 'type' => 'Lecture', 'credits' => 1],
            ['code' => 'IT399', 'name' => 'MASTERING COMPETITIVE PROGRAMMING', 'short' => 'PMP', 'type' => 'Lab', 'credits' => 1],
            ['code' => 'OCIT3002', 'name' => 'INTRODUCTION TO INTERNET OF THINGS', 'short' => 'BHP', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'OCIT3003', 'name' => 'BLOCKCHAIN AND ITS APPLICATIONS', 'short' => 'MMA', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'OCIT3004', 'name' => 'DEEP LEARNING', 'short' => 'HNY', 'type' => 'Lecture', 'credits' => 3],
            ['code' => 'CRNS', 'name' => 'Cryptography and Network Security', 'short' => 'CRNS', 'type' => 'Lab', 'credits' => 1],
            ['code' => 'LP', 'name' => 'Language Processors', 'short' => 'LP', 'type' => 'Lab', 'credits' => 1],
            ['code' => 'CC', 'name' => 'Cloud Computing', 'short' => 'CC', 'type' => 'Lab', 'credits' => 1],
            ['code' => 'CPD', 'name' => 'Contributory Personality Development', 'short' => 'CPD', 'type' => 'Practical', 'credits' => 0],
        ];

        foreach ($sem6Subjects as $subjectData) {
            Subject::firstOrCreate(
                ['subject_code' => $subjectData['code'], 'semester' => 6, 'branch' => 'IT'],
                [
                    'code' => $subjectData['code'],
                    'subject_name' => $subjectData['name'],
                    'name' => $subjectData['name'],
                    'subject_type' => $subjectData['type'],
                    'credits' => $subjectData['credits'],
                    'description' => $subjectData['name'],
                    'is_active' => true,
                    'has_lab' => $subjectData['type'] === 'Practical',
                ]
            );
        }

        // Create Faculty Members
        $faculties = [
            ['name' => 'DR. RAJNIK KATRIYA', 'code' => 'RSK'],
            ['name' => 'DR. BIMAL PATEL', 'code' => 'BHP'],
            ['name' => 'DR. ANERI PANDYA', 'code' => 'AKP'],
            ['name' => 'DR. PURVI PRAJAPATI', 'code' => 'PMP'],
            ['name' => 'DR. SANKET SUTHAR', 'code' => 'SBS'],
            ['name' => 'HEMANT YADAV', 'code' => 'HNY'],
            ['name' => 'MADHAV AJWALIA', 'code' => 'MMA'],
            ['name' => 'DR. PRIYANKA PATEL', 'code' => 'PPP'],
            ['name' => 'MIKIN PATEL', 'code' => 'MRP'],
            ['name' => 'DR. PRITESH PRAJAPATI', 'code' => 'PNP'],
            ['name' => 'DR. DHAVAL BHOI', 'code' => 'DB'],
            ['name' => 'DHAVAL PATEL', 'code' => 'DSP'],
            ['name' => 'SAGAR PATEL', 'code' => 'SGP'],
            ['name' => 'SAGAR M. PATEL', 'code' => 'SMP'],
            ['name' => 'RAVI PATEL', 'code' => 'RVP'],
            ['name' => 'JALPESH VASA', 'code' => 'JHV'],
            ['name' => 'NISHAT SHAIKH', 'code' => 'NAS'],
            ['name' => 'Miss Celine Davis', 'code' => 'CD'],
            ['name' => 'Ms. Jayshree Mehta', 'code' => 'JM'],
            ['name' => 'DR. NIRAV BHATT', 'code' => 'NHB'],
        ];

        foreach ($faculties as $facultyData) {
            Faculty::firstOrCreate(
                ['short_code' => $facultyData['code']],
                [
                    'faculty_name' => $facultyData['name'],
                    'email' => strtolower(str_replace([' ', '.'], ['', ''], $facultyData['code'])) . '@example.com',
                    'department' => 'IT',
                    'designation' => str_starts_with($facultyData['name'], 'DR.') ? 'Professor' : 'Assistant Professor',
                    'is_active' => true,
                ]
            );
        }

        // Create Divisions
        $divisions = [
            ['semester' => 4, 'branch' => 'IT', 'division_number' => 1],
            ['semester' => 4, 'branch' => 'IT', 'division_number' => 2],
            ['semester' => 6, 'branch' => 'IT', 'division_number' => 1],
            ['semester' => 6, 'branch' => 'IT', 'division_number' => 2],
        ];

        foreach ($divisions as $divisionData) {
            Division::firstOrCreate(
                [
                    'semester' => $divisionData['semester'],
                    'branch' => $divisionData['branch'],
                    'division_number' => $divisionData['division_number'],
                ],
                [
                    'name' => "{$divisionData['semester']}-{$divisionData['branch']}-{$divisionData['division_number']}",
                    'is_active' => true,
                ]
            );
        }

        // Create Batches for each division
        // Semester 4: Only A1, B1, C1 (3 batches)
        // Semester 6: All 8 batches (A1, A2, B1, B2, C1, C2, D1, D2)
        $allDivisions = Division::all();

        foreach ($allDivisions as $division) {
            // Semester 4 divisions have only 3 batches: A1, B1, C1
            if ($division->semester == 4) {
                $batchNames = ['A1', 'B1', 'C1'];
            } else {
                // Semester 6 divisions have 8 batches
                $batchNames = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'D1', 'D2'];
            }
            
            foreach ($batchNames as $batchName) {
                Batch::firstOrCreate(
                    [
                        'division_id' => $division->id,
                        'batch_name' => $batchName,
                    ],
                    [
                        'description' => "Batch {$batchName} for division {$division->name}",
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✓ Subjects, Faculties, Divisions, and Batches created successfully!');
        $this->command->info('Note: You can now manually add timetable entries through the admin panel.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'TCS (Tata Consultancy Services)',
                'type' => 'RECRUITER',
                'address' => 'Mumbai, Maharashtra',
                'contact_person' => 'HR Manager',
                'contact_email' => 'recruitment@tcs.com',
                'website' => 'https://www.tcs.com',
                'notes' => 'Leading IT services company',
            ],
            [
                'name' => 'Infosys',
                'type' => 'RECRUITER',
                'address' => 'Bangalore, Karnataka',
                'contact_person' => 'Talent Acquisition',
                'contact_email' => 'careers@infosys.com',
                'website' => 'https://www.infosys.com',
                'notes' => 'Global consulting and IT services',
            ],
            [
                'name' => 'Wipro',
                'type' => 'RECRUITER',
                'address' => 'Bangalore, Karnataka',
                'contact_person' => 'Campus Recruitment',
                'contact_email' => 'campus@wipro.com',
                'website' => 'https://www.wipro.com',
                'notes' => 'Leading IT services provider',
            ],
            [
                'name' => 'Tech Mahindra',
                'type' => 'TRAINER',
                'address' => 'Pune, Maharashtra',
                'contact_person' => 'Training Coordinator',
                'contact_email' => 'training@techmahindra.com',
                'website' => 'https://www.techmahindra.com',
                'notes' => 'Provides training and placement',
            ],
            [
                'name' => 'Cognizant',
                'type' => 'RECRUITER',
                'address' => 'Chennai, Tamil Nadu',
                'contact_person' => 'HR Department',
                'contact_email' => 'hr@cognizant.com',
                'website' => 'https://www.cognizant.com',
                'notes' => 'IT and business consulting',
            ],
        ];

        foreach ($companies as $company) {
            DB::table('companies')->insert([
                'name' => $company['name'],
                'type' => $company['type'],
                'address' => $company['address'],
                'contact_person' => $company['contact_person'],
                'contact_email' => $company['contact_email'],
                'website' => $company['website'],
                'notes' => $company['notes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}


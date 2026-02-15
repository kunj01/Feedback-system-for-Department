<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Batch;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;

class AccurateTimetableSeeder extends Seeder
{
    /**
     * Run the database seeder - Exact timetables from images
     */
    public function run(): void
    {
        // Clear existing timetable entries
        Timetable::truncate();

        $this->command->info('Clearing old timetable entries...');

        // Get divisions
        $div4IT1 = Division::where('name', '4-IT-1')->first();
        $div4IT2 = Division::where('name', '4-IT-2')->first();
        $div6IT1 = Division::where('name', '6-IT-1')->first();
        $div6IT2 = Division::where('name', '6-IT-2')->first();

        if (!$div4IT1 || !$div4IT2 || !$div6IT1 || !$div6IT2) {
            $this->command->error('Divisions not found. Please run TimetableSeeder first.');
            return;
        }

        // Seed accurate timetables
        $this->seed4IT1Exact($div4IT1);
        $this->seed4IT2Exact($div4IT2);
        $this->seed6IT1Exact($div6IT1);
        $this->seed6IT2Exact($div6IT2);

        $this->command->info('✓ Accurate timetable entries created successfully!');
    }

    private function seed4IT1Exact($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        // Subjects
        $dbms = Subject::where('subject_code', 'TUC202')->first();
        $se = Subject::where('subject_code', 'CEU201')->first();
        $coa = Subject::where('subject_code', 'CEUC202')->first();
        $daa = Subject::where('subject_code', 'ITUE204')->first();
        $dsa = Subject::where('subject_code', 'C-BUE200')->first();
        $hny = Subject::where('subject_code', 'ITUE205')->first();
        $sbs = Subject::where('subject_code', 'SBS')->first();
        $pmp = Subject::where('subject_code', 'PMP')->first();

        // Faculty
        $rsk = Faculty::where('short_code', 'RSK')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $akp = Faculty::where('short_code', 'AKP')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $hnyFac = Faculty::where('short_code', 'HNY')->first();
        $mrp = Faculty::where('faculty_name', 'LIKE', '%MRP%')->first() ?? $pmpFac;

        $schedule = [
            // Monday
            ['day' => 'Monday', 'slot' => '09:10-10:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],
            ['day' => 'Monday', 'slot' => '10:10-11:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            ['day' => 'Monday', 'slot' => '11:10-12:10', 'subject' => $daa, 'faculty' => $sbs, 'room' => '609'],
            // Lab sessions - Multiple batches
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '613A', 'batch' => 'A1'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'SGP', 'batch' => 'B1'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '615A', 'batch' => 'C1'],
            
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '613A', 'batch' => 'A2'],
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'SGP', 'batch' => 'B2'],
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '615A', 'batch' => 'C2'],
            
            // Tuesday
            ['day' => 'Tuesday', 'slot' => '09:10-10:10', 'subject' => $daa, 'faculty' => $sbs, 'room' => '609'],
            ['day' => 'Tuesday', 'slot' => '10:10-11:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => '609'],
            ['day' => 'Tuesday', 'slot' => '11:10-12:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Tuesday', 'slot' => '12:10-01:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '609'],
            
            ['day' => 'Tuesday', 'slot' => '01:10-02:10', 'subject' => $dsa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Tuesday', 'slot' => '02:20-03:20', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Tuesday', 'slot' => '03:20-04:20', 'subject' => $daa, 'faculty' => $sbs, 'room' => '609'],
            
            // Wednesday
            ['day' => 'Wednesday', 'slot' => '09:10-10:10', 'subject' => $dsa, 'faculty' => $hnyFac, 'room' => '609'],
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $dsa, 'faculty' => $hnyFac, 'room' => '609'],
            ['day' => 'Wednesday', 'slot' => '02:20-03:20', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Wednesday', 'slot' => '03:20-04:20', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            
            // Thursday
            ['day' => 'Thursday', 'slot' => '09:10-10:10', 'subject' => $dsa, 'faculty' => $hnyFac, 'room' => '609'],
            ['day' => 'Thursday', 'slot' => '12:10-01:10', 'subject' => $daa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Thursday', 'slot' => '01:10-02:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Thursday', 'slot' => '02:20-03:20', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],
            
            // Friday
            ['day' => 'Friday', 'slot' => '09:10-10:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            ['day' => 'Friday', 'slot' => '10:10-11:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Friday', 'slot' => '01:10-02:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Friday', 'slot' => '02:20-03:20', 'subject' => $dsa, 'faculty' => $mrp, 'room' => '609'],
        ];

        $this->createEntries($division, $schedule, $batches);
    }

    private function seed4IT2Exact($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        $dbms = Subject::where('subject_code', 'TUC202')->first();
        $se = Subject::where('subject_code', 'CEU201')->first();
        $coa = Subject::where('subject_code', 'CEUC202')->first();
        $daa = Subject::where('subject_code', 'ITUE204')->first();
        $dsa = Subject::where('subject_code', 'C-BUE200')->first();
        $hny = Subject::where('subject_code', 'ITUE205')->first();
        $sbs = Subject::where('subject_code', 'SBS')->first();

        $rsk = Faculty::where('short_code', 'RSK')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $akp = Faculty::where('short_code', 'AKP')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $hnyFac = Faculty::where('short_code', 'HNY')->first();

        $schedule = [
            // Monday
            ['day' => 'Monday', 'slot' => '09:10-10:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Monday', 'slot' => '10:10-11:10', 'subject' => $coa, 'faculty' => $smp, 'room' => '610'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610', 'batch' => 'A2'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $sbs, 'faculty' => $smp, 'room' => 'SGP', 'batch' => 'B2'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '614B', 'batch' => 'C2'],
            
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '613A', 'batch' => 'A1'],
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $smp, 'room' => 'SGP', 'batch' => 'B1'],
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '615B', 'batch' => 'C1'],
            
            // Tuesday
            ['day' => 'Tuesday', 'slot' => '09:10-10:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],
            ['day' => 'Tuesday', 'slot' => '10:10-11:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '610'],
            ['day' => 'Tuesday', 'slot' => '02:20-03:20', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '610'],
            ['day' => 'Tuesday', 'slot' => '03:20-04:20', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],
            
            // Wednesday  
            ['day' => 'Wednesday', 'slot' => '09:10-10:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $daa, 'faculty' => $sbs, 'room' => '610'],
            
            // Thursday
            ['day' => 'Thursday', 'slot' => '09:10-10:10', 'subject' => $dsa, 'faculty' => $hnyFac, 'room' => '610'],
            ['day' => 'Thursday', 'slot' => '12:10-01:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
            ['day' => 'Thursday', 'slot' => '01:10-02:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Thursday', 'slot' => '02:20-03:20', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],
            
            // Friday
            ['day' => 'Friday', 'slot' => '09:10-10:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '610'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
            ['day' => 'Friday', 'slot' => '02:20-03:20', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
        ];

        $this->createEntries($division, $schedule, $batches);
    }

    private function seed6IT1Exact($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        $crns = Subject::where('subject_code', 'IT348')->first();
        $lp = Subject::where('subject_code', 'IT365')->first();
        $mad = Subject::where('subject_code', 'IT366')->first();
        $cc = Subject::where('subject_code', 'IT367')->first();
        $project = Subject::where('subject_code', 'IT368')->first();

        $mma = Faculty::where('short_code', 'MMA')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $rvp = Faculty::where('short_code', 'RVP')->first() ?? $sbsFac;
        $akp = Faculty::where('short_code', 'AKP')->first();

        $schedule = [
            // Monday
            ['day' => 'Monday', 'slot' => '09:10-10:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '607'],
            ['day' => 'Monday', 'slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '607'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '607'],
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '607'],
            
            // Tuesday - Combined sessions
            ['day' => 'Tuesday', 'slot' => '12:10-01:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '607'],
            ['day' => 'Tuesday', 'slot' => '01:10-02:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '608'],
            ['day' => 'Tuesday', 'slot' => '02:20-03:20', 'subject' => $project, 'faculty' => $bhp, 'room' => '607'],
            ['day' => 'Tuesday', 'slot' => '03:20-04:20', 'subject' => $crns, 'faculty' => $pmpFac, 'room' => '607'],
            
            // Tuesday - Lab sessions at 10:10-11:10
            ['day' => 'Tuesday', 'slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '613A', 'batch' => 'A1'],
            ['day' => 'Tuesday', 'slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '614A', 'batch' => 'B1'],
            ['day' => 'Tuesday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '628A', 'batch' => 'C1'],
            ['day' => 'Tuesday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '613A', 'batch' => 'D1'],
            
            // Wednesday - Multiple lab batches at 10:10-11:10
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '613A', 'batch' => 'A1'],
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '628A', 'batch' => 'B1'],
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '628A', 'batch' => 'C1'],
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '615B', 'batch' => 'D1'],
            
            ['day' => 'Wednesday', 'slot' => '12:10-01:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '607'],
            ['day' => 'Wednesday', 'slot' => '01:10-02:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '607'],
            ['day' => 'Wednesday', 'slot' => '02:20-03:20', 'subject' => $project, 'faculty' => $bhp, 'room' => '607'],
            
            // Thursday - Aptitude Session
            ['day' => 'Thursday', 'slot' => '12:10-02:10', 'subject' => $project, 'faculty' => $bhp, 'room' => 'Aptitude'],
            
            // Thursday - All batch sessions at 10:10-11:10
            ['day' => 'Thursday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '628A', 'batch' => 'A1'],
            ['day' => 'Thursday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '614A', 'batch' => 'B1'],
            ['day' => 'Thursday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '628A', 'batch' => 'C1'],
            
            // Friday - Multiple sessions
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'A1'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'B1'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'C1'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'D1'],
            
            ['day' => 'Friday', 'slot' => '01:10-02:10', 'subject' => $project, 'faculty' => $pmpFac, 'room' => '628A', 'batch' => 'A1'],
            ['day' => 'Friday', 'slot' => '01:10-02:10', 'subject' => $project, 'faculty' => $pmpFac, 'room' => '628A', 'batch' => 'B1'],
            
            ['day' => 'Friday', 'slot' => '02:20-03:20', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'A1'],
            
            // Saturday - All batches at 09:10-10:10
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '607', 'batch' => 'A1'],
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '607', 'batch' => 'B1'],
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '607', 'batch' => 'C1'],
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '607', 'batch' => 'D1'],
            
            // Saturday - Additional sessions
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '607', 'batch' => 'A1'],
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '607', 'batch' => 'B1'],
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '607', 'batch' => 'C1'],
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '607', 'batch' => 'D1'],
        ];

        $this->createEntries($division, $schedule, $batches);
    }

    private function seed6IT2Exact($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        $crns = Subject::where('subject_code', 'IT348')->first();
        $lp = Subject::where('subject_code', 'IT365')->first();
        $mad = Subject::where('subject_code', 'IT366')->first();
        $cc = Subject::where('subject_code', 'IT367')->first();
        $project = Subject::where('subject_code', 'IT368')->first();

        $mma = Faculty::where('short_code', 'MMA')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $rvp = Faculty::where('short_code', 'RVP')->first() ?? $sbsFac;

        $schedule = [
            // Monday
            ['day' => 'Monday', 'slot' => '09:10-10:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '608'],
            ['day' => 'Monday', 'slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '608'],
            ['day' => 'Monday', 'slot' => '12:10-01:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '608'],
            ['day' => 'Monday', 'slot' => '01:10-02:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '608'],
            
            // Tuesday
            ['day' => 'Tuesday', 'slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '608'],
            ['day' => 'Tuesday', 'slot' => '12:10-01:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '608'],
            ['day' => 'Tuesday', 'slot' => '01:10-02:10', 'subject' => $cc, 'faculty' => $rvp, 'room' => '608'],
            
            // Wednesday
            ['day' => 'Wednesday', 'slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '608'],
            ['day' => 'Wednesday', 'slot' => '12:10-01:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '608'],
            ['day' => 'Wednesday', 'slot' => '01:10-02:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '608'],
            
            // Thursday - Aptitude
            ['day' => 'Thursday', 'slot' => '12:10-02:10', 'subject' => $project, 'faculty' => $bhp, 'room' => 'Aptitude'],
            
            // Thursday - Lab sessions at 10:10-11:10
            ['day' => 'Thursday', 'slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '608', 'batch' => 'A2'],
            ['day' => 'Thursday', 'slot' => '10:10-11:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '608', 'batch' => 'B2'],
            
            // Friday - Multiple batch labs at 10:10-11:10
            ['day' => 'Friday', 'slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $mad, 'room' => '628A', 'batch' => 'A2'],
            ['day' => 'Friday', 'slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $crns, 'room' => 'SGP', 'batch' => 'B2'],
            ['day' => 'Friday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => 'NAS', 'batch' => 'C2'],
            ['day' => 'Friday', 'slot' => '10:10-11:10', 'subject' => $crns, 'faculty' => $rvp, 'room' => '614B', 'batch' => 'D2'],
            
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'A2'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'B2'],
            ['day' => 'Friday', 'slot' => '12:10-01:10', 'subject' => $project, 'faculty' => $bhp, 'room' => '628A', 'batch' => 'C2'],
            
            // Saturday - All batches at 09:10-10:10
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '608', 'batch' => 'A2'],
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '608', 'batch' => 'B2'],
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '608', 'batch' => 'C2'],
            ['day' => 'Saturday', 'slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '608', 'batch' => 'D2'],
            
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '608', 'batch' => 'A2'],
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '608', 'batch' => 'B2'],
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '608', 'batch' => 'C2'],
            ['day' => 'Saturday', 'slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $mma, 'room' => '608', 'batch' => 'D2'],
        ];

        $this->createEntries($division, $schedule, $batches);
    }

    private function createEntries($division, $schedule, $batches)
    {
        foreach ($schedule as $entry) {
            if (!isset($entry['subject']) || !isset($entry['faculty'])) continue;
            
            $batchId = null;
            if (isset($entry['batch'])) {
                $batchKey = $entry['batch'];
                $batchId = $batches->get($batchKey)?->id;
            }
            
            Timetable::create([
                'division_id' => $division->id,
                'day' => $entry['day'],
                'time_slot' => $entry['slot'],
                'subject_id' => $entry['subject']->id,
                'faculty_id' => $entry['faculty']->id,
                'room_no' => $entry['room'],
                'batch_id' => $batchId,
                'is_active' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Batch;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;

class TimetableEntriesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clear existing timetable entries
        Timetable::truncate();

        // Get divisions
        $div4IT1 = Division::where('name', '4-IT-1')->first();
        $div4IT2 = Division::where('name', '4-IT-2')->first();
        $div6IT1 = Division::where('name', '6-IT-1')->first();
        $div6IT2 = Division::where('name', '6-IT-2')->first();

        if (!$div4IT1 || !$div4IT2 || !$div6IT1 || !$div6IT2) {
            $this->command->error('Divisions not found. Please run TimetableSeeder first.');
            return;
        }

        // Seed timetable for 4-IT-1
        $this->seed4IT1Timetable($div4IT1);

        // Seed timetable for 4-IT-2
        $this->seed4IT2Timetable($div4IT2);

        // Seed timetable for 6-IT-1
        $this->seed6IT1Timetable($div6IT1);

        // Seed timetable for 6-IT-2
        $this->seed6IT2Timetable($div6IT2);

        $this->command->info('✓ Timetable entries created successfully!');
    }

    private function seed4IT1Timetable($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        // Get subjects for Semester 4
        $dbms = Subject::where('subject_code', 'TUC202')->where('semester', 4)->first();
        $se = Subject::where('subject_code', 'CEU201')->where('semester', 4)->first();
        $coa = Subject::where('subject_code', 'CEUC202')->where('semester', 4)->first();
        $daa = Subject::where('subject_code', 'ITUE204')->where('semester', 4)->first();
        $dsa = Subject::where('subject_code', 'C-BUE200')->where('semester', 4)->first();
        $hny = Subject::where('subject_code', 'ITUE205')->where('semester', 4)->first();
        $sbs = Subject::where('subject_code', 'SBS')->where('semester', 4)->first();
        $pmp = Subject::where('subject_code', 'PMP')->where('semester', 4)->first();

        // Get faculty
        $rsk = Faculty::where('short_code', 'RSK')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $akp = Faculty::where('short_code', 'AKP')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $hnyFac = Faculty::where('short_code', 'HNY')->first();
        $mma = Faculty::where('short_code', 'MMA')->first();

        $schedule = [
            // Monday
            ['day' => 'Monday', 'time_slot' => '09:10-10:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Monday', 'time_slot' => '10:10-11:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],
            ['day' => 'Monday', 'time_slot' => '11:10-12:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Monday', 'time_slot' => '12:10-01:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            ['day' => 'Monday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-A', 'batch' => $batches->get('A1')],
            ['day' => 'Monday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-B', 'batch' => $batches->get('B1')],
            ['day' => 'Monday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-A', 'batch' => $batches->get('A2')],
            ['day' => 'Monday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-B', 'batch' => $batches->get('B2')],

            // Tuesday
            ['day' => 'Tuesday', 'time_slot' => '09:10-10:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '609'],
            ['day' => 'Tuesday', 'time_slot' => '10:10-11:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '609'],
            ['day' => 'Tuesday', 'time_slot' => '11:10-12:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Tuesday', 'time_slot' => '12:10-01:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],
            ['day' => 'Tuesday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-C', 'batch' => $batches->get('C1')],
            ['day' => 'Tuesday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-D', 'batch' => $batches->get('D1')],
            ['day' => 'Tuesday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-C', 'batch' => $batches->get('C2')],
            ['day' => 'Tuesday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-D', 'batch' => $batches->get('D2')],

            // Wednesday
            ['day' => 'Wednesday', 'time_slot' => '09:10-10:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Wednesday', 'time_slot' => '10:10-11:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            ['day' => 'Wednesday', 'time_slot' => '11:10-12:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '609'],
            ['day' => 'Wednesday', 'time_slot' => '12:10-01:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '609'],
            ['day' => 'Wednesday', 'time_slot' => '01:10-02:10', 'subject' => $pmp, 'faculty' => $pmpFac, 'room' => '610', 'batch' => $batches->get('A1')],
            ['day' => 'Wednesday', 'time_slot' => '01:10-02:10', 'subject' => $pmp, 'faculty' => $pmpFac, 'room' => '611', 'batch' => $batches->get('B1')],

            // Thursday
            ['day' => 'Thursday', 'time_slot' => '09:10-10:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Thursday', 'time_slot' => '10:10-11:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],
            ['day' => 'Thursday', 'time_slot' => '11:10-12:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Thursday', 'time_slot' => '12:10-01:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            ['day' => 'Thursday', 'time_slot' => '01:10-02:10', 'subject' => $pmp, 'faculty' => $pmpFac, 'room' => '610', 'batch' => $batches->get('C1')],
            ['day' => 'Thursday', 'time_slot' => '01:10-02:10', 'subject' => $pmp, 'faculty' => $pmpFac, 'room' => '611', 'batch' => $batches->get('D1')],

            // Friday
            ['day' => 'Friday', 'time_slot' => '09:10-10:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '609'],
            ['day' => 'Friday', 'time_slot' => '10:10-11:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '609'],
            ['day' => 'Friday', 'time_slot' => '11:10-12:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '609'],
            ['day' => 'Friday', 'time_slot' => '12:10-01:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '609'],

            // Saturday
            ['day' => 'Saturday', 'time_slot' => '09:10-10:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '609'],
            ['day' => 'Saturday', 'time_slot' => '10:10-11:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '609'],
            ['day' => 'Saturday', 'time_slot' => '11:10-12:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '609'],
            ['day' => 'Saturday', 'time_slot' => '12:10-01:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '609'],
        ];

        foreach ($schedule as $entry) {
            if ($entry['subject'] && $entry['faculty']) {
                Timetable::create([
                    'division_id' => $division->id,
                    'day' => $entry['day'],
                    'time_slot' => $entry['time_slot'],
                    'subject_id' => $entry['subject']->id,
                    'faculty_id' => $entry['faculty']->id,
                    'room_no' => $entry['room'],
                    'batch_id' => $entry['batch']->id ?? null,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seed4IT2Timetable($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        // Get subjects for Semester 4
        $dbms = Subject::where('subject_code', 'TUC202')->where('semester', 4)->first();
        $se = Subject::where('subject_code', 'CEU201')->where('semester', 4)->first();
        $coa = Subject::where('subject_code', 'CEUC202')->where('semester', 4)->first();
        $daa = Subject::where('subject_code', 'ITUE204')->where('semester', 4)->first();
        $dsa = Subject::where('subject_code', 'C-BUE200')->where('semester', 4)->first();
        $hny = Subject::where('subject_code', 'ITUE205')->where('semester', 4)->first();
        $sbs = Subject::where('subject_code', 'SBS')->where('semester', 4)->first();

        // Get faculty (using different faculty for variety)
        $rsk = Faculty::where('short_code', 'RSK')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $akp = Faculty::where('short_code', 'AKP')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $hnyFac = Faculty::where('short_code', 'HNY')->first();

        $schedule = [
            // Monday
            ['day' => 'Monday', 'time_slot' => '09:10-10:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Monday', 'time_slot' => '10:10-11:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],
            ['day' => 'Monday', 'time_slot' => '11:10-12:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '610'],
            ['day' => 'Monday', 'time_slot' => '12:10-01:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
            ['day' => 'Monday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('A1')],
            ['day' => 'Monday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('A2')],

            // Tuesday
            ['day' => 'Tuesday', 'time_slot' => '09:10-10:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '610'],
            ['day' => 'Tuesday', 'time_slot' => '10:10-11:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '610'],
            ['day' => 'Tuesday', 'time_slot' => '11:10-12:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Tuesday', 'time_slot' => '12:10-01:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],
            ['day' => 'Tuesday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('B1')],
            ['day' => 'Tuesday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('B2')],

            // Wednesday
            ['day' => 'Wednesday', 'time_slot' => '09:10-10:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '610'],
            ['day' => 'Wednesday', 'time_slot' => '10:10-11:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
            ['day' => 'Wednesday', 'time_slot' => '11:10-12:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '610'],
            ['day' => 'Wednesday', 'time_slot' => '12:10-01:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '610'],

            // Thursday
            ['day' => 'Thursday', 'time_slot' => '09:10-10:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Thursday', 'time_slot' => '10:10-11:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],
            ['day' => 'Thursday', 'time_slot' => '11:10-12:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '610'],
            ['day' => 'Thursday', 'time_slot' => '12:10-01:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
            ['day' => 'Thursday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('C1')],
            ['day' => 'Thursday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('C2')],

            // Friday
            ['day' => 'Friday', 'time_slot' => '09:10-10:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '610'],
            ['day' => 'Friday', 'time_slot' => '10:10-11:10', 'subject' => $dsa, 'faculty' => $smp, 'room' => '610'],
            ['day' => 'Friday', 'time_slot' => '11:10-12:10', 'subject' => $se, 'faculty' => $bhp, 'room' => '610'],
            ['day' => 'Friday', 'time_slot' => '12:10-01:10', 'subject' => $dbms, 'faculty' => $rsk, 'room' => '610'],

            // Saturday
            ['day' => 'Saturday', 'time_slot' => '09:10-10:10', 'subject' => $daa, 'faculty' => $pmpFac, 'room' => '610'],
            ['day' => 'Saturday', 'time_slot' => '10:10-11:10', 'subject' => $coa, 'faculty' => $akp, 'room' => '610'],
            ['day' => 'Saturday', 'time_slot' => '11:10-12:10', 'subject' => $hny, 'faculty' => $hnyFac, 'room' => '610'],
            ['day' => 'Saturday', 'time_slot' => '01:10-02:10', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('D1')],
            ['day' => 'Saturday', 'time_slot' => '02:20-03:20', 'subject' => $sbs, 'faculty' => $sbsFac, 'room' => 'Lab-E', 'batch' => $batches->get('D2')],
        ];

        foreach ($schedule as $entry) {
            if ($entry['subject'] && $entry['faculty']) {
                Timetable::create([
                    'division_id' => $division->id,
                    'day' => $entry['day'],
                    'time_slot' => $entry['time_slot'],
                    'subject_id' => $entry['subject']->id,
                    'faculty_id' => $entry['faculty']->id,
                    'room_no' => $entry['room'],
                    'batch_id' => $entry['batch']->id ?? null,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seed6IT1Timetable($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        // Get subjects for Semester 6
        $crns = Subject::where('subject_code', 'IT348')->where('semester', 6)->first();
        $lp = Subject::where('subject_code', 'IT365')->where('semester', 6)->first();
        $mad = Subject::where('subject_code', 'IT366')->where('semester', 6)->first();
        $cc = Subject::where('subject_code', 'IT367')->where('semester', 6)->first();
        $project = Subject::where('subject_code', 'IT368')->where('semester', 6)->first();
        $crnsLab = Subject::where('subject_code', 'CRNS')->where('semester', 6)->first();
        $lpLab = Subject::where('subject_code', 'LP')->where('semester', 6)->first();
        $ccLab = Subject::where('subject_code', 'CC')->where('semester', 6)->first();

        // Get faculty
        $mma = Faculty::where('short_code', 'MMA')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();
        $hnyFac = Faculty::where('short_code', 'HNY')->first();

        $schedule = [
            // Monday
            ['day' => 'Monday', 'time_slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '701'],
            ['day' => 'Monday', 'time_slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '701'],
            ['day' => 'Monday', 'time_slot' => '11:10-12:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '701'],
            ['day' => 'Monday', 'time_slot' => '12:10-01:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '701'],
            ['day' => 'Monday', 'time_slot' => '01:10-02:10', 'subject' => $crnsLab, 'faculty' => $mma, 'room' => 'Lab-A', 'batch' => $batches->get('A1')],
            ['day' => 'Monday', 'time_slot' => '02:20-03:20', 'subject' => $crnsLab, 'faculty' => $mma, 'room' => 'Lab-A', 'batch' => $batches->get('A2')],

            // Tuesday
            ['day' => 'Tuesday', 'time_slot' => '09:10-10:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '701'],
            ['day' => 'Tuesday', 'time_slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '701'],
            ['day' => 'Tuesday', 'time_slot' => '11:10-12:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '701'],
            ['day' => 'Tuesday', 'time_slot' => '12:10-01:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '701'],
            ['day' => 'Tuesday', 'time_slot' => '01:10-02:10', 'subject' => $lpLab, 'faculty' => $pmpFac, 'room' => 'Lab-B', 'batch' => $batches->get('A1')],
            ['day' => 'Tuesday', 'time_slot' => '02:20-03:20', 'subject' => $lpLab, 'faculty' => $pmpFac, 'room' => 'Lab-B', 'batch' => $batches->get('A2')],

            // Wednesday
            ['day' => 'Wednesday', 'time_slot' => '09:10-10:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '701'],
            ['day' => 'Wednesday', 'time_slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '701'],
            ['day' => 'Wednesday', 'time_slot' => '11:10-12:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '701'],
            ['day' => 'Wednesday', 'time_slot' => '12:10-01:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '701'],
            ['day' => 'Wednesday', 'time_slot' => '01:10-02:10', 'subject' => $ccLab, 'faculty' => $sbsFac, 'room' => 'Lab-C', 'batch' => $batches->get('A1')],
            ['day' => 'Wednesday', 'time_slot' => '02:20-03:20', 'subject' => $ccLab, 'faculty' => $sbsFac, 'room' => 'Lab-C', 'batch' => $batches->get('A2')],

            // Thursday
            ['day' => 'Thursday', 'time_slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '701'],
            ['day' => 'Thursday', 'time_slot' => '10:10-11:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '701'],
            ['day' => 'Thursday', 'time_slot' => '11:10-12:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '701'],
            ['day' => 'Thursday', 'time_slot' => '12:10-01:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '701'],
            ['day' => 'Thursday', 'time_slot' => '01:10-02:10', 'subject' => $project, 'faculty' => $bhp, 'room' => 'Lab-D', 'batch' => $batches->get('A1')],
            ['day' => 'Thursday', 'time_slot' => '02:20-03:20', 'subject' => $project, 'faculty' => $bhp, 'room' => 'Lab-D', 'batch' => $batches->get('A2')],

            // Friday
            ['day' => 'Friday', 'time_slot' => '09:10-10:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '701'],
            ['day' => 'Friday', 'time_slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '701'],
            ['day' => 'Friday', 'time_slot' => '11:10-12:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '701'],
            ['day' => 'Friday', 'time_slot' => '12:10-01:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '701'],

            // Saturday
            ['day' => 'Saturday', 'time_slot' => '09:10-10:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '701'],
            ['day' => 'Saturday', 'time_slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '701'],
            ['day' => 'Saturday', 'time_slot' => '11:10-12:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '701'],
            ['day' => 'Saturday', 'time_slot' => '12:10-01:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '701'],
        ];

        foreach ($schedule as $entry) {
            if ($entry['subject'] && $entry['faculty']) {
                Timetable::create([
                    'division_id' => $division->id,
                    'day' => $entry['day'],
                    'time_slot' => $entry['time_slot'],
                    'subject_id' => $entry['subject']->id,
                    'faculty_id' => $entry['faculty']->id,
                    'room_no' => $entry['room'],
                    'batch_id' => $entry['batch']->id ?? null,
                    'is_active' => true,
                ]);
            }
        }
    }

    private function seed6IT2Timetable($division)
    {
        $batches = Batch::where('division_id', $division->id)->get()->keyBy('batch_name');
        
        // Get subjects for Semester 6
        $crns = Subject::where('subject_code', 'IT348')->where('semester', 6)->first();
        $lp = Subject::where('subject_code', 'IT365')->where('semester', 6)->first();
        $mad = Subject::where('subject_code', 'IT366')->where('semester', 6)->first();
        $cc = Subject::where('subject_code', 'IT367')->where('semester', 6)->first();
        $project = Subject::where('subject_code', 'IT368')->where('semester', 6)->first();
        $crnsLab = Subject::where('subject_code', 'CRNS')->where('semester', 6)->first();
        $lpLab = Subject::where('subject_code', 'LP')->where('semester', 6)->first();
        $ccLab = Subject::where('subject_code', 'CC')->where('semester', 6)->first();

        // Get faculty
        $mma = Faculty::where('short_code', 'MMA')->first();
        $pmpFac = Faculty::where('short_code', 'PMP')->first();
        $smp = Faculty::where('short_code', 'SMP')->first();
        $sbsFac = Faculty::where('short_code', 'SBS')->first();
        $bhp = Faculty::where('short_code', 'BHP')->first();

        $schedule = [
            // Monday
            ['day' => 'Monday', 'time_slot' => '09:10-10:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '702'],
            ['day' => 'Monday', 'time_slot' => '10:10-11:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '702'],
            ['day' => 'Monday', 'time_slot' => '11:10-12:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '702'],
            ['day' => 'Monday', 'time_slot' => '12:10-01:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '702'],
            ['day' => 'Monday', 'time_slot' => '01:10-02:10', 'subject' => $crnsLab, 'faculty' => $mma, 'room' => 'Lab-E', 'batch' => $batches->get('B1')],
            ['day' => 'Monday', 'time_slot' => '02:20-03:20', 'subject' => $crnsLab, 'faculty' => $mma, 'room' => 'Lab-E', 'batch' => $batches->get('B2')],

            // Tuesday
            ['day' => 'Tuesday', 'time_slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '702'],
            ['day' => 'Tuesday', 'time_slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '702'],
            ['day' => 'Tuesday', 'time_slot' => '11:10-12:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '702'],
            ['day' => 'Tuesday', 'time_slot' => '12:10-01:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '702'],
            ['day' => 'Tuesday', 'time_slot' => '01:10-02:10', 'subject' => $lpLab, 'faculty' => $pmpFac, 'room' => 'Lab-F', 'batch' => $batches->get('B1')],
            ['day' => 'Tuesday', 'time_slot' => '02:20-03:20', 'subject' => $lpLab, 'faculty' => $pmpFac, 'room' => 'Lab-F', 'batch' => $batches->get('B2')],

            // Wednesday
            ['day' => 'Wednesday', 'time_slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '702'],
            ['day' => 'Wednesday', 'time_slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '702'],
            ['day' => 'Wednesday', 'time_slot' => '11:10-12:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '702'],
            ['day' => 'Wednesday', 'time_slot' => '12:10-01:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '702'],
            ['day' => 'Wednesday', 'time_slot' => '01:10-02:10', 'subject' => $ccLab, 'faculty' => $sbsFac, 'room' => 'Lab-G', 'batch' => $batches->get('B1')],
            ['day' => 'Wednesday', 'time_slot' => '02:20-03:20', 'subject' => $ccLab, 'faculty' => $sbsFac, 'room' => 'Lab-G', 'batch' => $batches->get('B2')],

            // Thursday
            ['day' => 'Thursday', 'time_slot' => '09:10-10:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '702'],
            ['day' => 'Thursday', 'time_slot' => '10:10-11:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '702'],
            ['day' => 'Thursday', 'time_slot' => '11:10-12:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '702'],
            ['day' => 'Thursday', 'time_slot' => '12:10-01:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '702'],
            ['day' => 'Thursday', 'time_slot' => '01:10-02:10', 'subject' => $project, 'faculty' => $bhp, 'room' => 'Lab-H', 'batch' => $batches->get('B1')],
            ['day' => 'Thursday', 'time_slot' => '02:20-03:20', 'subject' => $project, 'faculty' => $bhp, 'room' => 'Lab-H', 'batch' => $batches->get('B2')],

            // Friday
            ['day' => 'Friday', 'time_slot' => '09:10-10:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '702'],
            ['day' => 'Friday', 'time_slot' => '10:10-11:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '702'],
            ['day' => 'Friday', 'time_slot' => '11:10-12:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '702'],
            ['day' => 'Friday', 'time_slot' => '12:10-01:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '702'],

            // Saturday
            ['day' => 'Saturday', 'time_slot' => '09:10-10:10', 'subject' => $crns, 'faculty' => $mma, 'room' => '702'],
            ['day' => 'Saturday', 'time_slot' => '10:10-11:10', 'subject' => $mad, 'faculty' => $smp, 'room' => '702'],
            ['day' => 'Saturday', 'time_slot' => '11:10-12:10', 'subject' => $cc, 'faculty' => $sbsFac, 'room' => '702'],
            ['day' => 'Saturday', 'time_slot' => '12:10-01:10', 'subject' => $lp, 'faculty' => $pmpFac, 'room' => '702'],
        ];

        foreach ($schedule as $entry) {
            if ($entry['subject'] && $entry['faculty']) {
                Timetable::create([
                    'division_id' => $division->id,
                    'day' => $entry['day'],
                    'time_slot' => $entry['time_slot'],
                    'subject_id' => $entry['subject']->id,
                    'faculty_id' => $entry['faculty']->id,
                    'room_no' => $entry['room'],
                    'batch_id' => $entry['batch']->id ?? null,
                    'is_active' => true,
                ]);
            }
        }
    }
}

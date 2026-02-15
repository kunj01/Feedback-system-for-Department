<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Batch;
use App\Models\Subject;
use App\Models\Faculty;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    /**
     * Display timetable page
     */
    public function index(Request $request)
    {
        $semesters = [4, 6];
        $branches = ['IT'];
        $divisions = Division::active()->orderBy('name')->get();

        $selectedDivisionId = $request->get('division_id');
        $timetableData = [];

        if ($selectedDivisionId) {
            $division = Division::find($selectedDivisionId);
            
            if ($division) {
                $timetableData = $this->getTimetableData($division->id);
            }
        }

        return view('admin.timetable.index', compact(
            'semesters',
            'branches',
            'divisions',
            'selectedDivisionId',
            'timetableData'
        ));
    }

    /**
     * Get timetable data for a division
     */
    private function getTimetableData($divisionId)
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $timeSlots = [
            '09:10-10:10',
            '10:10-11:10',
            '11:10-12:10',
            '12:10-01:10',
            '01:10-02:10',
            '02:20-03:20',
            '03:20-04:20',
        ];

        $timetable = [];
        $entries = Timetable::with(['subject', 'faculty', 'batch'])
            ->where('division_id', $divisionId)
            ->where('is_active', true)
            ->get();

        foreach ($timeSlots as $timeSlot) {
            $timetable[$timeSlot] = [];
            
            foreach ($days as $day) {
                $dayEntries = $entries->where('day', $day)
                    ->where('time_slot', $timeSlot)
                    ->values();
                
                $timetable[$timeSlot][$day] = $dayEntries;
            }
        }

        return [
            'days' => $days,
            'timeSlots' => $timeSlots,
            'timetable' => $timetable,
        ];
    }

    /**
     * Store timetable entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'time_slot' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'required|exists:faculties,id',
            'room_no' => 'required|string|max:20',
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        $timetable = Timetable::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Timetable entry created successfully',
            'data' => $timetable->load(['subject', 'faculty', 'batch']),
        ]);
    }

    /**
     * Update timetable entry
     */
    public function update(Request $request, Timetable $timetable)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'required|exists:faculties,id',
            'room_no' => 'required|string|max:20',
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        $timetable->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Timetable entry updated successfully',
            'data' => $timetable->load(['subject', 'faculty', 'batch']),
        ]);
    }

    /**
     * Delete timetable entry
     */
    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return response()->json([
            'success' => true,
            'message' => 'Timetable entry deleted successfully',
        ]);
    }

    /**
     * Get subjects for dropdown
     */
    public function getSubjects(Request $request)
    {
        $semester = $request->get('semester');
        $branch = $request->get('branch');

        $subjects = Subject::active()
            ->where('semester', $semester)
            ->where('branch', $branch)
            ->orderBy('subject_name')
            ->get(['id', 'subject_code', 'subject_name', 'subject_type']);

        return response()->json($subjects);
    }

    /**
     * Get faculties for dropdown
     */
    public function getFaculties()
    {
        $faculties = Faculty::active()
            ->orderBy('faculty_name')
            ->get(['id', 'faculty_name', 'short_code']);

        return response()->json($faculties);
    }

    /**
     * Get batches for a division
     */
    public function getBatches($divisionId)
    {
        $batches = Batch::where('division_id', $divisionId)
            ->where('is_active', true)
            ->orderBy('batch_name')
            ->get(['id', 'batch_name']);

        return response()->json($batches);
    }

    /**
     * Auto-generate feedback allocations from timetable
     */
    public function generateFeedbackAllocations(Request $request)
    {
        $divisionId = $request->get('division_id');
        
        if (!$divisionId) {
            return response()->json([
                'success' => false,
                'message' => 'Division is required',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $timetableEntries = Timetable::with(['subject', 'faculty', 'batch', 'division'])
                ->where('division_id', $divisionId)
                ->where('is_active', true)
                ->get();

            // Group by subject, faculty, and batch
            $grouped = $timetableEntries->groupBy(function ($item) {
                return $item->subject_id . '-' . $item->faculty_id . '-' . ($item->batch_id ?? 'all');
            });

            $created = 0;

            foreach ($grouped as $key => $entries) {
                $entry = $entries->first();
                
                // Check if feedback assignment already exists
                $exists = DB::table('form_assignments')
                    ->where('subject_id', $entry->subject_id)
                    ->where('teacher_id', $entry->faculty_id)
                    ->where('division_id', $entry->division_id)
                    ->where('batch_id', $entry->batch_id)
                    ->exists();

                if (!$exists) {
                    // Create feedback assignment
                    DB::table('form_assignments')->insert([
                        'subject_id' => $entry->subject_id,
                        'teacher_id' => $entry->faculty_id,
                        'division_id' => $entry->division_id,
                        'batch_id' => $entry->batch_id,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $created++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully created {$created} feedback allocations",
                'created' => $created,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error generating feedback allocations: ' . $e->getMessage(),
            ], 500);
        }
    }
}

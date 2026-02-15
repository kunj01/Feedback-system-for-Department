<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Batch;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentUploadController extends Controller
{
    /**
     * Display student upload page
     */
    public function index()
    {
        $divisions = Division::active()->orderBy('name')->get();
        $recentUploads = DB::table('students')
            ->select('created_at', DB::raw('count(*) as count'))
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.students.upload', compact('divisions', 'recentUploads'));
    }

    /**
     * Process CSV upload
     */
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            
            // Get headers
            $headers = array_shift($csvData);
            
            // Validate headers
            $requiredHeaders = ['enrollment_no', 'name', 'semester', 'branch', 'division', 'batch'];
            $missingHeaders = array_diff($requiredHeaders, $headers);
            
            if (!empty($missingHeaders)) {
                return back()->with('error', 'Missing required columns: ' . implode(', ', $missingHeaders));
            }

            DB::beginTransaction();

            $created = 0;
            $updated = 0;
            $errors = [];

            foreach ($csvData as $index => $row) {
                if (count($row) !== count($headers)) {
                    continue; // Skip malformed rows
                }

                $data = array_combine($headers, $row);
                $lineNumber = $index + 2; // +2 because array is 0-indexed and we removed header

                try {
                    $result = $this->processStudentRow($data);
                    
                    if ($result['status'] === 'created') {
                        $created++;
                    } elseif ($result['status'] === 'updated') {
                        $updated++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Line {$lineNumber}: {$e->getMessage()}";
                }
            }

            DB::commit();

            $message = "Successfully processed {$created} new students and updated {$updated} existing students.";
            
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred.";
            }

            return back()->with('success', $message)->with('errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing CSV: ' . $e->getMessage());
        }
    }

    /**
     * Process a single student row
     */
    private function processStudentRow(array $data)
    {
        // Validate required fields
        $validator = Validator::make($data, [
            'enrollment_no' => 'required|string',
            'name' => 'required|string',
            'semester' => 'required|integer',
            'branch' => 'required|string',
            'division' => 'required|integer',
            'batch' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validation failed: ' . $validator->errors()->first());
        }

        // Get or create division
        $divisionName = "{$data['semester']}-{$data['branch']}-{$data['division']}";
        $division = Division::firstOrCreate(
            [
                'semester' => $data['semester'],
                'branch' => $data['branch'],
                'division_number' => $data['division'],
            ],
            [
                'name' => $divisionName,
                'is_active' => true,
            ]
        );

        // Get or create batch
        $batch = Batch::firstOrCreate(
            [
                'division_id' => $division->id,
                'batch_name' => strtoupper(trim($data['batch'])),
            ],
            [
                'description' => "Batch {$data['batch']} for {$divisionName}",
                'is_active' => true,
            ]
        );

        // Parse name
        $nameParts = explode(' ', trim($data['name']), 3);
        $firstName = $nameParts[0] ?? '';
        $middleName = $nameParts[1] ?? null;
        $lastName = $nameParts[2] ?? ($nameParts[1] ?? null);

        // Check if student exists
        $student = Student::where('enrollment_no', $data['enrollment_no'])->first();

        $studentData = [
            'enrollment_no' => $data['enrollment_no'],
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'division_id' => $division->id,
            'batch_id' => $batch->id,
            'semester' => $data['semester'],
            'branch' => $data['branch'],
            'email' => $data['email'] ?? null,
            'contact' => $data['contact'] ?? null,
        ];

        if ($student) {
            $student->update($studentData);
            return ['status' => 'updated', 'student' => $student];
        } else {
            $student = Student::create($studentData);
            return ['status' => 'created', 'student' => $student];
        }
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_upload_template.csv"',
        ];

        $columns = ['enrollment_no', 'name', 'semester', 'branch', 'division', 'batch', 'email', 'contact'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample data
            fputcsv($file, ['22IT001', 'John Doe', '4', 'IT', '2', 'A1', 'john@example.com', '9876543210']);
            fputcsv($file, ['22IT002', 'Jane Smith', '4', 'IT', '2', 'A1', 'jane@example.com', '9876543211']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete students
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        try {
            $count = Student::whereIn('id', $request->student_ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$count} students",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting students: ' . $e->getMessage(),
            ], 500);
        }
    }
}

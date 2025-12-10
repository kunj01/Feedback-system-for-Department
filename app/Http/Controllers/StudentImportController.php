<?php

namespace App\Http\Controllers;

use App\Services\StudentImportService;
use App\Models\BulkImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentImportController extends Controller
{
    protected $importService;

    public function __construct(StudentImportService $importService)
    {
        $this->importService = $importService;
        $this->middleware('auth');
    }

    /**
     * Show import wizard page
     */
    public function index()
    {
        // Only Admin and TnP can import students
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('students.import.index');
    }

    /**
     * Download Excel template matching CSV structure
     */
    public function downloadTemplate()
    {
        // Only Admin and TnP can download template
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers matching the Placement Detail CSV
        $headers = [
            'Sr No',
            'ID NO',
            'First Name',
            'Middle Name',
            'Last Name',
            'Full Name',
            'Gender',
            'City',
            '10% or CGPA',
            '12% or CGPA',
            'Diploma % or CGPA',
            'B.Tech CGPA (Upto 5th Sem)',
            'Mobile No',
            'Personal Email ID',
            'CHARUSAT Email Id',
            'admission',
            'Eligible Students',
            'Counsellor Name',
            'Placed By CHARUSAT?',
            'Company Name',
            'Placed',
            'Offer Letter',
            "Package\n(in LPA Only)",
            'Stipend',
            'Remarks',
        ];

        $sheet->fromArray([$headers], null, 'A1');

        // Add sample data rows
        $sampleData = [
            [
                '1',
                '20IT004',
                'SAGAR',
                'JAYESHBHAI',
                'BHANUSHALI',
                'BHANUSHALI SAGAR JAYESHBHAI',
                'Male',
                'Changa',
                '87.83',
                '72.92',
                'NA',
                '9.075',
                '8866157124',
                'sagarbhanushali@gmail.com',
                '20it004@charusat.edu.in',
                'SQ',
                'YES',
                'Dhaval Patel',
                'YES',
                'ThinkBiz',
                '1',
                '1',
                '5',
                '10000',
                'Backend Developer',
            ],
            [
                '2',
                '20IT005',
                'BHAKTI',
                'SHAILESHBHAI',
                'BHANVADIYA',
                'BHANVADIYA BHAKTI SHAILESHBHAI',
                'Female',
                'Gomta',
                '90.28',
                '81.33',
                'NA',
                '9.09',
                '9313533452',
                'bhaktipatel@gmail.com',
                '20it005@charusat.edu.in',
                'SQ',
                'YES',
                'Mikin Patel',
                'YES',
                'ThinkBiz',
                '1',
                '1',
                '5',
                '10000',
                '',
            ],
        ];

        $sheet->fromArray($sampleData, null, 'A2');

        // Style header row
        $sheet->getStyle('A1:Y1')->getFont()->setBold(true);
        $sheet->getStyle('A1:Y1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD3D3D3');

        // Auto-size columns
        foreach (range('A', 'Y') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add notes
        $sheet->setCellValue('A5', 'Notes:');
        $sheet->setCellValue('A6', '- Required fields: ID NO, First Name, Last Name, Full Name, CHARUSAT Email Id');
        $sheet->setCellValue('A7', '- ID NO format: Extract department from ID (e.g., 20IT004 → IT Department, Batch 2020)');
        $sheet->setCellValue('A8', '- Gender: Male/Female');
        $sheet->setCellValue('A9', '- Use "NA" for blank/missing values');
        $sheet->setCellValue('A10', '- Eligible Students: YES/NO');
        $sheet->setCellValue('A11', '- Placed By CHARUSAT?: YES/NO');
        $sheet->setCellValue('A12', '- Placed: 1 = Yes, 0 or blank = No');
        $sheet->setCellValue('A13', '- Package in LPA (e.g., 5 for 5 LPA)');

        $writer = new Xlsx($spreadsheet);

        $fileName = 'placement_import_template_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'template');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Perform dry-run validation
     */
    public function dryRun(Request $request)
    {
        // Only Admin and TnP can import students
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('file');
            $filePath = $file->getRealPath();

            // Parse Excel
            $rows = $this->importService->parseExcelFile($filePath);

            // Validate rows
            $validationResults = $this->importService->validateRows($rows);

            // Generate summary
            $summary = $this->importService->generateImportSummary($validationResults);

            // Get filename for import log
            $filename = $file->getClientOriginalName();

            return view('students.import.preview', compact('validationResults', 'summary', 'filename'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    /**
     * Execute import
     */
    public function import(Request $request)
    {
        // Only Admin and TnP can import students
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file_data' => 'required|string',
            'strategy' => 'required|in:overwrite,merge,skip',
        ]);

        try {
            $strategy = $request->input('strategy', 'merge');

            // Retrieve validation results from serialized data
            $importData = unserialize(base64_decode($request->input('file_data')));

            if (!$importData || !is_array($importData) || !isset($importData['results']) || !isset($importData['filename'])) {
                return back()->with('error', 'Invalid import data. Please re-upload the file.');
            }

            $validationResults = $importData['results'];
            $filename = $importData['filename'];

            // Import only valid and warning rows
            $rowsToImport = array_merge(
                $validationResults['valid'],
                $validationResults['warnings']
            );

            $results = $this->importService->importRows($rowsToImport, $strategy, auth()->id());

            // Calculate total rows from validation results
            $totalRows = count($validationResults['valid']) + count($validationResults['warnings']) + count($validationResults['errors']);

            // Create import log
            $importLog = BulkImportLog::create([
                'import_type' => 'STUDENTS',
                'uploaded_by' => auth()->id(),
                'filename' => $filename,
                'total_rows' => $totalRows,
                'created_count' => $results['created'],
                'updated_count' => $results['updated'],
                'skipped_count' => $results['skipped'],
                'errors' => $results['errors'],
                'status' => 'COMPLETED',
                'summary' => json_encode($results),
            ]);

            // TODO: Send email notifications to created users

            return redirect()->route('students.import.show', $importLog->id)
                ->with('success', "Import completed! Created: {$results['created']}, Updated: {$results['updated']}, Skipped: {$results['skipped']}");

        } catch (\Exception $e) {
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Show import logs list
     */
    public function logs()
    {
        // Only Admin and TnP can view import logs
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        $logs = BulkImportLog::where('import_type', 'STUDENTS')
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('students.import.logs', compact('logs'));
    }

    /**
     * Show import log details
     */
    public function show($id)
    {
        // Only Admin and TnP can view import details
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        $log = BulkImportLog::with('uploader')->findOrFail($id);

        // Get students imported in this batch (created/updated within 1 minute of import)
        $students = \App\Models\Student::with(['department', 'placements.company'])
            ->where('created_at', '>=', $log->created_at->subMinute())
            ->where('created_at', '<=', $log->created_at->addMinute())
            ->orWhere(function($query) use ($log) {
                $query->where('updated_at', '>=', $log->created_at->subMinute())
                      ->where('updated_at', '<=', $log->created_at->addMinute());
            })
            ->get();

        return view('students.import.show', compact('log', 'students'));
    }

    /**
     * Download import report
     */
    public function downloadReport($id)
    {
        // Only Admin and TnP can download reports
        if (!auth()->user()->hasAnyRole(['Admin', 'TnP'])) {
            abort(403, 'Unauthorized action.');
        }

        $log = BulkImportLog::findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'Import Report');
        $sheet->setCellValue('A2', 'Filename: ' . $log->filename);
        $sheet->setCellValue('A3', 'Uploaded By: ' . $log->uploader->name);
        $sheet->setCellValue('A4', 'Date: ' . $log->created_at->format('Y-m-d H:i:s'));
        $sheet->setCellValue('A5', '');
        $sheet->setCellValue('A6', 'Summary:');
        $sheet->setCellValue('A7', 'Total Rows: ' . $log->total_rows);
        $sheet->setCellValue('A8', 'Created: ' . $log->created_count);
        $sheet->setCellValue('A9', 'Updated: ' . $log->updated_count);
        $sheet->setCellValue('A10', 'Skipped: ' . $log->skipped_count);

        // Errors section
        if (!empty($log->errors)) {
            $sheet->setCellValue('A12', 'Errors:');
            $sheet->fromArray([['Row', 'Error']], null, 'A13');

            $row = 14;
            foreach ($log->errors as $error) {
                $sheet->setCellValue('A' . $row, $error['row']);
                $sheet->setCellValue('B' . $row, implode(', ', $error['errors']));
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'import_report_' . $log->id . '_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'report');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}


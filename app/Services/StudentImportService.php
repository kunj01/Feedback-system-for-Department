<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use App\Models\Department;
use App\Models\Company;
use App\Models\StudentPlacement;
use App\Models\BulkImportLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;

class StudentImportService
{
    /**
     * Parse Excel/CSV file and return array of rows
     */
    public function parseExcelFile($filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];

        $headerRow = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1', null, true, false)[0];
        $headers = array_map('trim', $headerRow);

        $highestRow = $worksheet->getHighestRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = $worksheet->rangeToArray('A' . $row . ':' . $worksheet->getHighestColumn() . $row, null, true, false)[0];

            // Skip empty rows
            if (empty(array_filter($rowData))) {
                continue;
            }

            $data = array_combine($headers, $rowData);
            $rows[] = $data;
        }

        return $rows;
    }

    /**
     * Validate rows and return validation results
     */
    public function validateRows(array $rows): array
    {
        $results = [
            'valid' => [],
            'warnings' => [],
            'errors' => [],
        ];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because row 1 is header and array is 0-indexed
            $validation = $this->validateRow($row, $rowNumber);

            if ($validation['status'] === 'error') {
                $results['errors'][] = $validation;
            } elseif ($validation['status'] === 'warning') {
                $results['warnings'][] = $validation;
            } else {
                $results['valid'][] = $validation;
            }
        }

        return $results;
    }

    /**
     * Validate individual row
     */
    protected function validateRow(array $row, int $rowNumber): array
    {
        $errors = [];
        $warnings = [];

        // Required fields from CSV: ID NO, First Name, Last Name, Full Name, Gender, CHARUSAT Email Id
        $idNo = trim($row['ID NO'] ?? '');
        $firstName = trim($row['First Name'] ?? '');
        $lastName = trim($row['Last Name'] ?? '');
        $fullName = trim($row['Full Name'] ?? '');
        $charusatEmail = trim($row['CHARUSAT Email Id'] ?? '');

        if (empty($idNo)) {
            $errors[] = "Missing required field: ID NO";
        }

        if (empty($firstName)) {
            $errors[] = "Missing required field: First Name";
        }

        if (empty($lastName)) {
            $errors[] = "Missing required field: Last Name";
        }

        if (empty($fullName)) {
            $errors[] = "Missing required field: Full Name";
        }

        if (empty($charusatEmail)) {
            $errors[] = "Missing required field: CHARUSAT Email Id";
        } elseif (!filter_var($charusatEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid CHARUSAT email format";
        }

        // Validate personal email if provided
        $personalEmail = trim($row['Personal Email ID'] ?? '');
        if (!empty($personalEmail) && !filter_var($personalEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid personal email format";
        }

        // Extract department code from ID NO (e.g., 20IT004 -> IT)
        if (!empty($idNo)) {
            preg_match('/\d+([A-Z]+)\d+/i', $idNo, $matches);
            $deptCode = $matches[1] ?? null;

            if ($deptCode) {
                $department = Department::where('code', strtoupper($deptCode))->first();
                if (!$department) {
                    $errors[] = "Department code '{$deptCode}' extracted from ID NO not found in system";
                }
            } else {
                $warnings[] = "Could not extract department code from ID NO";
            }
        }

        // Check if student already exists
        if (!empty($idNo)) {
            $existing = Student::where('student_id', strtoupper($idNo))->first();
            if ($existing) {
                $warnings[] = "Student ID already exists - will be updated based on merge strategy";
            }
        }

        // Validate percentages and CGPA
        $ssc = $row['10% or CGPA'] ?? '';
        $hsc = $row['12% or CGPA'] ?? '';
        $diploma = $row['Diploma % or CGPA'] ?? '';
        $btechCgpa = $row['B.Tech CGPA (Upto 5th Sem)'] ?? '';

        if (!empty($ssc) && !in_array(strtoupper($ssc), ['NA', '']) && !is_numeric($ssc)) {
            $warnings[] = "10% or CGPA is not numeric: {$ssc}";
        }

        if (!empty($hsc) && !in_array(strtoupper($hsc), ['NA', '']) && !is_numeric($hsc)) {
            $warnings[] = "12% or CGPA is not numeric: {$hsc}";
        }

        if (!empty($btechCgpa) && !in_array(strtoupper($btechCgpa), ['NA', '']) && !is_numeric($btechCgpa)) {
            $warnings[] = "B.Tech CGPA is not numeric: {$btechCgpa}";
        }

        // Determine status
        $status = !empty($errors) ? 'error' : (!empty($warnings) ? 'warning' : 'valid');

        return [
            'row_number' => $rowNumber,
            'data' => $row,
            'status' => $status,
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Generate import summary for preview
     */
    public function generateImportSummary(array $validationResults): array
    {
        return [
            'total_rows' => count($validationResults['valid']) + count($validationResults['warnings']) + count($validationResults['errors']),
            'valid_count' => count($validationResults['valid']),
            'warning_count' => count($validationResults['warnings']),
            'error_count' => count($validationResults['errors']),
            'will_create' => $this->countNewRecords($validationResults),
            'will_update' => $this->countExistingRecords($validationResults),
        ];
    }

    /**
     * Count new records to be created
     */
    protected function countNewRecords(array $validationResults): int
    {
        $count = 0;
        $allRows = array_merge($validationResults['valid'], $validationResults['warnings']);

        foreach ($allRows as $result) {
            $idNo = trim($result['data']['ID NO'] ?? '');
            if ($idNo && !Student::where('student_id', strtoupper($idNo))->exists()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Count existing records to be updated
     */
    protected function countExistingRecords(array $validationResults): int
    {
        $count = 0;
        $allRows = array_merge($validationResults['valid'], $validationResults['warnings']);

        foreach ($allRows as $result) {
            $idNo = trim($result['data']['ID NO'] ?? '');
            if ($idNo && Student::where('student_id', strtoupper($idNo))->exists()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Import rows with transaction
     *
     * @param array $rows Array of validated rows
     * @param string $strategy 'overwrite', 'merge', or 'skip'
     * @param int $uploadedBy User ID who performed the import
     * @return array Import results
     */
    public function importRows(array $rows, string $strategy = 'merge', int $uploadedBy): array
    {
        $results = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $result) {
                if ($result['status'] === 'error') {
                    $results['skipped']++;
                    $results['errors'][] = [
                        'row' => $result['row_number'],
                        'errors' => $result['errors'],
                    ];
                    continue;
                }

                $row = $result['data'];

                try {
                    $studentResult = $this->importStudentRow($row, $strategy);

                    if ($studentResult['action'] === 'created') {
                        $results['created']++;
                    } elseif ($studentResult['action'] === 'updated') {
                        $results['updated']++;
                    } elseif ($studentResult['action'] === 'skipped') {
                        $results['skipped']++;
                    }
                } catch (\Exception $e) {
                    $results['skipped']++;
                    $results['errors'][] = [
                        'row' => $result['row_number'],
                        'errors' => [$e->getMessage()],
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    /**
     * Import individual student row
     */
    protected function importStudentRow(array $row, string $strategy): array
    {
        $idNo = strtoupper(trim($row['ID NO']));
        $existing = Student::where('student_id', $idNo)->first();

        // Extract department code from ID NO
        preg_match('/\d+([A-Z]+)\d+/i', $idNo, $matches);
        $deptCode = strtoupper($matches[1] ?? '');

        $department = Department::where('code', $deptCode)->first();
        if (!$department) {
            throw new \Exception("Department {$deptCode} not found");
        }

        // Handle existing student based on strategy
        if ($existing) {
            if ($strategy === 'skip') {
                return ['action' => 'skipped', 'student' => $existing];
            }

            // Update student
            $updateData = $this->prepareStudentData($row, $department->id);

            if ($strategy === 'merge') {
                // Only update NULL or blank fields
                foreach ($updateData as $key => $value) {
                    $currentValue = $existing->{$key};
                    // Update if current is null, empty string, or 'NA'
                    if ($this->isBlankOrNA($currentValue) && !$this->isBlankOrNA($value)) {
                        $existing->{$key} = $value;
                    }
                }
            } else {
                // Overwrite all fields
                foreach ($updateData as $key => $value) {
                    $existing->{$key} = $value;
                }
            }

            $existing->save();

            // Handle placement data if exists
            $this->handlePlacementData($existing, $row, $strategy);

            return ['action' => 'updated', 'student' => $existing];
        }

        // Create new student
        $studentData = $this->prepareStudentData($row, $department->id);
        $studentData['student_id'] = $idNo;

        // Create or link user account
        $user = $this->createOrLinkUser($row);
        $studentData['user_id'] = $user->id;

        $student = Student::create($studentData);

        // Handle placement data if exists
        $this->handlePlacementData($student, $row, $strategy);

        return ['action' => 'created', 'student' => $student, 'user' => $user];
    }

    /**
     * Check if value is blank or NA
     */
    protected function isBlankOrNA($value): bool
    {
        if (is_null($value)) {
            return true;
        }

        $trimmed = trim(strtoupper((string)$value));
        return $trimmed === '' || $trimmed === 'NA';
    }

    /**
     * Prepare student data array from row
     */
    protected function prepareStudentData(array $row, int $departmentId): array
    {
        // Extract batch from ID NO (e.g., 20IT004 -> 2020)
        $idNo = $row['ID NO'] ?? '';
        preg_match('/^(\d{2})/', $idNo, $matches);
        $batchYear = isset($matches[1]) ? '20' . $matches[1] : null;

        return [
            'first_name' => $this->cleanValue($row['First Name'] ?? null),
            'middle_name' => $this->cleanValue($row['Middle Name'] ?? null),
            'last_name' => $this->cleanValue($row['Last Name'] ?? null),
            'roll_no' => $this->cleanValue($row['ID NO'] ?? null),
            'registration_no' => $this->cleanValue($row['ID NO'] ?? null),
            'gender' => $this->mapGender($row['Gender'] ?? null),
            'city' => $this->cleanValue($row['City'] ?? null),
            'contact' => $this->cleanValue($row['Mobile No'] ?? null),
            'email' => $this->cleanValue($row['CHARUSAT Email Id'] ?? null),
            'personal_email' => $this->cleanValue($row['Personal Email ID'] ?? null),
            'department_id' => $departmentId,
            'course' => 'B.Tech',
            'batch' => $batchYear,
            'ssc_percentage' => $this->cleanNumeric($row['10% or CGPA'] ?? null),
            'hsc_percentage' => $this->cleanNumeric($row['12% or CGPA'] ?? null),
            'diploma_percentage' => $this->cleanNumeric($row['Diploma % or CGPA'] ?? null),
            'btech_cgpa_upto_5th' => $this->cleanNumeric($row['B.Tech CGPA (Upto 5th Sem)'] ?? null),
            'cgpa' => $this->cleanNumeric($row['B.Tech CGPA (Upto 5th Sem)'] ?? null),
            'admission_type' => $this->cleanValue($row['admission'] ?? null),
            'is_eligible' => $this->mapYesNo($row['Eligible Students'] ?? null),
            'counsellor_name' => $this->cleanValue($row['Counsellor Name'] ?? null),
            'training_status' => 'NOT_ASSIGNED',
        ];
    }

    /**
     * Handle placement data for student
     */
    protected function handlePlacementData(Student $student, array $row, string $strategy): void
    {
        $companyName = trim($row['Company Name'] ?? '');
        $placed = trim($row['Placed'] ?? '');

        // Only create placement record if there's placement data
        if (empty($companyName) || $this->isBlankOrNA($placed) || $placed !== '1') {
            return;
        }

        // Get or create company
        $company = null;
        if (!empty($companyName) && !$this->isBlankOrNA($companyName)) {
            $company = Company::firstOrCreate(
                ['name' => $companyName],
                ['type' => 'RECRUITER']
            );
        }

        // Check if placement record exists
        $placement = StudentPlacement::where('student_id', $student->id)
            ->where('company_id', $company?->id)
            ->first();

        $placementData = [
            'student_id' => $student->id,
            'company_id' => $company?->id,
            'status' => 'OFFERED',
            'placed_by_charusat' => $this->mapYesNo($row['Placed By CHARUSAT?'] ?? null),
            'has_offer_letter' => $placed === '1',
            'package' => $this->cleanNumeric($row['Package
(in LPA Only)'] ?? $row['Package (in LPA Only)'] ?? null),
            'stipend' => $this->cleanNumeric($row['Stipend'] ?? null),
            'position' => $this->cleanValue($row['Remarks'] ?? null),
            'remarks' => $this->cleanValue($row['Remarks'] ?? null),
        ];

        if ($placement) {
            if ($strategy === 'merge') {
                // Only update blank/NA fields
                foreach ($placementData as $key => $value) {
                    if ($key === 'student_id') continue; // Skip ID fields
                    if ($this->isBlankOrNA($placement->{$key}) && !$this->isBlankOrNA($value)) {
                        $placement->{$key} = $value;
                    }
                }
                $placement->save();
            } elseif ($strategy === 'overwrite') {
                $placement->update($placementData);
            }
            // skip strategy: do nothing
        } else {
            StudentPlacement::create($placementData);
        }
    }

    /**
     * Create or link user account for student
     */
    protected function createOrLinkUser(array $row): User
    {
        $email = strtolower(trim($row['CHARUSAT Email Id']));

        // Check if user with this email exists
        $user = User::where('email', $email)->first();

        if ($user) {
            return $user;
        }

        // Create new user
        $password = $this->generateSecurePassword();
        $fullName = trim($row['Full Name'] ?? '');

        $user = User::create([
            'name' => $fullName,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $this->cleanValue($row['Mobile No'] ?? null),
            'is_active' => true,
        ]);

        // Assign Student role
        $user->assignRole('Student');

        // Store temporary password for email (in production, use password reset token)
        $user->temporary_password = $password;

        return $user;
    }

    /**
     * Clean value - convert NA/blank to null
     */
    protected function cleanValue($value)
    {
        if (is_null($value)) {
            return null;
        }

        $trimmed = trim((string)$value);

        if ($trimmed === '' || strtoupper($trimmed) === 'NA') {
            return null;
        }

        return $trimmed;
    }

    /**
     * Clean numeric value - convert NA/blank to null
     */
    protected function cleanNumeric($value)
    {
        $cleaned = $this->cleanValue($value);

        if (is_null($cleaned)) {
            return null;
        }

        if (!is_numeric($cleaned)) {
            return null;
        }

        return (float)$cleaned;
    }

    /**
     * Map gender values
     */
    protected function mapGender($value): ?string
    {
        $cleaned = $this->cleanValue($value);

        if (is_null($cleaned)) {
            return null;
        }

        $upper = strtoupper($cleaned);

        if (in_array($upper, ['MALE', 'M'])) {
            return 'M';
        }

        if (in_array($upper, ['FEMALE', 'F'])) {
            return 'F';
        }

        return 'O';
    }

    /**
     * Map YES/NO values
     */
    protected function mapYesNo($value): ?string
    {
        $cleaned = $this->cleanValue($value);

        if (is_null($cleaned)) {
            return null;
        }

        $upper = strtoupper($cleaned);

        if ($upper === 'YES' || $upper === 'Y' || $upper === '1') {
            return 'YES';
        }

        if ($upper === 'NO' || $upper === 'N' || $upper === '0') {
            return 'NO';
        }

        return null;
    }

    /**
     * Generate secure random password
     */
    protected function generateSecurePassword(int $length = 12): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*()_+-=';

        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        $all = $uppercase . $lowercase . $numbers . $special;
        for ($i = 4; $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }

        return str_shuffle($password);
    }
}

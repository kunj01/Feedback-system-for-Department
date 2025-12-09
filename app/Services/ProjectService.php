<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Department;

class ProjectService
{
    /**
     * Generate unique project ID in format: TP-{YEAR}-{DEPT_CODE}-{0001}
     */
    public function generateProjectId(?int $departmentId = null): string
    {
        $year = date('Y');
        $deptCode = 'GEN'; // Default

        if ($departmentId) {
            $department = Department::find($departmentId);
            if ($department && $department->code) {
                $deptCode = $department->code;
            }
        }

        // Get the last project for this year and department
        $lastProject = Project::where('project_id', 'like', "TP-{$year}-{$deptCode}-%")
            ->orderBy('project_id', 'desc')
            ->first();

        $sequence = 1;

        if ($lastProject) {
            // Extract sequence number from last project_id
            preg_match('/TP-\d{4}-[A-Z]+-(\d+)$/', $lastProject->project_id, $matches);
            if (isset($matches[1])) {
                $sequence = intval($matches[1]) + 1;
            }
        }

        return sprintf('TP-%s-%s-%04d', $year, $deptCode, $sequence);
    }

    /**
     * Calculate internal exam grade based on marks
     */
    public function calculateGrade(?float $marks): ?string
    {
        if ($marks === null) {
            return null;
        }

        if ($marks >= 70 && $marks <= 75) {
            return 'A+';
        } elseif ($marks >= 60 && $marks < 70) {
            return 'A';
        } elseif ($marks >= 50 && $marks < 60) {
            return 'B+';
        } elseif ($marks >= 40 && $marks < 50) {
            return 'B';
        } elseif ($marks >= 10 && $marks < 40) {
            return 'C';
        } else {
            return 'F';
        }
    }
}

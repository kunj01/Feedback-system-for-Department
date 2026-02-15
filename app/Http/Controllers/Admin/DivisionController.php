<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display divisions listing
     */
    public function index()
    {
        $divisions = Division::withCount(['students', 'batches'])
            ->orderBy('semester')
            ->orderBy('branch')
            ->orderBy('division_number')
            ->get();

        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Store a new division
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|integer|min:1|max:8',
            'branch' => 'required|string|max:50',
            'division_number' => 'required|integer|min:1',
        ]);

        // Check if division already exists
        $exists = Division::where('semester', $validated['semester'])
            ->where('branch', $validated['branch'])
            ->where('division_number', $validated['division_number'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Division already exists',
            ], 400);
        }

        $division = Division::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Division created successfully',
            'data' => $division,
        ]);
    }

    /**
     * Update division
     */
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);

        $division->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Division updated successfully',
            'data' => $division,
        ]);
    }

    /**
     * Delete division
     */
    public function destroy(Division $division)
    {
        // Check if division has students or batches
        if ($division->students()->count() > 0 || $division->batches()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete division with students or batches',
            ], 400);
        }

        $division->delete();

        return response()->json([
            'success' => true,
            'message' => 'Division deleted successfully',
        ]);
    }

    /**
     * Get divisions by filters (API endpoint)
     */
    public function getByFilters(Request $request)
    {
        $query = Division::query();

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('branch')) {
            $query->where('branch', $request->branch);
        }

        $divisions = $query->active()->orderBy('division_number')->get();

        return response()->json($divisions);
    }
}

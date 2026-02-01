<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $multiTeacherMode = SystemSettings::get('multi_teacher_feedback_mode', false);
        
        return view('admin.settings.index', compact('multiTeacherMode'));
    }

    /**
     * Update multi-teacher feedback mode
     */
    public function updateMultiTeacherMode(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean',
        ]);

        SystemSettings::set(
            'multi_teacher_feedback_mode',
            $request->enabled ? 'true' : 'false',
            'boolean',
            'Enable multi-teacher feedback mode for subjects'
        );

        return response()->json([
            'success' => true,
            'message' => 'Multi-teacher feedback mode ' . ($request->enabled ? 'enabled' : 'disabled') . ' successfully',
            'enabled' => $request->enabled
        ]);
    }

    /**
     * Get multi-teacher mode status
     */
    public function getMultiTeacherMode()
    {
        $enabled = SystemSettings::get('multi_teacher_feedback_mode', false);
        
        return response()->json([
            'success' => true,
            'enabled' => $enabled
        ]);
    }
}

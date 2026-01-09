<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use App\Models\SpeakerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpeakerController extends Controller
{
    public function index(Request $request)
    {
        $query = Speaker::where('created_by', Auth::id())
            ->with(['creator', 'approver', 'facultyApprover']);

        // Filter by faculty approval status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('faculty_approval_status', $status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } elseif ($sortBy === 'date') {
            $query->orderBy('date', $sortOrder)->orderBy('time', $sortOrder);
        }

        $speakers = $query->paginate(15)->appends($request->query());
        
        return view('faculty.speakers.index', compact('speakers', 'status', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        return view('faculty.speakers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'venue' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $validated['created_by'] = Auth::id();
        
        // Check if auto-approve is enabled
        if (SpeakerSetting::isAutoApproveEnabled()) {
            $validated['faculty_approval_status'] = 'approved';
            $validated['faculty_approved_by'] = Auth::id();
            $validated['faculty_approved_at'] = now();
            $message = 'Speaker added and auto-approved successfully! Admin will review for final approval.';
        } else {
            $validated['faculty_approval_status'] = 'pending';
            $message = 'Speaker added successfully! Waiting for admin approval.';
        }
        
        $validated['approval_status'] = 'pending'; // Admin approval always pending initially

        Speaker::create($validated);

        return redirect()->route('faculty.speakers.index')
            ->with('success', $message);
    }

    public function show(Speaker $speaker)
    {
        if ($speaker->created_by !== Auth::id()) {
            abort(403);
        }

        return view('faculty.speakers.show', compact('speaker'));
    }

    public function edit(Speaker $speaker)
    {
        if ($speaker->created_by !== Auth::id()) {
            abort(403);
        }

        return view('faculty.speakers.edit', compact('speaker'));
    }

    public function update(Request $request, Speaker $speaker)
    {
        if ($speaker->created_by !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'venue' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $speaker->update($validated);

        return redirect()->route('faculty.speakers.index')
            ->with('success', 'Speaker updated successfully!');
    }

    public function destroy(Speaker $speaker)
    {
        if ($speaker->created_by !== Auth::id()) {
            abort(403);
        }

        $speaker->delete();

        return redirect()->route('faculty.speakers.index')
            ->with('success', 'Speaker deleted successfully!');
    }
}

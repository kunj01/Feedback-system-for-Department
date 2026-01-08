<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpeakerController extends Controller
{
    public function index()
    {
        $speakers = Speaker::where('created_by', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);
        
        return view('faculty.speakers.index', compact('speakers'));
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

        Speaker::create($validated);

        return redirect()->route('faculty.speakers.index')
            ->with('success', 'Speaker added successfully!');
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

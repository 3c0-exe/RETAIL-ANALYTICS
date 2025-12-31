<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemAnnouncement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = SystemAnnouncement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'is_dismissible' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_dismissible'] = $request->has('is_dismissible');

        SystemAnnouncement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function edit(SystemAnnouncement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, SystemAnnouncement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'is_active' => 'boolean',
            'is_dismissible' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_dismissible'] = $request->has('is_dismissible');

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(SystemAnnouncement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    public function toggle(SystemAnnouncement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        return back()->with('success', 'Announcement ' . ($announcement->is_active ? 'activated' : 'deactivated') . '!');
    }

    public function dismiss(Request $request, SystemAnnouncement $announcement)
    {
        $announcement->dismissFor(auth()->id());

        return response()->json(['success' => true]);
    }
}

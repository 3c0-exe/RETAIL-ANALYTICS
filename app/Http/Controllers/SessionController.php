<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Get all active sessions for the authenticated user
     */
    public function index()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => $session->last_activity,
                    'is_current' => $session->id === session()->getId(),
                ];
            });

        return response()->json([
            'sessions' => $sessions,
            'current_session_id' => session()->getId()
        ]);
    }

    /**
     * Logout from all other devices
     */
    public function logoutOtherDevices(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Verify password
        if (!Hash::check($request->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        // Logout from all other sessions
        Auth::logoutOtherDevices($request->password);

        // Log the action
        activity()
            ->causedBy(Auth::user())
            ->log('Logged out from all other devices');

        return response()->json([
            'message' => 'Successfully logged out from all other devices.'
        ]);
    }

    /**
     * Delete a specific session (kick user from that device)
     */
    public function destroy(Request $request, $sessionId)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Verify password
        if (!Hash::check($request->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        // Don't allow deleting current session
        if ($sessionId === session()->getId()) {
            return response()->json([
                'message' => 'Cannot logout from current session. Use logout instead.'
            ], 400);
        }

        // Delete the session
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        // Log the action
        activity()
            ->causedBy(Auth::user())
            ->log("Terminated session: {$sessionId}");

        return response()->json([
            'message' => 'Session terminated successfully.'
        ]);
    }

    /**
     * Update user's last activity timestamp
     */
    public function updateActivity()
    {
        // Update last activity in sessions table
        DB::table('sessions')
            ->where('id', session()->getId())
            ->update(['last_activity' => time()]);

        return response()->json(['status' => 'ok']);
    }
}

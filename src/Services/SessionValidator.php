<?php

namespace Moox\Press\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionValidator
{
    public static function validateSession(Request $request)
    {
        $sessionId = $request->input('session_id');

        $session = DB::table('sessions')->where('id', $sessionId)->first();

        if (! $session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        try {
            $payload = decrypt(base64_decode($session->payload));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to decrypt session payload'], 500);
        }

        $sessionData = @unserialize($payload);

        if ($sessionData === false) {
            return response()->json(['error' => 'Failed to unserialize session data'], 500);
        }

        $userId = $sessionData['login_web'] ?? null;

        if (! $userId) {
            return response()->json(['error' => 'User not found in session'], 404);
        }

        return response()->json(['user_id' => $userId], 200);
    }
}

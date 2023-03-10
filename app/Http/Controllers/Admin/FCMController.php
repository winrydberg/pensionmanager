<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FCMController extends Controller
{
    public function updateFCMToken(Request $request)
    {
        try {
            $user = Auth::user();
            $user->update([
                'fcm_token' => $request->fcm_token
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Token successfully updated'
            ]);
        } catch (\Exception $e) {
            Log::error('FCM_TOKEN_UPDATE_ERROR => '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Ooops something went wrong.'
            ], 500);
        }
    }
}

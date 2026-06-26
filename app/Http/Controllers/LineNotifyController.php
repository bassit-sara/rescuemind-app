<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LineNotifyController extends Controller
{
    public function redirect(Request $request)
    {
        $clientId = env('LINE_NOTIFY_CLIENT_ID');
        $redirectUri = env('LINE_NOTIFY_REDIRECT_URI', url('/line-notify/callback'));
        $state = Str::random(40);

        // Store state in session for CSRF protection
        $request->session()->put('line_notify_state', $state);

        $url = "https://notify-bot.line.me/oauth/authorize?" . http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'notify',
            'state' => $state,
        ]);

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');
        $error = $request->input('error');

        if ($error) {
            return redirect()->route('profile.edit')->with('error', 'การเชื่อมต่อ LINE Notify ถูกยกเลิก หรือเกิดข้อผิดพลาด');
        }

        $savedState = $request->session()->pull('line_notify_state');
        if ($state !== $savedState) {
            return redirect()->route('profile.edit')->with('error', 'State ไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง');
        }

        $clientId = env('LINE_NOTIFY_CLIENT_ID');
        $clientSecret = env('LINE_NOTIFY_CLIENT_SECRET');
        $redirectUri = env('LINE_NOTIFY_REDIRECT_URI', url('/line-notify/callback'));

        // Exchange code for access token
        $response = Http::asForm()->post('https://notify-bot.line.me/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ]);

        if ($response->successful()) {
            $token = $response->json()['access_token'];

            // Save token to user
            $user = Auth::user();
            $user->line_notify_token = $token;
            $user->save();

            return redirect()->route('profile.edit')->with('status', 'เชื่อมต่อ LINE Notify สำเร็จแล้ว ระบบจะส่งการแจ้งเตือนไปยังบัญชี LINE ของคุณ');
        }

        return redirect()->route('profile.edit')->with('error', 'เกิดข้อผิดพลาดในการดึงข้อมูลจาก LINE Notify');
    }

    public function disconnect(Request $request)
    {
        $user = Auth::user();

        if ($user->line_notify_token) {
            // Revoke token via LINE Notify API
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $user->line_notify_token
            ])->post('https://notify-api.line.me/api/revoke');

            // Remove token from database
            $user->line_notify_token = null;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('status', 'ยกเลิกการเชื่อมต่อ LINE Notify เรียบร้อยแล้ว');
    }
}

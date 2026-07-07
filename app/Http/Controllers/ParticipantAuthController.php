<?php

namespace App\Http\Controllers;

use App\Models\SanghParticipant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ParticipantAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('participant.login');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        $mobile = $data['mobile'];

        $exists = SanghParticipant::where('mobile', $mobile)->exists();

        if (!$exists) {
            return back()->withErrors(['mobile' => __('sangh.not_found')])->withInput();
        }

        $otp = $this->generateOtp();

        // Cache OTP for 10 minutes
        Cache::put("participant_otp_{$mobile}", $otp, now()->addMinutes(10));

        // Store mobile in session so verify page knows which number to check
        session(['otp_mobile' => $mobile]);

        // In production: send SMS here
        // SmsService::send($mobile, "Your OTP is {$otp}");

        return redirect()->route('participant.verify');
    }

    public function showVerify(): View|RedirectResponse
    {
        if (!session('otp_mobile')) {
            return redirect()->route('participant.login');
        }

        return view('participant.verify', [
            'mobile' => session('otp_mobile'),
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $mobile = session('otp_mobile');

        if (!$mobile) {
            return redirect()->route('participant.login');
        }

        $data = $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $cached = Cache::get("participant_otp_{$mobile}");

        if (!$cached || $data['otp'] !== (string) $cached) {
            return back()->withErrors(['otp' => __('participant.otp_invalid')]);
        }

        Cache::forget("participant_otp_{$mobile}");
        session()->forget('otp_mobile');
        session(['participant_mobile' => $mobile]);

        return redirect()->route('participant.profile');
    }

    public function profile(): View
    {
        $mobile = session('participant_mobile');

        $registrations = SanghParticipant::where('mobile', $mobile)
            ->whereNull('group_leader_id')
            ->with(['sangh.event', 'groupMembers' => fn($q) => $q->orderBy('id')])
            ->orderByDesc('id')
            ->get();

        return view('participant.profile', compact('registrations', 'mobile'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('participant_mobile');
        return redirect()->route('participant.login');
    }

    private function generateOtp(): string
    {
        if (app()->environment('production')) {
            return (string) random_int(100000, 999999);
        }

        return env('DEMO_OTP', '1234');
    }
}

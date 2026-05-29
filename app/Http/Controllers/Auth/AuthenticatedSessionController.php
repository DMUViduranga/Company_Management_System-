<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\DeviceAuthorization;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        
        if ($user && Hash::check($request->password, $user->password)) {
            
         
            if (method_exists($user, 'isAdmin') && $user->isAdmin()) { 
                $request->authenticate();
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }

            
            $deviceToken = $request->cookie('device_guid');
            
           
            $device = DeviceAuthorization::where('user_id', $user->id)
                                        ->where('device_token', $deviceToken)
                                        ->first();

           
            if (!$device || !$device->is_approved) {
                
                
                if (!$deviceToken) {
                    $deviceToken = Str::uuid()->toString();
                }

                
                DeviceAuthorization::updateOrCreate(
                    ['user_id' => $user->id, 'device_token' => $deviceToken],
                    [
                        'browser_name' => $request->header('User-Agent'),
                        'ip_address' => $request->ip(),
                        'is_approved' => false // Pending Status
                    ]
                );

            
                return back()
                    ->withCookie(cookie()->forever('device_guid', $deviceToken))
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'Access Denied: Your device is not authorized yet. Please wait until an Admin approves it.'
                    ]);
            }
        }

       
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
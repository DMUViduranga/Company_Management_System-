<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

class WorkingHoursMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        
        if (!$user || $user->isAdmin()) {
            return $next($request);
        }

        $now = Carbon::now('Asia/Colombo');

        $startTimeString = $user->allowed_from ?? '08:00:00';
        $endTimeString = $user->allowed_to ?? '18:00:00';

        $allowedFrom = Carbon::createFromFormat('H:i:s', $startTimeString, 'Asia/Colombo');
        $allowedTo = Carbon::createFromFormat('H:i:s', $endTimeString, 'Asia/Colombo');

       
        if (!$now->between($allowedFrom, $allowedTo)) {
            
            
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired! Your allowed working hours are between ' . 
                           $allowedFrom->format('h:i A') . ' and ' . $allowedTo->format('h:i A') . '.'
            ]);
        }

        return $next($request);
    }
}
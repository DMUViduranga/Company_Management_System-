<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class WorkingHoursMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
      
        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

       
        $now  = Carbon::now('Asia/Colombo');
        $hour = (int) $now->format('G'); // 0-23

        $accessStart = 8;  // 08:00 AM
        $accessEnd   = 18; // 06:00 PM

      
        if ($hour < $accessStart || $hour >= $accessEnd) {
            return response()->view('errors.restricted', [
                'accessStart' => '08:00 AM',
                'accessEnd'   => '06:00 PM',
                'currentTime' => $now->format('h:i A'),
                'nextAccess'  => $hour >= $accessEnd
                    ? $now->copy()->addDay()->setTime($accessStart, 0)->format('D, d M Y \a\t h:i A')
                    : $now->copy()->setTime($accessStart, 0)->format('D, d M Y \a\t h:i A'),
            ], 403);
        }

        return $next($request);
    }
}
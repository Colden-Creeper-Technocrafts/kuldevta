<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsParticipant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('participant_mobile')) {
            return redirect()->route('participant.login');
        }

        return $next($request);
    }
}

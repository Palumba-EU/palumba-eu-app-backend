<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultElectionFallback
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Previously, there was only a single election supported at a time. Therefore, none of the entities
        // was related to a specific election. In an update, multi-election support was added, so all entities
        // like statements, topics, etc. are now related to a specific election. The new endpoints require
        // the election to load the entities for.
        // For backwards compatibility (for everyone that hasn't updated the app) there are still routes that do
        // not take an election parameter. Those routes should continue to return data for the original election.
        // This middleware falls back to this very first election and manually binds it to the route.
        if (is_null($request->route('election'))) {
            $election = Election::query()->orderBy('id')->firstOrFail();
            $request->route()->setParameter('election', $election);
        }

        return $next($request);
    }
}

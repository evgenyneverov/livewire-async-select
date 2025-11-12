<?php

namespace DrPshtiwan\LivewireAsyncSelect\Http\Middleware;

use Closure;
use DrPshtiwan\LivewireAsyncSelect\Support\InternalAuthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $hdr = $request->header('X-Internal-User');
        
        if (! $hdr) {
            return $next($request);
        }

        try {
            $payload = InternalAuthToken::verify($hdr);
        } catch (\Throwable $e) {
            abort(401, 'Internal auth failed: '.$e->getMessage());
        }

        $skew = (int) config('async-select.internal.skew', 60);
        
        if (isset($payload['m']) && strtoupper($payload['m']) !== $request->getMethod()) {
            abort(401, 'Method mismatch');
        }

        if (isset($payload['p']) && $payload['p'] !== $request->getPathInfo()) {
            abort(401, 'Path mismatch');
        }

        if (isset($payload['h'])) {
            $host = $request->getSchemeAndHttpHost();
            if ($payload['h'] !== $host) {
                abort(401, 'Host mismatch');
            }
        }

        if (! empty($payload['bh'])) {
            $raw = $request->getContent() ?? '';
            $bh  = hash('sha256', $raw);
            if (! hash_equals($payload['bh'], $bh)) {
                abort(401, 'Body hash mismatch');
            }
        }

        Auth::onceUsingId($payload['uid']);

        if (! empty($payload['perms'])) {
            foreach ($payload['perms'] as $perm) {
                if (! auth()->user()?->can($perm)) {
                    abort(403, 'Forbidden (permission: '.$perm.')');
                }
            }
        }

        return $next($request);
    }
}


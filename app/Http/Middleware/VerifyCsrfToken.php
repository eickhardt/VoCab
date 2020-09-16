<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        return parent::handle($request, $next);
    }

}

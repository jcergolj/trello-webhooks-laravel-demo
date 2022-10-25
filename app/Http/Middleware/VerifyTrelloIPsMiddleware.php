<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyTrelloIPsMiddleware
{
    const IP_START = '18.234.32.224';

    const IP_END = '18.234.32.239';

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->insideRange(ip2long($request->ip()))) {
            return $next($request);
        }

        abort(Response::HTTP_BAD_REQUEST, 'Bad incoming trello request.');
    }

    /**
     * @param  long  $ip
     * @return bool
     */
    protected function insideRange($ip)
    {
        return $ip >= ip2long('18.234.32.224') && $ip <= ip2long('18.234.32.239');
    }
}

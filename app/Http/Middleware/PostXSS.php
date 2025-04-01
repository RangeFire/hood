<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PostXSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // foreach($request->all() as $key => $value) {

        //     if(is_array($value)) {
        //         $frmtData = [];
        //         foreach($value as $key2 => $value2) {
        //             $frmtValue = $value2;
        //             $frmtValue = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $frmtValue);
        //             $frmtValue = strip_tags($frmtValue);
        //             $frmtData[] = $frmtValue;
        //         }

        //         $request->request->set($key, $frmtData);
                
        //     }else {
        //         $frmtValue = $value;
        //         $frmtValue = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $frmtValue);
        //         $frmtValue = strip_tags($frmtValue);
        //         $request->request->set($key, $frmtValue);
        //     }

        // }

        return $next($request);
    }
}

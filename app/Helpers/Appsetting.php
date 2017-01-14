<?php

if (!function_exists('Appsetting')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function Appsetting($name)
    {
    	return  \DB::table('appsetting')->whereName($name)->first()->value;
    }
}

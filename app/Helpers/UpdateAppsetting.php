<?php

if (!function_exists('UpdateAppsetting')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function UpdateAppsetting($name,$value)
    {
    	\DB::table('appsetting')->whereName($name)->update(['value'=>$value]);
    }
}

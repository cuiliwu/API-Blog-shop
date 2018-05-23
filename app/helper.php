<?php
/**
 * Created by Engineer CuiLiwu.
 * Project: deal.
 * Date: 2018/5/17-14:10
 * License Hangzhou orce Technology Co., Ltd. Copyright © 2018
 */

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;

if ( ! function_exists('config_path'))
{
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}
if(!function_exists('new_code')){
    /**
     * 用于生成新的code
     *
     */
    function newCode(){
        $code = time().createNoncestr(4);
        return $code;
    }

}
//取得随机代码
if (!function_exists('createNoncestr')) {
    function createNoncestr($length = 32, $type = '')
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        /**
         * 可以采用锁确保不重复，这里不采用，现在量级用不到
         */
        return $str;

    }
}

//解决lumen，$request->route()返回数组的问题
if (!function_exists('route_parameter')) {
    /**
     * Get a given parameter from the route.
     *
     * @param $name
     * @param null $default
     * @return mixed
     */
    function route_parameter($name, $default = null)
    {
        $routeInfo = app('request')->route();

        return array_get($routeInfo[2], $name, $default);
    }
}

/**
 * 浙商银行调用
 * @param $_url
 * @param array $_format
 * @return \App\Services\Http\GuzzleHttp
 */
if(!function_exists('http_czbank')){
    function http_czbank($_url, array $_format = [])
    {
        $api_host = config('url.czbank');
        list($method, $url) = $_url;
        if($_format){
            $url = vsprintf($url, $_format);
        }
        $url = $api_host . $url;

        return \App\Services\Http\GuzzleHttp::getInstance($method, $url);
    }
}

if(!function_exists('cui_log')){
    function cui_log($data,$filename){
        if(empty($filename)){
            $log_filename=$_SERVER['DOCUMENT_ROOT'].'/ecclub.php';
        }
        else{
            $log_filename=$_SERVER['DOCUMENT_ROOT'].'/'.$filename.'.php';
        }
        // 如果文件夹不存在则创建文件夹
        is_dir(dirname($log_filename)) || mkdir(dirname($log_filename),0777,true);
        // 日志大小
        $max_size=10485760;
        // 判断文件大小做自动删除动作
        if(file_exists($log_filename) && abs(filesize($log_filename))>$max_size){
            // 删除文件
            unlink($log_filename);
        }else{
            // 写日志(第三个参数是系统函数，可以连续写文件不覆盖)
            file_put_contents($log_filename, date("Y-m-d H:i:s",time()+8*60*60)." ".$data."\n", FILE_APPEND);
        }
    }
}



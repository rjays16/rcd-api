<?php

if(!function_exists('config_path'))
{
    function config_path($path=null)
    {
        return app()->getConfigurationPath(rtrim($path, ".php"));
    }
}

if(!function_exists('public_path'))
{

    function public_path($path=null)
    {
        return rtrim(app()->basePath('public/'.$path), '/');
    }
}

if(!function_exists('storage_path'))
{

    function storage_path($path=null)
    {
        return app()->storagePath($path);
    }
}

if(!function_exists('database_path'))
{

    function database_path($path=null)
    {
        return app()->databasePath($path);
    }
}

if(!function_exists('resource_path'))
{

    function resource_path($path=null)
    {
        return app()->resourcePath($path);
    }
}

//if(!function_exists('lang_path'))
//{
//
//    function lang_path($path=null)
//    {
//        return app()->getLanguagePath($path);
//    }
//}

if ( ! function_exists('asset'))
{

    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if ( ! function_exists('elixir'))
{

    function elixir($file)
    {
        static $manifest = null;
        if (is_null($manifest))
        {
            $manifest = json_decode(file_get_contents(public_path().'/build/rev-manifest.json'), true);
        }
        if (isset($manifest[$file]))
        {
            return '/build/'.$manifest[$file];
        }
        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}

if ( ! function_exists('auth'))
{
    function auth()
    {
        return app('Illuminate\Contracts\Auth\Guard');
    }
}

if ( ! function_exists('bcrypt'))
{
    function bcrypt($value, $options = array())
    {
        return app('hash')->make($value, $options);
    }
}

if ( ! function_exists('redirect'))
{
    function redirect($to = null, $status = 302, $headers = array(), $secure = null)
    {
        if (is_null($to)) return app('redirect');
        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if ( ! function_exists('response'))
{
    function response($content = '', $status = 200, array $headers = array())
    {
        $factory = app('Illuminate\Contracts\Routing\ResponseFactory');
        if (func_num_args() === 0)
        {
            return $factory;
        }
        return $factory->make($content, $status, $headers);
    }
}

if ( ! function_exists('secure_asset'))
{
    function secure_asset($path)
    {
        return asset($path, true);
    }
}

if ( ! function_exists('secure_url'))
{
    function secure_url($path, $parameters = array())
    {
        return url($path, $parameters, true);
    }
}


if ( ! function_exists('session'))
{
    function session($key = null, $default = null)
    {
        if (is_null($key)) return app('session');
        if (is_array($key)) return app('session')->put($key);
        return app('session')->get($key, $default);
    }
}


if ( ! function_exists('cookie'))
{

    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        $cookie = app('Illuminate\Contracts\Cookie\Factory');
        if (is_null($name))
        {
            return $cookie;
        }
        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
    }
}

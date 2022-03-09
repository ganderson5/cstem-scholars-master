<?php

abstract class HTTP
{
    private static $flash = null;

    public static function method()
    {
        return self::post('_method') ?? $_SERVER['REQUEST_METHOD'];
    }

    public static function isGet()
    {
        return self::method() == 'GET';
    }

    public static function isPost()
    {
        return self::method() == 'POST';
    }

    public static function get($param = null, $default = null)
    {
        return self::filter($_GET, $param, $default);
    }

    public static function post($param = null, $default = null)
    {
        return self::filter($_POST, $param, $default);
    }

    public static function session($param = null, $default = null)
    {
        return self::filter($_SESSION, $param, $default);
    }

    public static function cookie($param = null, $default = null)
    {
        return self::filter($_COOKIE, $param, $default);
    }

    public static function redirect($url, $flash = null)
    {
        if ($flash) {
            $_SESSION['flash'] = serialize($flash);
        }

        header("Location: $url");
        exit();
    }

    public static function error($body = 'Fatal error', $code = 400, $title = 'Error')
    {
        http_response_code($code);
        exit(HTML::template('error.php', ['body' => $body, 'title' => $title]));
    }

    public static function flash($key = null)
    {
        if (isset($_SESSION['flash'])) {
            self::$flash = unserialize($_SESSION['flash']);
            unset($_SESSION['flash']);
        }

        return self::$flash[$key] ?? self::$flash;
    }

    public static function url($path)
    {
        return BASE_URL . '/' . $path;
    }

    private static function filter($arr, $param, $default)
    {
        // Return the entire array if nothing was passed in
        if ($param === null) {
            return $arr;
        }

        if (is_array($param)) {
            // Remove extra columns
            $arr = array_intersect_key($arr, array_flip($param));

            // Add missing columns if necessary
            foreach ($param as $k) {
                if (!array_key_exists($k, $arr)) {
                    $arr[$k] = $default;
                }
            }

            return $arr;
        }

        return $arr[$param] ?? $default;
    }
}

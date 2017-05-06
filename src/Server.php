<?php
class Server
{
    public static $mimes = [
        'html' => 'text/html',
        'css' => 'text/css',
        'htm' => 'text/html',
        'jpeg' => 'text/jpeg',
        'jpg' => 'text/jpeg',
        'js' => 'text/javascript',
        'png' => 'image/png',
        'svg' => 'image/svg+xml'
    ];

    /**
     * Runs the server
     *
     * @param string|null $cwd
     * @param array $index
     * @param string $front
     *
     * @return bool|string
     */
    public static function run($cwd = null, array $index = ['html', 'php'], $front = '/index.php')
    {
        if ($cwd === null) {
            $cwd = getcwd();
        }

        $file = self::getFilePath($cwd, $index);

        //The file does not exists
        if ($file === false) {
            return false;
        }

        //The file is the front controller
        if (!empty($front) && ($file === $cwd.$front)) {
            return false;
        }

        //The file can be served by the php server
        if ($cwd === getcwd()) {
            return true;
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        //The file is a php script that must be included
        if ($ext === 'php') {
            return $file;
        }

        //Output the file content
        if (isset(self::$mimes[$ext])) {
            $mime = self::$mimes[$ext];
        } else {
            $info = new finfo(FILEINFO_MIME);
            $mime = $info->file($file);
        }

        header('Content-Type: '.$mime);
        readfile($file);
        exit;
    }

    /**
     * Returns the path of the request uri
     *
     * @return string|false
     */
    public static function getRequestPath()
    {
        if (empty($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return empty($path) ? $path : urldecode($path);
    }

    /**
     * Check whether the requested path exists
     *
     * @param string $cwd
     * @param array $index
     *
     * @return string|false
     */
    public static function getFilePath($cwd, array $index)
    {
        $path = self::getRequestPath();

        if ($path === false) {
            return false;
        }

        $file = $cwd.$path;

        if (is_file($file)) {
            return $file;
        }

        if (empty($index)) {
            return false;
        }

        foreach ($index as $ext) {
            $f = rtrim($file, '/').'/index.'.$ext;

            if (is_file($f)) {
                return $f;
            }
        }

        return false;
    }
}

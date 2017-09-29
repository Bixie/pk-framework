<?php


namespace Bixie\PkFramework\Helpers;

use Pagekit\Application as App;

class FileHelper
{

    /**
     * @param string $path
     * @param bool $create
     * @return string
     */
    public static function getPathFromRequest ($path = '', $create = true) {
        $root = strtr(App::path(), '\\', '/');
        $path = self::normalizePath($root . '/' . App::request()->get('root') . '/' . App::request()->get('path') . '/' . $path);
        if ($create && !is_dir($path)) {
            App::file()->makeDir($path);
        }
        return $path;
    }

    /**
     * Normalizes the given path
     * @param  string $path
     * @return string
     */
    public static function normalizePath ($path) {
        $path = str_replace(['\\', '//'], '/', $path);
        $prefix = preg_match('|^(?P<prefix>([a-zA-Z]+:)?//?)|', $path, $matches) ? $matches['prefix'] : '';
        $path = substr($path, strlen($prefix));
        $parts = array_filter(explode('/', $path), 'strlen');
        $tokens = [];

        foreach ($parts as $part) {
            if ('..' === $part) {
                array_pop($tokens);
            } elseif ('.' !== $part) {
                array_push($tokens, $part);
            }
        }

        return $prefix . implode('/', $tokens);
    }

    /**
     * @param $name
     * @return bool
     */
    public static function isValidFilename($name) {
        if (empty($name)) {
            return false;
        }

        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed = App::module('system/finder')->config['extensions'];
        if (!empty($extension) && !in_array($extension, explode(',', $allowed))) {
            return false;
        }

        if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
            return !preg_match('#[\\/:"*?<>|]#', $name);
        }

        return -1 !== strpos($name, '/');
    }

}
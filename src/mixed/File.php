<?php

namespace Magein\plugins\mixed;

/**
 * Class File
 * @package Tools
 */
class File
{
    /**
     * 根据通配符表达式匹配字符串信息
     * @param string $string 字符串
     * @param string $express 表达式
     * @return bool
     */
    public function match(string $string, string $express)
    {
        if (empty($string) || empty($match)) {
            return false;
        }

        // 把通配符表达式转化为正则表达式
        $length = strlen($express);
        $reg = '';
        for ($i = 0; $i < $length; $i++) {
            $char = $express[$i];
            if ($char == '?') {
                $reg .= '.?';
            } elseif ($char == '*') {
                $reg .= '.+';
            } else {
                $reg .= $char;
            }
        }

        $reg = '/' . $reg . '/';

        // 正则匹配
        if (preg_match($reg, $string)) {
            return true;
        };

        return false;
    }

    /**
     * 获取目录下的文件
     * @param string $path 物理地址
     * @param string $express 文件匹配，支持通配符
     * @return array
     */
    public function each(string $path, $express = null)
    {
        static $files = [];

        if (is_dir($path)) {
            $handle = opendir($path);
            while ($resource = readdir($handle)) {
                if ($resource != '.' && $resource != '..') {
                    $directory = $path . '/' . $resource;
                    if (is_file($directory)) {
                        if ($express) {
                            if ($this->match($resource, $express)) {
                                $files[] = $directory;
                            }
                        } else {
                            $files[] = $directory;
                        }
                    } else {
                        call_user_func_array([$this, __FUNCTION__], [$directory, $express]);
                    }
                }
            }
        }

        return $files;
    }

    /**
     * 复制文件到其他的文件夹
     * @param array|string $files 文件或者目录
     * @param string $src 目标目录
     * @param bool $keepDirectory 是否保持原目录结构
     * @return bool
     */
    public function copy($files, string $src, bool $keepDirectory = true)
    {
        if (empty($files)) {
            return false;
        }

        if (!is_dir($src)) {
            return false;
        }

        if (is_dir($files)) {
            $files = $this->each($files);
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        $flag = false;

        foreach ($files as $file) {

            if (is_file($file)) {

                $pathInfo = pathinfo($file);
                $baseName = $pathInfo['basename'];
                $dirName = $pathInfo['dirname'];
                if ($keepDirectory) {
                    $dir = $src . '/' . $dirName;
                    if (!is_dir($dir)) {
                        if (mkdir($dir, 0777, true)) {
                            $flag = copy($file, $src . '/' . $dirName . '/' . $baseName);
                        }
                    }
                } else {
                    $flag = copy($file, $src . '/' . $dirName);
                }
            }

            if (!$flag) {
                break;
            }

            $flag = true;
        }

        if (!$flag) {

            return false;
        }

        return true;
    }
}
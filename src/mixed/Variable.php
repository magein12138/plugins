<?php

namespace Magein\plugins\mixed;

/**
 * 驼峰，帕斯卡，下划线命名转化
 * Class Variable
 * @package Tools
 */
class Variable
{
    /**
     * 下划线命名转化为驼峰命名
     * @param string $variable
     * @return mixed|string
     */
    public function transToCamelCase(string $variable)
    {
        if (empty($variable)) {
            return $variable;
        }

        $variable = trim($variable, '_');

        $variable = preg_replace_callback('/_([a-z])/', function ($matches) {
            return ucfirst(isset($matches[1]) ? $matches[1] : '');
        }, $variable);

        return lcfirst($variable);
    }

    /**
     * 下划线命名(含驼峰)转化为帕斯卡命名法（驼峰命名法的首字母大写）
     * @param string $variable
     * @return mixed|string
     */
    public function transToPascal(string $variable)
    {
        if (empty($variable)) {
            return $variable;
        }

        $variable = trim($variable, '_');

        $variable = preg_replace_callback('/_([a-z])/', function ($matches) {
            return ucfirst(isset($matches[1]) ? $matches[1] : '');
        }, $variable);

        return ucfirst($variable);
    }

    /**
     * 驼峰转命名(含帕卡斯)转化为下划线命名
     * @param string $variable
     * @return mixed|string
     */
    public function transToUnderline(string $variable)
    {
        if (empty($variable)) {
            return $variable;
        }

        $variable = trim($variable, '_');

        $variable = preg_replace_callback('/([A-Z])/', function ($matches) {
            return '-' . lcfirst(isset($matches[1]) ? $matches[1] : '');
        }, $variable);

        return trim($variable, '_');
    }
}
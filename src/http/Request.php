<?php

namespace Magein\plugins\http;

class Request
{
    /**
     * @param $request
     * @param null $name
     * @param null $default
     * @param int|null $filter
     * @return mixed|null|false
     */
    private function request($request, $name = null, $default = null, int $filter = null)
    {
        if ($name) {

            if (isset($request[$name])) {

                $param = $request[$name];

                if ($filter) {
                    $param = filter_var($param, $filter);
                }

                return $param;
            }

            return null !== $default ? $default : null;
        }

        return $request;
    }

    /**
     * @param null $name
     * @param null $default
     * @param int|null $filter
     * @return false|mixed|null
     */
    public function param($name = null, $default = null, int $filter = null)
    {
        return $this->request($_REQUEST, $name, $default, $filter);
    }

    /**
     * @param null $name
     * @param null $default
     * @param int|null $filter
     * @return false|mixed|null
     */
    public function get($name = null, $default = null, int $filter = null)
    {
        return $this->request($_GET, $name, $default, $filter);
    }

    /**
     * @param null $name
     * @param null $default
     * @param int|null $filter
     * @return false|mixed|null
     */
    public function post($name = null, $default = null, int $filter = null)
    {
        return $this->request($_POST, $name, $default, $filter);
    }

    /**
     * @param null $name
     * @return null
     */
    public function file($name = null)
    {
        return $this->request($_FILES, $name);
    }

    /**
     * @return bool|string
     */
    public function input()
    {
        return file_get_contents('php://input');
    }

    /**
     * @param bool $toArray
     * @return bool|mixed|\SimpleXMLElement|string
     */
    public function xml($toArray = true)
    {
        $xml = file_get_contents('php://input');

        if ($toArray) {

            $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

            $xml = json_decode(json_encode($xml), true);
        }

        return $xml;
    }
}
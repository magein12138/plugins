<?php

/**
 * 提取头信息
 * Class Header
 */
class Header
{
    const PROTOCOL = 'protocol';

    const HOST = 'Host';

    const CONNECTION = 'Connection';

    const PRAGMA = 'Pragma';

    const CACHE_CONTROL = 'Cache-Control';

    const USER_AGENT = 'User-Agent';

    const UPGRADE = 'Upgrade';

    const ORIGIN = 'Origin';

    const SEC_WEB_SOCKET_VERSION = 'Sec-WebSocket-Version';

    const ACCEPT_ENCODING = 'Accept-Encoding';

    const ACCEPT_LANGUAGE = 'Accept-Language';

    const SEC_WebSocket_Key = 'Sec-WebSocket-Key';

    private $header = [];

    public function __construct($header)
    {
        $this->extract($header);
    }

    /**
     * @param $header
     * @return array
     */
    private function extract($header)
    {
        if (empty($header)) {
            return $this->header;
        }

        preg_match_all('/.*+\n/', $header, $matches);

        if (isset($matches[0])) {
            foreach ($matches[0] as $key => $match) {
                $match = trim($match);
                $colon = strpos(trim($match), ':');
                if ($colon > 0) {
                    $this->header[substr($match, 0, $colon)] = trim(substr($match, $colon + 1));
                } else {
                    $this->header[self::PROTOCOL] = $match;
                }
            }
        }

        return $this->header;
    }

    /**
     * @param string $name
     * @return array|mixed|string
     */
    public function get($name = '')
    {
        if ($name) {
            return isset($this->header[$name]) ? $this->header[$name] : '';
        }

        return $this->header;
    }
}


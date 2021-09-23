<?php

namespace Snowdog\DevTest\Model;

class Varnish
{

    public $server_id;
    public $ip;
    public $user_id;

    public function __construct()
    {
        $this->user_id = intval($this->user_id);
        $this->server_id = intval($this->server_id);
    }

    /**
     * @return int
     */
    public function getServerId()
    {
        return $this->server_id;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
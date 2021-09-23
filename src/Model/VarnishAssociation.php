<?php

namespace Snowdog\DevTest\Model;

class VarnishAssociation
{

    public $server_id;
    public $entity_id;
    public $website_id;

    public function __construct()
    {
        $this->entity_id = intval($this->entity_id);
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
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }
}
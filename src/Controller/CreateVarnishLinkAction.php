<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\Website;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {

        $web = $_POST['webId'];
        $server = $_POST['serverId'];
        $link = $_POST['link'];

        if ($link == "true") {
            $this->varnishManager->link($server, $web);
        } else {
            $this->varnishManager->unlink($server, $web);
        }

        echo true;
        exit;
    }
}
<?php

namespace Snowdog\DevTest\Controller;

class LoginFormAction extends \Snowdog\DevTest\Controller\BaseAction
{

    public function execute()
    {
        parent::__construct();
        require __DIR__ . '/../view/login.phtml';
    }
}
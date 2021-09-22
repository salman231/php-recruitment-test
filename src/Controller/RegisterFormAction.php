<?php

namespace Snowdog\DevTest\Controller;

class RegisterFormAction extends \Snowdog\DevTest\Controller\BaseAction
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute() {
        require __DIR__ . '/../view/register.phtml';
    }
}
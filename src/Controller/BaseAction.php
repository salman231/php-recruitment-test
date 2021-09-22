<?php

namespace Snowdog\DevTest\Controller;

class BaseAction
{
    public function __construct()
    {
        if (isset($_SESSION['login'])) {
            if ($_SERVER['REQUEST_URI'] == "/login" || $_SERVER['REQUEST_URI'] == "/register") {
                require __DIR__ . '/../view/403.phtml';
                exit();
            }
        } else {
            if ($_SERVER['REQUEST_URI'] != "/login" && $_SERVER['REQUEST_URI'] != "/register" && $_SERVER['REQUEST_URI'] != "/") {
                require __DIR__ . '/../view/login.phtml';
                exit();
            }
        }
    }
}
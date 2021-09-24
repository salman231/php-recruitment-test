<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\PageManager;

class IndexAction extends \Snowdog\DevTest\Controller\BaseAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    private $pageManager;
    CONST ORDER_DESC = 'DESC';
    CONST ORDER_ASC = 'ASC';

    public function __construct(
        UserManager $userManager,
        WebsiteManager $websiteManager,
        PageManager $pageManager
    )
    {
        parent::__construct();
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }

    public function loggedInUserTotalPages()
    {
        $pagesNumber = 0;
        foreach ($this->getWebsites() as $website) {
            $pages = $this->pageManager->getAllByWebsite($website);
            $pagesNumber += count($pages);
        }
        return $pagesNumber;
    }

    public function getRecentlyPageVisited()
    {
        $page = $this->pageManager->getUsersPageVisited($this->user, $this::ORDER_DESC);
        if ($page){
            return $page->getUrl();
        }else{
            return "Didnt visited any page";
        }
    }

    public function getLeastPageVisited()
    {
        $page = $this->pageManager->getUsersPageVisited($this->user, $this::ORDER_ASC);
        if ($page){
            return $page->getUrl();
        }else{
            return "Didnt visited any page";
        }    }

}
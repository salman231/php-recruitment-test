<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;
use Old_Legacy_CacheWarmer_Resolver_Method;
use Old_Legacy_CacheWarmer_Actor;
use Old_Legacy_CacheWarmer_Warmer;

class WarmCommand
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;
    /**
     * @var Old_Legacy_CacheWarmer_Resolver_Method
     */
    private $cacheWarmerResolver;
    /**
     * @var Old_Legacy_CacheWarmer_Actor
     */
    private $cacheWarmerActor;
    /**
     * @var Old_Legacy_CacheWarmer_Warmer
     */
    private $cacheWarmer;

    public function __construct(
        WebsiteManager $websiteManager,
        PageManager $pageManager,
        Old_Legacy_CacheWarmer_Resolver_Method $cacheWarmerResolver,
        Old_Legacy_CacheWarmer_Actor $cacheWarmerActor,
        Old_Legacy_CacheWarmer_Warmer $cacheWarmer
    )
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->cacheWarmerResolver = $cacheWarmerResolver;
        $this->cacheWarmerActor = $cacheWarmerActor;
        $this->cacheWarmer = $cacheWarmer;
    }

    public function __invoke($id, OutputInterface $output)
    {
        $website = $this->websiteManager->getById($id);
        if ($website) {
            $pages = $this->pageManager->getAllByWebsite($website);
            $this->cacheWarmerActor->setActor(function ($hostname, $ip, $url) use ($output) {
                $output->writeln('Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip . '</comment>');
            });
            $warmer = $this->cacheWarmer;
            $warmer->setResolver($this->cacheWarmerResolver);
            $warmer->setHostname($website->getHostname());
            $warmer->setActor($this->cacheWarmerActor);

            foreach ($pages as $page) {
                $warmer->warm($page->getUrl());
                $this->pageManager->setLastPageVisit($page);
            }
        } else {
            $output->writeln('<error>Website with ID ' . $id . ' does not exists!</error>');
        }
    }
}
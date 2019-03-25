<?php
namespace OCA\NextCeph\AppInfo;

use \OCP\AppFramework\App;
use \OCA\NextCeph\Service\AuthorService;

class Application extends App {

    public function __construct(array $urlParams=array()){
        parent::__construct('nextceph', $urlParams);

        $container = $this->getContainer();
        /**
         * Controllers
         */
        $container->registerService('AuthorService', function($c) {
            return new AuthorService(
                $c->query('Config'),
                $c->query('AppName')
            );
        });

        $container->registerService('Config', function($c) {
            return $c->query('ServerContainer')->getConfig();
        });
    }
}

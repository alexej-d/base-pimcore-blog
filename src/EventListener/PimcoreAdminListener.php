<?php

namespace App\EventListener;

use Pimcore\Event\BundleManager\PathsEvent;

class PimcoreAdminListener
{
    const PUBLIC_PATH = '/static';
    const WEBPACK_PATH = '//localhost:8080/static';

    public static function isDevMode()
    {
        return isset($_ENV['NODE_ENV']) && $_ENV['NODE_ENV'] === 'development';
    }

    public static function getPrefixedPath($path)
    {
        return sprintf(
            '%s/%s',
            self::isDevMode() ? self::WEBPACK_PATH : self::PUBLIC_PATH,
            $path
        );
    }

    public function addCSSFiles(PathsEvent $event)
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                self::isDevMode() ? [] : [ self::getPrefixedPath('styles/editmode.css') ]
            )
        );
    }

    public function addEditmodeJSFiles(PathsEvent $event)
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [ self::getPrefixedPath('scripts/editmode.js') ]
            )
        );
    }

    public function addJSFiles(PathsEvent $event)
    {
        $event->setPaths(
            array_merge(
                $event->getPaths(),
                [ self::getPrefixedPath('scripts/admin.js') ]
            )
        );
    }
}

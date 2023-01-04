<?php

namespace App\Document\Areabrick;

use App\Document\Areabrick\AbstractAreabrick;

class FeaturedBlogPost extends AbstractAreabrick
{
    public function getName()
    {
        return 'Featured Beitrag';
    }

    public static function getGroup(): string
    {
        return 'Teaser';
    }

    public function getConfig()
    {
        return [
            $this->getHeaderLevel(),
        ];
    }
}

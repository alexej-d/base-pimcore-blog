<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Pimcore\Model\DataObject\BlogCategory\Listing;

class BlogExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('blog_cats', [$this, 'getBlogCategories']),
        ];
    }

    public function getBlogCategories(): Listing
    {
        $listing = new Listing();

        return $listing;
    }
}

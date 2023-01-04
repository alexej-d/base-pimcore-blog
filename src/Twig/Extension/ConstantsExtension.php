<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConstantsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('cn_row', [$this, 'rowClasses']),
            new TwigFunction('cn_title', [$this, 'titleClasses']),
            new TwigFunction('cn_prose', [$this, 'proseClasses']),
            new TwigFunction('cn_button', [$this, 'buttonClasses']),
            new TwigFunction('cn_gap', [$this, 'gapClasses']),
        ];
    }

    public function rowClasses(): string
    {
        return 'container mx-auto px-6';
    }

    public function titleClasses(): string
    {
        return 'font-bold leading-none';
    }

    public function proseClasses(): string
    {
        return 'prose prose-invert';
    }

    public function buttonClasses(): string
    {
        return '';
    }

    public function gapClasses(): string
    {
        return 'px-4 lg:px-2';
    }
}

<?php

namespace App\Twig\Extension;

use Twig\Error\RuntimeError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HelperExtension extends AbstractExtension
{
    private $ids = [];

    public function getFunctions()
    {
        return [
            new TwigFunction('env', [$this, 'env']),
            new TwigFunction('cn', [$this, 'htmlClasses']),
            new TwigFunction('uniqid', function (string $prefix = '') {
                if (!array_key_exists($prefix, $this->ids)) $this->ids[$prefix] = 0;
                $this->ids[$prefix] += 1;

                return $prefix . $this->ids[$prefix];
            }),
        ];
    }

    public function env($var)
    {
        return isset($_ENV[$var]) ? $_ENV[$var] : false;
    }

    /** @copyright by Fabien Potencier, taken here https://github.com/twigphp/html-extra/blob/2.x/HtmlExtension.php */
    public function htmlClasses(...$args): string
    {
        $classes = [];
        foreach ($args as $i => $arg) {
            if (\is_string($arg)) {
                $classes[] = $arg;
            } elseif (\is_array($arg)) {
                foreach ($arg as $class => $condition) {
                    if (!\is_string($class)) {
                        throw new RuntimeError(sprintf('The html_classes function argument %d (key %d) should be a string, got "%s".', $i, $class, \gettype($class)));
                    }
                    if (!$condition) {
                        continue;
                    }
                    $classes[] = $class;
                }
            } else {
                throw new RuntimeError(sprintf('The html_classes function argument %d should be either a string or an array, got "%s".', $i, \gettype($arg)));
            }
        }

        return implode(' ', array_filter(array_unique($classes), function ($item) { return !empty(trim($item)); }));
    }
}

<?php

namespace App\Website\LinkGenerator;

use Pimcore\Model\DataObject\ClassDefinition\LinkGeneratorInterface;
use Pimcore\Http\Request\Resolver\DocumentResolver;
use Pimcore\Localization\LocaleServiceInterface;
use Pimcore\Twig\Extension\Templating\PimcoreUrl;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;

abstract class AbstractLinkGenerator implements LinkGeneratorInterface
{
    protected SluggerInterface $slugger;

    protected DocumentResolver $documentResolver;

    protected RequestStack $requestStack;

    protected PimcoreUrl $pimcoreUrl;

    protected LocaleServiceInterface $localeService;

    /**
     * BlogPostLinkGenerator constructor.
     *
     * @param SluggerInterface $slugger
     * @param DocumentResolver $documentResolver
     * @param RequestStack $requestStack
     * @param PimcoreUrl $pimcoreUrl
     * @param LocaleServiceInterface $localeService
     */
    public function __construct(SluggerInterface $slugger, DocumentResolver $documentResolver, RequestStack $requestStack, PimcoreUrl $pimcoreUrl, LocaleServiceInterface $localeService)
    {
        $this->slugger = $slugger;
        $this->documentResolver = $documentResolver;
        $this->requestStack = $requestStack;
        $this->pimcoreUrl = $pimcoreUrl;
        $this->localeService = $localeService;
    }
}

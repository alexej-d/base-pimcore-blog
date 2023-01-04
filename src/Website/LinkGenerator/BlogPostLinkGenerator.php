<?php

namespace App\Website\LinkGenerator;

use App\Website\Tool\ForceInheritance;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\BlogPost;
use Pimcore\Model\Document;

class BlogPostLinkGenerator extends AbstractLinkGenerator
{
    /**
     * @param Concrete $object
     * @param array $params
     *
     * @return string
     */
    public function generate(Concrete $object, array $params = []): string
    {
        if (!($object instanceof BlogPost)) {
            throw new \InvalidArgumentException('Given object is no BlogPost');
        }

        return ForceInheritance::run(function () use ($object, $params) {
            if (!empty($object->getSlug())) {
                return current($object->getSlug())->getSlug();
            }

            $fullPath = '';
            $locale = $this->localeService->getLocale();

            if (isset($params['document']) && $params['document'] instanceof Document) {
                $document = $params['document'];
            } else {
                $document = $this->documentResolver->getDocument($this->requestStack->getCurrentRequest());
            }

            $localeUrlPart = '/' . $locale . '/';
            if ($document && $localeUrlPart !== $document->getFullPath()) {
                $fullPath = substr($document->getFullPath(), strlen($localeUrlPart));
            }

            if ($document && !$fullPath) {
                $fullPath = $document->getProperty('blog_default_document') ? substr($document->getProperty('blog_default_document')->getFullPath(), strlen($localeUrlPart)) : '';
            }

            return $this->pimcoreUrl->__invoke(
                [
                    'name' => $this->slugger->slug($object->getTitle($locale), '-', $locale)->toString(),
                    'object' => $object->getId(),
                    'path' => $fullPath
                ],
                'blog-detail',
                true
            );
        });
    }
}

<?php

namespace App\EventListener;

use App\Model\DataObject\SluggableInterface;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Data\UrlSlug;
use Pimcore\Model\Site;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SluggableListener implements EventSubscriberInterface
{
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            DataObjectEvents::PRE_UPDATE => 'event',
            DataObjectEvents::PRE_ADD => 'event',
        ];
    }

    public function event(DataObjectEvent $dataObjectEvent)
    {
        $object = $dataObjectEvent->getObject();

        if (!$object instanceof SluggableInterface) return;

        $referenceFieldName = get_class($object)::SLUG_REFERENCE;
        $slugPrefix = get_class($object)::SLUG_PREFIX;
        $defaultLanguage = Tool::getDefaultLanguage();
        $sites = new Site\Listing();

        foreach (Tool::getValidLanguages() as $language) {
            $newSlugs = [];
            $actualSlugs = [];
            $title = $object->{'get' . ucfirst($referenceFieldName)}($language);

            if (!$title) continue;

            $slug = sprintf(
                '%s%s/%s',
                $language === $defaultLanguage ? '' : '/' . $language,
                $slugPrefix,
                strtolower($this->slugger->slug($title, '-', $language)->toString())
            );

            $newSlugs[] = new UrlSlug($slug, 0);

            foreach ($sites->getSites() as $site) {
                $newSlugs[] = new UrlSlug($slug, $site->getId());
            }

            $existingSlugs = $object->getSlug($language);

            foreach ($newSlugs as $newSlug) {
                $found = false;

                foreach ($existingSlugs as $existingSlug) {
                    if ($existingSlug->getSiteId() === $newSlug->getSiteId()) {
                        if ($existingSlug->getSlug() === $newSlug->getSlug()) {
                            $actualSlugs[] = $existingSlug;
                        }
                        else {
                            $newSlug->setPreviousSlug($existingSlug->getSlug());
                            $actualSlugs[] = $newSlug;

                        }
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $actualSlugs[] = $newSlug;
                }
            }

            $object->setSlug($actualSlugs, $language);
        }
    }
}

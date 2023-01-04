<?php

namespace App\Twig\Extension;

use App\Document\Areabrick\AbstractAreabrick;
use Pimcore\Extension\Document\Areabrick\AreabrickManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BricksExtension extends AbstractExtension
{
    protected $blacklist = ['formbuilder_form'];
    protected AreabrickManager $brickManager;

    public function __construct(AreabrickManager $brickManager)
    {
        $this->brickManager = $brickManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('bricks_config', [$this, 'getBricksConfig'], [
                'needs_context' => true,
            ]),
        ];
    }

    public function getBricksConfig(?array $context = [], ?array $merge = []): array
    {
        $group = [];
        $areaElements = $this->brickManager->getBricks();
        //sort area elements by key => area name
        ksort($areaElements);

        /** @var AbstractAreabrick $areaElementData */
        foreach ($areaElements as $areaElementName => $areaElementData) {
            if (!$this->brickManager->isEnabled($areaElementName)) {
                unset($areaElements[$areaElementName]);
            }
        }

        /** @var AbstractAreabrick $areaElementData */
        foreach ($areaElements as $areaElementData) {
            $id = $areaElementData->getId();
            if (in_array($id, $this->blacklist)) continue;
            if ($areaElementData instanceof AbstractAreabrick)
                $current = $areaElementData::getGroup();
            else $current = AbstractAreabrick::getGroup();
            if (!isset($group[$current])) $group[$current] = [];
            $group[$current][] = $id;
        }

        ksort($group);

        return $merge + [
            'group' => $group,
        ];
    }
}

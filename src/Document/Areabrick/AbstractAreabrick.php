<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Extension\Document\Areabrick\EditableDialogBoxConfiguration;
use Pimcore\Extension\Document\Areabrick\EditableDialogBoxInterface;
use Pimcore\Model\Document;
use Pimcore\Model\Document\Editable\Area\Info;

abstract class AbstractAreabrick extends AbstractTemplateAreabrick implements EditableDialogBoxInterface
{
    /**
     * @inheritDoc
     */
    public function getTemplateLocation()
    {
        return static::TEMPLATE_LOCATION_GLOBAL;
    }

    /**
     * @inheritDoc
     */
    public function getTemplateSuffix()
    {
        return static::TEMPLATE_SUFFIX_TWIG;
    }

        /**
     * @inheritDoc
     */
    public function getHtmlTagOpen(Info $info)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getHtmlTagClose(Info $info)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getIcon()
    {
        return '/bundles/pimcoreadmin/img/flat-color-icons/document.svg';
    }

    public static function getHeaderLevel()
    {
        return [
            'label' => 'Headline level (H2 ist der Standard)',
            'type' => 'select',
            'name' => 'level',
            'config' => [
                'defaultValue' => 2,
                'store' => [
                    [1, 'Headline 1'],
                    [2, 'Headline 2'],
                    [3, 'Headline 3'],
                    [4, 'Headline 4'],
                    [5, 'Headline 5'],
                ],
            ],
        ];
    }

    public static function getElementType()
    {
        return [
            'label' => 'Elementtyp',
            'type' => 'select',
            'name' => 'element',
            'config' => [
                'defaultValue' => 'div',
                'store' => [
                    ['div', 'DIV (Standard)'],
                    ['header', 'HEADER'],
                    ['section', 'SECTION'],
                    ['aside', 'ASIDE'],
                ],
            ],
        ];
    }

    public static function getOrientation($exclude = [])
    {
        $store = [
            ['', 'Erben (Standard, meist links)'],
            ['left', 'Links'],
            ['middle', 'Mitte'],
            ['right', 'Rechts'],
        ];

        if (!empty($exclude)) {
            $store = array_values(array_filter(
                $store,
                function($item) use ($exclude) { return !in_array($item[0], $exclude); }
            ));
        }

        return [
            'label' => 'Ausrichtung',
            'type' => 'select',
            'name' => 'orientation',
            'config' => [
                'defaultValue' => '',
                'store' => $store,
            ],
        ];
    }

    public static function getGroup(): string
    {
        return 'Allgemeine Elemente';
    }

    public function getConfig()
    {
        return [];
    }

    private static function find(array $array, $searchKey, $searchValue, $ignored = [])
    {
        $result = null;
        foreach ($array as $key => $value) {
            if (in_array($key, $ignored)) continue;
            if ($key === $searchKey && $value === $searchValue) $result = $array;
            if (is_array($value)) $result = self::find($value, $searchKey, $searchValue, $ignored);
            if ($result) break;
        }
        return $result;
    }

    public function getData(Info $info, $field, $default = null)
    {
        $conf = self::find($this->getConfig(), 'name', $field, ['config']);
        if (!$conf) return null;

        $value = $this->getDocumentEditable($info->getDocument(), $conf['type'], $field)->getData();
        if (!is_null($default) && !$value) return $default;

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getEditableDialogBoxConfiguration(Document\Editable $area, ?Info $info): EditableDialogBoxConfiguration
    {

        $items = $this->getConfig();
        $config = new EditableDialogBoxConfiguration();
        $config->setWidth(600);
        $config->setItems($items);

        return $config;
    }
}

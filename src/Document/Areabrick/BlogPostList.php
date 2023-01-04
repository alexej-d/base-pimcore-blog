<?php

namespace App\Document\Areabrick;

use App\Document\Areabrick\AbstractAreabrick;
use Pimcore\Model\DataObject;
use Pimcore\Model\Document\Editable\Area\Info;

class BlogPostList extends AbstractAreabrick
{
    public function getName()
    {
        return 'Blogposts';
    }

    public static function getGroup(): string
    {
        return 'Liste';
    }

    public function getConfig()
    {
        return [
            $this->getHeaderLevel(),
            [
                'label' => 'Wie viele Beiträge überspringen?',
                'type' => 'numeric',
                'name' => 'offset',
            ],
            [
                'label' => 'Bestimmten Beitrag auslassen?',
                'type' => 'relation',
                'name' => 'exclude'
            ],
        ];
    }

    public function action(Info $info)
    {
        $offset = $this->getData($info, 'offset', 0);
        $exclude = $this->getData($info, 'exclude');

        $objects = DataObject\BlogPost::getList();
        $objects->setLimit(9);
        $objects->setOffset($offset);

        if ($exclude && isset($exclude['id'])) {
            $objects->setCondition('oo_id != ?', $exclude['id']);
        }

        $info->setParam('objects', $objects);
    }
}

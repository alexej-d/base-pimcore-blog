<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends FrontendController
{
    /**
     * @Route("/search", name="search")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function detailAction(
        Request $request,
    ) {
        $query = $request->get('q');
        $params = ['query' => $query, 'objects' => []];

        if (empty($query)) {
            return $this->render('search/index.html.twig', $params);
        }

        $objects = DataObject\BlogPost::getList();
        $objects->setCondition(
            '(title LIKE :query OR content LIKE :query OR teaserContent LIKE :query)',
            [':query' => '%' . $objects->escapeLike($query) . '%']
        );

        $params['objects'] = $objects;

        return $this->render('search/index.html.twig', $params);
    }

}

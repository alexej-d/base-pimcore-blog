<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
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
        return $this->render('blog/detail.html.twig', []);
    }

}

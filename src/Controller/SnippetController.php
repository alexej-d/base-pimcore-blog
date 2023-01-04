<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SnippetController extends FrontendController
{
    /**
     * @Template()
     * @param Request $request
     */
    public function footerAction(Request $request): array
    {
        return [
            'root' => $request->get('root'),
        ];
    }
}

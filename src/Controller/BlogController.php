<?php

namespace App\Controller;

use App\Website\LinkGenerator\BlogPostLinkGenerator;
use App\Website\LinkGenerator\BlogCategoryLinkGenerator;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends FrontendController
{
    public function detailSlugAction(Request $request, DataObject\BlogPost $object) {
        return $this->forward(self::class . '::detailAction', ['object' => $object]);
    }

    public function categoryDetailSlugAction(Request $request, DataObject\BlogCategory $object) {
        return $this->forward(self::class . '::categoryDetailAction', ['object' => $object]);
    }

    /**
     * @Route("/blog/{path}{name}~p{object}", name="blog-detail", defaults={"path"=""}, requirements={"path"=".*?", "name"="[\w-]+", "object"="\d+"})
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function detailAction(
        Request $request,
        Concrete $object,
        BlogPostLinkGenerator $linkGenerator
    ) {
        if (!($object && $object->isPublished() && $object instanceof DataObject\BlogPost)) {
            throw new NotFoundHttpException('Post not found.');
        }

        //redirect to main url if applicable
        $generatorUrl = $linkGenerator->generate($object);
        if ($generatorUrl != $request->getPathInfo()) {
            $queryString = $request->getQueryString();

            return $this->redirect($generatorUrl . ($queryString ? '?' . $queryString : ''));
        }

        return $this->render('blog/detail.html.twig', ['object' => $object]);
    }

    /**
     * @param Request $request
     * @param Factory $ecommerceFactory
     *
     * @return Response
     */
    public function blogPostTeaserAction(Request $request) {
        if ($request->get('type') === 'object' && $request->get('id')) {
            AbstractObject::setGetInheritedValues(true);
            $object = DataObject\BlogPost::getById($request->get('id'));

            if ($object) {
                return $this->render('blog/teaser.html.twig', [
                    'object' => $object,
                    'level' => $request->get('level'),
                ]);
            }
        }

        throw new NotFoundHttpException('Post not found.');
    }

    /**
     * @Route("/blog/cat/{path}{name}~c{object}", name="blog-category-detail", defaults={"path"=""}, requirements={"path"=".*?", "name"="[\w-]+", "object"="\d+"})
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function categoryDetailAction(
        Request $request,
        Concrete $object,
        BlogCategoryLinkGenerator $linkGenerator
    ) {
        if (!($object && $object->isPublished() && $object instanceof DataObject\BlogCategory)) {
            throw new NotFoundHttpException('Category not found.');
        }

        //redirect to main url if applicable
        $generatorUrl = $linkGenerator->generate($object);
        if ($generatorUrl != $request->getPathInfo()) {
            $queryString = $request->getQueryString();

            return $this->redirect($generatorUrl . ($queryString ? '?' . $queryString : ''));
        }

        $objects = DataObject\BlogPost::getList();
        $objects->setCondition('categories LIKE ?', '%' . $object->getId() . '%');

        return $this->render('blog/category-detail.html.twig', [
            'object' => $object,
            'objects' => $objects,
        ]);
    }

}

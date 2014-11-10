<?php

namespace CodeReview\RestBundle\Controller;

use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArtistController extends Controller
{
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('CodeReviewRestBundle:Artist')->find($id);

        if (null === $data) {
            throw new NotFoundHttpException();
        }

        $view = new View($data);

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}

<?php

namespace CodeReview\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArtistController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CodeReviewRestBundle:Default:index.html.twig', array('name' => $name));
    }
}

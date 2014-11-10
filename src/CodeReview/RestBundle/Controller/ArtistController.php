<?php

namespace CodeReview\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ArtistController extends Controller
{
    /**
     * Returns an Artist when given a valid id
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Retrieves an Artist by id",
     *  output = "CodeReview\RestBundle\Entity\Artist",
     *  section="Artists",
     *  statusCodes={
     *         200="Returned when successful",
     *         404="Returned when the requested Artist is not found"
     *     }
     * )
     *
     * @View()
     *
     * @param int   $id     The Artist's id
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em->getRepository('CodeReviewRestBundle:Artist')->find($id);

        if (null === $data) {
            throw new NotFoundHttpException();
        }

        return $data;
    }
}

<?php

namespace CodeReview\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ArtistController extends FOSRestController implements ClassResourceInterface
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
     *
     * @return array
     */
    public function getAction($id)
    {
        return $this->getOr404($id);
    }

    protected function getOr404($id)
    {
        $handler = $this->getHandler();
        $data = $handler->get($id);

        if (null === $data) {
            throw new NotFoundHttpException();
        }

        return $data;
    }

    private function getHandler()
    {
        return $this->get('code_review.rest_bundle.artist_handler');
    }

}

<?php

namespace CodeReview\RestBundle\Controller;

use CodeReview\RestBundle\Entity\Artist;
use CodeReview\RestBundle\Form\ArtistType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ArtistController extends FOSRestController
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
    public function getArtistAction($id)
    {
        return $this->getOr404($id);
    }

    /**
     * @QueryParam(name="limit", requirements="\d+", default="10", description="our limit")
     * @QueryParam(name="offset", requirements="\d+", nullable=true, default="0", description="our offset")
     *
     * @return array
     */
    public function getArtistsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        return $this->getHandler()->all($limit, $offset);
    }


    public function postArtistAction(Request $request)
    {
        $form = $this->createForm(new ArtistType(), new Artist(), array(
            'method'            => 'POST',
            'csrf_protection'   => false,
        ));

        $form->submit($request->request->all());

        if ( ! $form->isValid()) {
            return $form;
        }

        $artist = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($artist);
        $em->flush();

        return $this->redirectView(
            $this->generateUrl('get_artist', array('id'=>$artist->getId())),
            Response::HTTP_CREATED
        );
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

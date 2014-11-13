<?php

namespace CodeReview\RestBundle\Controller;

use CodeReview\RestBundle\Entity\Artist;
use CodeReview\RestBundle\Exception\InvalidFormException;
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

    /**
     * @View()
     *
     * @param Request $request
     * @return array|\FOS\RestBundle\View\View|null
     */
    public function postArtistAction(Request $request)
    {
        try {

            $artist = $this->getHandler()->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id'        => $artist->getId(),
                '_format'    => $request->get('_format'),
            );

            return $this->redirectView(
                $this->generateUrl('get_artist', $routeOptions),
                Response::HTTP_CREATED
            );

        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }


    public function putArtistAction(Request $request, $id)
    {
        $artist = $this->getHandler()->get($id);

        try {

            if ($artist === null) {
                $statusCode = Response::HTTP_CREATED;
                $artist = $this->getHandler()->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Response::HTTP_NO_CONTENT;
                $artist = $this->getHandler()->put(
                    $artist,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id'        => $artist->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_artist', $routeOptions, $statusCode);

        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }


    public function patchArtistAction(Request $request, $id)
    {
        try {

            $artist = $this->getHandler()->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id'        => $artist->getId(),
                '_format'   => $request->get('_format')
            );

            return $this->routeRedirectView('get_artist', $routeOptions, Response::HTTP_NO_CONTENT);

        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
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

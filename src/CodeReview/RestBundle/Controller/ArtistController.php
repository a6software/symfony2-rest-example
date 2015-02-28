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
     * Returns a collection of Artists filtered by optional criteria
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a collection of Artists",
     *  section="Artists",
     *  requirements={
     *      {"name"="limit", "dataType"="integer", "requirement"="\d+", "description"="the max number of records to return"}
     *  },
     *  parameters={
     *      {"name"="limit", "dataType"="integer", "required"=true, "description"="the max number of records to return"},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="the record number to start results at"}
     *  }
     * )
     *
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
     * @ApiDoc(
     *  resource=true,
     *  description="Creates a new Artist",
     *  input = "CodeReview\RestBundle\Form\Type\ArtistFormType",
     *  output = "CodeReview\RestBundle\Entity\Artist",
     *  section="Artists",
     *  statusCodes={
     *         201="Returned when a new Artist has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
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

            return $this->routeRedirectView(
                'get_artist',
                $routeOptions,
                Response::HTTP_CREATED
            );

        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }


    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Replaces an existing Artist",
     *  input = "CodeReview\RestBundle\Form\Type\ArtistFormType",
     *  output = "CodeReview\RestBundle\Entity\Artist",
     *  section="Artists",
     *  statusCodes={
     *         201="Returned when a new Artist has been successfully created",
     *         204="Returned when an existing Artist has been successfully updated",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
     * @param Request $request
     * @param         $id
     * @return array|\FOS\RestBundle\View\View|null
     */
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


    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Updates an existing Artist",
     *  input = "CodeReview\RestBundle\Form\Type\ArtistFormType",
     *  output = "CodeReview\RestBundle\Entity\Artist",
     *  section="Artists",
     *  statusCodes={
     *         204="Returned when an existing Artist has been successfully updated",
     *         400="Returned when the posted data is invalid",
     *         404="Returned when trying to update a non existent Artist"
     *     }
     * )
     *
     * @param Request $request
     * @param         $id
     * @return array|\FOS\RestBundle\View\View|null
     */
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


    /**
     * @ApiDoc(
     *  resource=true,
     *  description="Deletes an existing Artist",
     *  section="Artists",
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="the id of the Artist to delete"}
     *  },
     *  statusCodes={
     *         204="Returned when an existing Artist has been successfully deleted",
     *         404="Returned when trying to delete a non existent Artist"
     *     }
     * )
     *
     * @param Request $request
     * @param         $id
     */
    public function deleteArtistAction(Request $request, $id)
    {
        $artist = $this->getOr404($id);

        $this->getHandler()->delete($artist);
    }

    /**
     * Returns a record by id, or throws a 404 error
     *
     * @param $id
     * @return mixed
     */
    protected function getOr404($id)
    {
        $handler = $this->getHandler();
        $data = $handler->get($id);

        if (null === $data) {
            throw new NotFoundHttpException();
        }

        return $data;
    }

    /**
     * Returns the required handler for this controller
     *
     * @return \CodeReview\RestBundle\Handler\ArtistHandler
     */
    private function getHandler()
    {
        return $this->get('code_review.rest_bundle.artist_handler');
    }
}

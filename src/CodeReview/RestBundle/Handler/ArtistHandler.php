<?php

namespace CodeReview\RestBundle\Handler;

use CodeReview\RestBundle\Entity\Artist;
use CodeReview\RestBundle\Form\Handler\FormHandler;
use CodeReview\RestBundle\Model\ArtistInterface;
use CodeReview\RestBundle\Repository\ArtistRepository;

class ArtistHandler implements HandlerInterface
{
    private $repository;
    private $formHander;

    public function __construct(ArtistRepository $artistRepository, FormHandler $formHandler)
    {
        $this->repository = $artistRepository;
        $this->formHander = $formHandler;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param $limit
     * @param $offset
     * @return array
     */
    public function all($limit, $offset)
    {
        return $this->repository->findBy(array(), array(), $limit, $offset);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function post(array $parameters)
    {
        return $this->formHander->processForm(
            new Artist(),
            $parameters,
            "POST"
        );
    }

    /**
     * @param ArtistInterface $artistInterface
     * @param array           $parameters
     * @return mixed
     */
    public function put(ArtistInterface $artistInterface, array $parameters)
    {
        return $this->formHander->processForm(
            $artistInterface,
            $parameters,
            "PUT"
        );
    }

    /**
     * @param ArtistInterface $artistInterface
     * @param array           $parameters
     * @return mixed
     */
    public function patch(ArtistInterface $artistInterface, array $parameters)
    {
        return $this->formHander->processForm(
            $artistInterface,
            $parameters,
            "PATCH"
        );
    }


    /**
     * @param ArtistInterface $artistInterface
     * @return mixed
     */
    public function delete(ArtistInterface $artistInterface)
    {
        return $this->formHander->delete($artistInterface);
    }


}
<?php

namespace CodeReview\RestBundle\Handler;

use CodeReview\RestBundle\Repository\ArtistRepository;

class ArtistHandler implements HandlerInterface
{
    private $repository;

    public function __construct(ArtistRepository $artistRepository)
    {
        $this->repository = $artistRepository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }
}
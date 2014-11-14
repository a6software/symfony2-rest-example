<?php

namespace CodeReview\RestBundle\Handler;

use CodeReview\RestBundle\Model\ArtistInterface;

interface HandlerInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function all($limit, $offset);

    /**
     * @param array $parameters
     * @return mixed
     */
    public function post(array $parameters);

    /**
     * @param ArtistInterface $artistInterface
     * @param array           $parameters
     * @return mixed
     */
    public function put(ArtistInterface $artistInterface, array $parameters);

    /**
     * @param ArtistInterface $artistInterface
     * @param array           $parameters
     * @return mixed
     */
    public function patch(ArtistInterface $artistInterface, array $parameters);

    /**
     * @param ArtistInterface $artistInterface
     * @return mixed
     */
    public function delete(ArtistInterface $artistInterface);
}
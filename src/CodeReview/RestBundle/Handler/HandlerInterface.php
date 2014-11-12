<?php

namespace CodeReview\RestBundle\Handler;

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
}
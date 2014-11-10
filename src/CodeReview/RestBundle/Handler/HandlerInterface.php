<?php

namespace CodeReview\RestBundle\Handler;

interface HandlerInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function get($id);
}
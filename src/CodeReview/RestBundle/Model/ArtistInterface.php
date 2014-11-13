<?php

namespace CodeReview\RestBundle\Model;

interface ArtistInterface
{
    /**
     * Returns an Artist's name
     *
     * @return mixed
     */
    public function getName();

    /**
     * Returns an Artist's date of birth
     *
     * @return mixed
     */
    public function getDob();
}
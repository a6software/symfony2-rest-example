<?php

namespace CodeReview\RestBundle\DataFixtures\ORM;

use CodeReview\RestBundle\Entity\Artist;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArtistData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $a = new Artist();
        $a->setName('The Beatles');

        $manager->persist($a);
        $manager->flush();

        $this->addReference('artist-1', $a);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 100; // the order in which fixtures will be loaded
    }
}
<?php

use \ApiGuy;

class ArtistControllerCest
{
    public function _before(ApiGuy $i)
    {
    }

    public function _after(ApiGuy $i)
    {
    }

    // tests
    public function getInvalidArtist(ApiGuy $i)
    {
        $i->wantTo('ensure Getting an invalid Artist id returns a 404 code');

        $i->sendGET('/api/artists/23623623.json');
        $i->seeResponseCodeIs(404);
        $i->seeResponseIsJson();
    }

    public function ensureDefaultResponseTypeIsJson(ApiGuy $i)
    {
        $i->sendGET('/api/artists/1');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();
    }

    public function getValidArtist(ApiGuy $i)
    {
        $i->sendGET('/api/artists/1.json');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();

        $i->seeResponseContains(array(
            'name'  => 'The Beatles',
        ));
    }

    public function getSecondValidArtist(ApiGuy $i)
    {
        $i->sendGET('/api/artists/2.json');
        $i->seeResponseCodeIs(200);
        $i->seeResponseIsJson();

        $i->seeResponseContains(array(
            'name'  => 'The Rolling Stones',
        ));
    }
}
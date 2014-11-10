<?php

use Symfony\Component\HttpFoundation\Response;
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

        $i->sendGET(ApiArtistPage::route('23623623.json'));
        $i->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
        $i->seeResponseIsJson();
    }

    public function ensureDefaultResponseTypeIsJson(ApiGuy $i)
    {
        $i->sendGET(ApiArtistPage::route('1'));
        $i->seeResponseCodeIs(Response::HTTP_OK);
        $i->seeResponseIsJson();
    }

    public function getValidArtist(ApiGuy $i)
    {
        foreach ($this->validArtistProvider() as $data) {
            $i->sendGET(ApiArtistPage::route($data[0] . '.json'));
            $i->seeResponseCodeIs(Response::HTTP_OK);
            $i->seeResponseIsJson();

            $i->seeResponseContainsJson($data[1]);
        }
    }

    private function validArtistProvider()
    {
        return array(
            array('1', array(
                "name"  => "The Beatles",
            )),
            array('2', array(
                "name"  => "The Rolling Stones",
            )),
        );
    }
}
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
//    public function getInvalidArtist(ApiGuy $i)
//    {
//        $i->wantTo('ensure Getting an invalid Artist id returns a 404 code');
//
//        $i->sendGET(ApiArtistPage::route('23623623.json'));
//        $i->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
//        $i->seeResponseIsJson();
//    }
//
//    public function ensureDefaultResponseTypeIsJson(ApiGuy $i)
//    {
//        $i->sendGET(ApiArtistPage::route('/1'));
//        $i->seeResponseCodeIs(Response::HTTP_OK);
//        $i->seeResponseIsJson();
//    }
//
//    public function getValidArtist(ApiGuy $i)
//    {
//        foreach ($this->validArtistProvider() as $data) {
//            $i->sendGET(ApiArtistPage::route('/' . $data[0] . '.json'));
//            $i->seeResponseCodeIs(Response::HTTP_OK);
//            $i->seeResponseIsJson();
//
//            $i->seeResponseContainsJson($data[1]);
//        }
//    }
//
//    private function validArtistProvider()
//    {
//        return array(
//            array('1', array(
//                "name"  => "The Beatles",
//            )),
//            array('2', array(
//                "name"  => "The Rolling Stones",
//            )),
//        );
//    }
//
//    public function getArtistsCollection(ApiGuy $i)
//    {
//        $i->sendGET(ApiArtistPage::$URL);
//        $i->seeResponseCodeIs(Response::HTTP_OK);
//        $i->seeResponseIsJson();
//        $i->seeResponseContainsJson(array(
//            array(
//                'id' => 1,
//                "name"  => "The Beatles",
//            ),
//            array(
//                'id' => 2,
//                "name"  => "The Rolling Stones",
//            )
//        ));
//    }
//
//    public function getArtistsCollectionWithLimit(ApiGuy $i)
//    {
//        $i->sendGET(ApiArtistPage::route('?limit=1'));
//        $i->seeResponseCodeIs(Response::HTTP_OK);
//        $i->seeResponseIsJson();
//        $i->seeResponseContainsJson(array(
//            array(
//                'id' => 1,
//                "name"  => "The Beatles",
//            ),
//        ));
//    }
//
//    public function getArtistsCollectionWithOffset(ApiGuy $i)
//    {
//        $i->sendGET(ApiArtistPage::route('?offset=1'));
//        $i->seeResponseCodeIs(Response::HTTP_OK);
//        $i->seeResponseIsJson();
//        $i->seeResponseContainsJson(array(
//            "name"  => "The Rolling Stones"
//        ));
//    }
//
//    public function getArtistsCollectionWithLimitAndOffset(ApiGuy $i)
//    {
//        $i->sendGET(ApiArtistPage::route('?offset=1&limit=3'));
//        $i->seeResponseCodeIs(Response::HTTP_OK);
//        $i->seeResponseIsJson();
//        $i->seeResponseContainsJson(array(
//            "name"  => "The Rolling Stones"
//        ));
//    }

    public function postWithBadFieldsReturns400ErrorCode(ApiGuy $i)
    {
        $i->sendPOST(ApiArtistPage::$URL, array(
            'bad_field' => 'asdsfsdfds'
        ));

        $i->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function goodPostReturns201WithHeader(ApiGuy $i)
    {
        $name = 'My Artist Name';

        $i->sendPOST(ApiArtistPage::$URL, array(
            'name'  => $name
        ));

        $id = $i->grabFromDatabase('artist', 'id', array(
            'name'  => $name
        ));

        $i->seeResponseCodeIs(Response::HTTP_CREATED);
        $i->canSeeHttpHeader('Location', ApiArtistPage::route('/' . $id, true));
    }
}
<?php

use Symfony\Component\HttpFoundation\Response;
use \ApiGuy;

class ArtistControllerCest
{
    public function _before(ApiGuy $i)
    {
        $i->amHttpAuthenticated('rest_user', 'password123');
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
        $i->sendGET(ApiArtistPage::route('/1'));
        $i->seeResponseCodeIs(Response::HTTP_OK);
        $i->seeResponseIsJson();
    }

    public function getValidArtist(ApiGuy $i)
    {
        foreach ($this->validArtistProvider() as $data) {
            $i->sendGET(ApiArtistPage::route('/' . $data[0] . '.json'));
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

    public function getArtistsCollection(ApiGuy $i)
    {
        $i->sendGET(ApiArtistPage::$URL);
        $i->seeResponseCodeIs(Response::HTTP_OK);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(array(
            array(
                'id' => 1,
                "name"  => "The Beatles",
            ),
            array(
                'id' => 2,
                "name"  => "The Rolling Stones",
            )
        ));
    }

    public function getArtistsCollectionWithLimit(ApiGuy $i)
    {
        $i->sendGET(ApiArtistPage::route('?limit=1'));
        $i->seeResponseCodeIs(Response::HTTP_OK);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(array(
            array(
                'id' => 1,
                "name"  => "The Beatles",
            ),
        ));
    }

    public function getArtistsCollectionWithOffset(ApiGuy $i)
    {
        $i->sendGET(ApiArtistPage::route('?offset=1'));
        $i->seeResponseCodeIs(Response::HTTP_OK);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(array(
            "name"  => "The Rolling Stones"
        ));
    }

    public function getArtistsCollectionWithLimitAndOffset(ApiGuy $i)
    {
        $i->sendGET(ApiArtistPage::route('?offset=1&limit=3'));
        $i->seeResponseCodeIs(Response::HTTP_OK);
        $i->seeResponseIsJson();
        $i->seeResponseContainsJson(array(
            "name"  => "The Rolling Stones"
        ));
    }

    public function postWithEmptyFieldsReturns400ErrorCode(ApiGuy $i)
    {
        $i->sendPOST(ApiArtistPage::$URL, array());

        $i->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

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
        $dob = '1911-11-11 10:10:10';

        $i->sendPOST(ApiArtistPage::$URL, array(
            'name'  => $name,
            'dob'   => $dob
        ));

        $id = $i->grabFromDatabase('artist', 'id', array(
            'name'  => $name
        ));

        $i->seeResponseCodeIs(Response::HTTP_CREATED);
        $i->canSeeHttpHeader('Location', ApiArtistPage::route('/' . $id, true, true));
    }


    public function putWithInvalidIdAndInvalidDataReturns400ErrorCode(ApiGuy $i)
    {
        $i->sendPUT(ApiArtistPage::route('/214234.json'), array(
            'sdfdsfsdf' => 'asfsdfsd',
        ));

        $i->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function putWithInvalidIdAndValidDataCreatesNewResourceAndReturns201(ApiGuy $i)
    {
        $name = 'a new neame';
        $dob = '1911-11-11 10:10:10';

        $i->sendPUT(ApiArtistPage::route('/3244234234.json'), array(
            'name'  => $name,
            'dob'   => $dob,
        ));

        $id = $i->grabFromDatabase('artist', 'id', array(
            'name'  => $name
        ));

        $i->seeResponseCodeIs(Response::HTTP_CREATED);
        $i->canSeeHttpHeader('Location', ApiArtistPage::route('/' . $id, true, true));
    }

    public function putWithValidIdAndInvalidDataReturns400ErrorCode(ApiGuy $i)
    {
        $i->sendPUT(ApiArtistPage::route('/2.json'), array(
            'ssdaffsa' => 'cxzvxzc',
        ));

        $i->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function putWithValidIdAndValidDataReplacesExistingDataAndReturns204(ApiGuy $i)
    {
        $name = 'new data';

        $i->sendPUT(ApiArtistPage::route('/2.json'), array(
            'name' => $name,
        ));

        $newName = $i->grabFromDatabase('artist', 'name', array(
            'id'  => 2
        ));

        $i->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $i->canSeeHttpHeader('Location', ApiArtistPage::route('/2', true, true));
        $i->assertEquals($name, $newName);
    }


    public function patchWithInvalidIdReturns404(ApiGuy $i)
    {
        $i->sendPATCH(ApiArtistPage::route('/214234.json'), array(
            'sdfdsfsdf' => 'asfsdfsd',
        ));

        $i->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    public function patchWithValidIdAndInvalidDataReturns400ErrorCode(ApiGuy $i)
    {
        $i->sendPATCH(ApiArtistPage::route('/2.json'), array(
            'sdfdsfsdf' => 'asfsdfsd',
        ));

        $i->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
    }

    public function patchWithValidIdAndValidDataReturns204(ApiGuy $i)
    {
        $originalName = 'The Rolling Stones';
        $dob = new \DateTime('1900-01-01 00:00:00');

        $i->sendPATCH(ApiArtistPage::route('/2.json'), array(
            'dob' => $dob->format('Y-m-d H:i:s'),
        ));

        $newDob = $i->grabFromDatabase('artist', 'dob', array(
            'id'  => 2
        ));

        $existingName = $i->grabFromDatabase('artist', 'name', array(
            'id'  => 2
        ));

        $i->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
        $i->canSeeHttpHeader('Location', ApiArtistPage::route('/2', true, true));
        $i->assertEquals($dob, new \DateTime($newDob));
        $i->assertEquals($originalName, $existingName);
    }


    public function deleteWithInvalidArtistReturns404(ApiGuy $i)
    {
        $i->sendDELETE(ApiArtistPage::route('/60000.json'));

        $i->seeResponseCodeIs(Response::HTTP_NOT_FOUND);
    }

    public function deleteWithValidArtistReturns204(ApiGuy $i)
    {
        $i->seeInDatabase('artist', array(
            'id'    => 1,
        ));

        $i->sendDELETE(ApiArtistPage::route('/1.json'));

        $i->dontSeeInDatabase('artist', array(
            'id'    => 1,
        ));

        $i->seeResponseCodeIs(Response::HTTP_NO_CONTENT);
    }


}
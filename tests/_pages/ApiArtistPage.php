<?php

class ApiArtistPage extends ApiBasePage
{
    // include url of current page
    public static $URL = '/api/artists';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
    * Basic route example for your current URL
    * You can append any additional parameter to URL
    * and use it in tests like: EditPage::route('/123-post');
    */
    public static function route($param, $withEnvironment = false, $withDomain = false)
    {
        $route = static::$URL.$param;

        if ($withEnvironment) {
            $route = self::$ENVIRONMENT . $route;
        }

        if ($withDomain) {
            $route = self::$DOMAIN . $route;
        }

        return $route;
    }
}
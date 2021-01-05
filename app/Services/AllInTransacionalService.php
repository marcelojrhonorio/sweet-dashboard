<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Log;

class AllInTransacionalService
{
    /**
     * instance of the default values
     */
    private static $_allindata;

    public function __construct()
    {
        self::$_allindata = [
            "base_uri" => env('ALLIN_BASE_URI','https://transacional.allin.com.br/api'),
            'method'   => 'get_token',
            'output'   => 'json',
            'username' => 'sweetbonus_tallinapi',
            'password' => 'U0Y1udUP',
        ];
    }

    private static function getClient()
    {
        return new Client(['base_uri' => self::$_allindata['base_uri']]);
    }

    private static function prepareUrlQuery()
    {
        $params = [
            'method' => self::$_allindata['method'],
            'output' => self::$_allindata['output'],
            'username' => self::$_allindata['username'],
            'password' => self::$_allindata['password'],
        ];

        return urldecode(http_build_query($params));
    }

    private static function callAllIn()
    {
        $client = self::getClient();
        try {
            $response = $client->get('?' . self::prepareUrlQuery());
            return json_decode($response->getBody()->getContents());
        } catch (RequestException $e) {
            Log::debug("Request Expection , request ->" . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::debug("Request Expection ->" . Psr7\str($e->getResponse()));
            }
        } catch (ConnectException $e) {
            Log::debug("Connection expection, request ->" . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::debug("Connection expection, response ->" . Psr7\str($e->getResponse()));
            }
        } catch (ClientException $e) {
            Log::debug("Client expection, request ->" . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::debug("Client expection, response ->" . Psr7\str($e->getResponse()));
            }
        } catch (BadResponseException $e) {
            Log::debug("Bad Response, request ->" . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::debug("Bad Response, response ->" . Psr7\str($e->getResponse()));
            }
        }
        return null;
    }

    public static function getToken()
    {
        $json = self::callAllIn();

        if (null !== $json && property_exists($json, 'token')) {
            return $json->token;
        }
        return '';
    }
}

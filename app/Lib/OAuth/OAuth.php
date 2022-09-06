<?php

namespace App\Lib\OAuth;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

abstract class OAuth
{

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $openId;

    public function __construct($clientId, $clientSecret, $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    public function httpGet($uri, $params = [], $headers = [])
    {
        $client = new HttpClient();

        $options = ['query' => $params, 'headers' => $headers];

        $response = $client->get($uri, $options);

        return $response->getBody();
    }

    public function httpPost($uri, $params = [], $headers = [])
    {
        $client = new HttpClient();

        $options = ['query' => $params, 'headers' => $headers];

        $response = $client->post($uri, $options);

        return $response->getBody();
    }

    public function getState()
    {
        $text = rand(1000, 9999);
        return Crypt::encryptString($text);
    }

    public function checkState($state)
    {

        try {
            $decrypted = Crypt::decryptString($state);
            if ($decrypted < 1000 || $decrypted > 9999) {
                throw new \Exception('Invalid OAuth State Value');
            }
            return true;

        } catch (DecryptException $e) {
            //
            throw new \Exception('Invalid OAuth State Value');
        }
    }

    abstract public function getAuthorizeUrl();

    abstract public function getAccessToken($code);

    abstract public function getOpenId($accessToken);

    abstract public function getUserInfo($accessToken, $openId);

}

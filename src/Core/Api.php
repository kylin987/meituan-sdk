<?php


namespace Hanson\Meituan\Core;


use function GuzzleHttp\Psr7\parse_query;
use Hanson\Foundation\AbstractAPI;
use Hanson\Foundation\Log;
use Hanson\Meituan\AccessToken\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Api extends AbstractAPI
{

    /**
     * @var AccessToken
     */
    public $accessToken;

    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param $method
     * @param $params
     * @param array $files
     * @return array
     */
    public function request($method, $params, $files = [])
    {
        $http = $this->getHttp();

        $url = $params[0];

        $params = array_merge($params[1], [
            'charset' => 'UTF-8',
            'timestamp' => time(),
        ]);

        $tokenArray = $this->accessToken->getAuthToken() ? ['appAuthToken' => $this->accessToken->getAuthToken()] : [];

        $params = $tokenArray ? array_merge($params, $tokenArray) : $params;

        $params['sign'] = $this->accessToken->signature($params);

        /** @var ResponseInterface $response */
        $response = call_user_func_array([$http, $method], [$url, $params, $files]);

        return json_decode(strval($response->getBody()), true);
    }

    /**
     * Push guzzle middleware before request.
     *
     * @return mixed
     */
    public function middlewares()
    {
        // TODO: Implement middlewares() method.
    }
}
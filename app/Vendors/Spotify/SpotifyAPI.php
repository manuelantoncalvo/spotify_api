<?php

namespace App\Vendors\Spotify;

use App\Vendors\Spotify\Exceptions\SpotifyAPIException;
use Exception;
use GuzzleHttp\Client;

class SpotifyAPI
{
    const API_URL = 'https://api.spotify.com';
    const ACCOUNTS_URL = 'https://accounts.spotify.com';
    const TOKEN = '/api/token';

    protected $requestParams = [];
    protected $baseUri;
    protected $response;
    protected $requestMethod = 'GET';
    protected $uri;
    protected $checkEnable = true;
    protected $responseHeaders;
    private $headers = [
        'Accept' => 'application/json',
    ];
    /**
     * @var string
     */
    protected $accessToken;

    public function setUri(string $uri): self
    {
        $this->uri = '/' . ltrim(rtrim($uri, '/'), '/');
        return $this;
    }

    public function setBaseUri(string $base_uri): self
    {
        $this->baseUri = rtrim($base_uri);
        return $this;
    }

    public function setRequestMethod(string $method): self
    {
        $this->requestMethod = strtoupper($method);
        return $this;
    }

    public function getTokenWithCredentials(string $clientId, string $clientSecret): ClientCredentialsToken
    {
        $this->setBaseUri(self::ACCOUNTS_URL)
            ->setUri(self::TOKEN)
            ->setRequestMethod('POST')
            ->setAuthParams([$clientId, $clientSecret]);

        $response = $this->setFormParams([
            'grant_type' => 'client_credentials',
        ])->getResult();

        if (!isset($response->access_token)) {
            throw new SpotifyAPIException('Access token missing in response');
        }

        $clientToken = new ClientCredentialsToken((array)$response);
        $this->setAccessToken($clientToken->access_token);
        return $clientToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;
        $this->setHeaders(['Authorization' => 'Bearer ' . $this->accessToken]);
        return $this;
    }

    public function setHeaders($headers): self
    {
        if ($headers === null) {
            $this->headers = [];
            return $this;
        }
        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                $this->headers[$key] = $value;
            }
        }
        return $this;
    }

    public function getResult()
    {
        return $this->sendRequest()->getResponse();
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function getBaseUri(): string
    {
        return $this->baseUri;
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    protected function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    protected function getUri(): string
    {
        return $this->uri;
    }

    protected function getRequestParams(): array
    {
        return $this->requestParams;
    }

    public function login(): ClientCredentialsToken
    {
        return $this->getTokenWithCredentials(
            env('SPOTIFY_CLIENT_ID'),
            env('SPOTIFY_CLIENT_SECRET')
        );
    }

    public function sendRequest(): self
    {
        $client = new Client(['base_uri' => $this->getBaseUri(), 'headers' => $this->getHeaders()]);
        $response = $client->request($this->getRequestMethod(), $this->getUri(), $this->getRequestParams());
        $body = $response->getBody();
        $this->setResponseHeaders($response->getHeaders());
        $this->response = $this->parseRawResponse((string)$body);
        return $this;
    }

    protected function parseRawResponse($rawResponseBody)
    {
        $decodedResponse = json_decode($rawResponseBody);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('The response is not valid json');
        }
        return $decodedResponse;
    }

    protected function setResponseHeaders($headers)
    {
        $this->responseHeaders = $headers;
        return $this;
    }

    public function setAuthParams($params): self
    {
        $this->requestParams['auth'] = $params;
        return $this;
    }

    protected function setFormParams(array $params): self
    {
        $this->requestParams['form_params'] = $params;
        return $this;
    }

    protected function setQueryParams(array $value): self
    {
        $this->requestParams['query'] = $value;
        return $this;
    }

    protected function clearRequestParams(): self
    {
        $this->requestParams = [];
        return $this;
    }
    
    protected function hasAuthorizationToken(): bool
    {
        return isset($this->getHeaders()['Authorization']);
    }
    
    protected function checkClientCredentials(): self
    {
        if(!$this->hasAuthorizationToken()) {
            $this->login();
        }
        return $this;
    }

    public function search(SearchParam $searchParam)
    {
        $this->checkClientCredentials()
            ->clearRequestParams()
            ->setQueryParams($searchParam->getQueryParams())
            ->setRequestMethod($searchParam->requestMethod)
            ->setBaseUri(self::API_URL)
            ->setUri($searchParam->uri);
        return $this->getResult();
    }
}

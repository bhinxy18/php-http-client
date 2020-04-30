<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi;

use CoreDNA\PublicApi\AbstractGateway;

class ServiceGateway extends AbstractGateway
{
     
    /**
     * __construct
     *
     * @param  string $endpoint This is the endpoint URI
     * @return void
     */
    public function __construct(
        string $endpoint
    ) {
        $this->endpoint = $endpoint;
        if (!$this->token) {
            $this->token = $this->requestToken();
        }
    }
    
    /**
     * Gets the token from the endpoint.
     *
     * @return string
     */
    public function requestToken(): ?string
    {
        $options = [
            'http' => [
                'method' => 'OPTIONS',
                'header' => $this->buildHeaders()
            ]
        ];
        $context = stream_context_create($options);
        
        try {
            $token = file_get_contents($this->endpoint, false, $context);

            $parseHeaders = $this->parseHeaders($http_response_header);
            if (!$token || (!empty($parseHeaders) && !$this->isResponseCodeValid($parseHeaders['reponse_code']))) {
                throw new \Exception("Unable to update data");
            }
        } catch (\Exception $ex) {
            throw new \Exception("Exception caught while attempting OPTIONS request to $uri. Message: " . $ex->getMessage());
        }

        return $token;
    }
    
    /**
     * Gets data from the endpoint based on parameters.
     *
     * @param  string $resource
     * @param  array  $parameters
     * @return array
     */
    public function get(string $resource, array $parameters): array
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => $this->buildHeaders()
            ]
        ];
        $context = stream_context_create($options);
        $uri = $this->buildEndpointUri($resource, $parameters);
        
        try {
            $response = file_get_contents($uri, false, $context);
            
            $parseHeaders = $this->parseHeaders($http_response_header);
            if ($response === false || (!empty($parseHeaders) && !$this->isResponseCodeValid($parseHeaders['reponse_code']))) {
                throw new \Exception("Unable to update data");
            }
        } catch (\Exception $ex) {
            throw new \Exception("Error with GET request to $uri. Message: " . $ex->getMessage());
        }
        
        return $this->decodeResponse($response);
    }
    
    /**
     * Sends data via POST request.
     *
     * @param  string $resource
     * @param  array  $parameters
     * @param  string $payload
     * @return bool
     */
    public function post(string $resource, array $parameters, string $payload): ?bool
    {
        $options = [
        'http' => [
        'method' => 'POST',
        'content' => $this->buildParameterList($parameters),
                'header' => $this->buildHeaders($payload),
                'content' => $payload
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];

        $context = stream_context_create($options);
        $uri = $this->buildEndpointUri($resource, $parameters);
        
        try {
            $response = file_get_contents($uri, false, $context);

            $parseHeaders = $this->parseHeaders($http_response_header);
            if ($response === false || (!empty($parseHeaders) && !$this->isResponseCodeValid($parseHeaders['reponse_code']))) {
                throw new \Exception("Unable to update data");
            }
        } catch (\Exception $ex) {
            throw new \Exception("Error with POST request to $uri. Message: " . $ex->getMessage());
        }
        
        return true;
    }
}

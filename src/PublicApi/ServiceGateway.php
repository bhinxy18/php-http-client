<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi;

use CoreDNA\PublicApi\AbstractGateway;

class ServiceGateway extends AbstractGateway
{
    public function __construct($endpoint)
    {
        parent::__construct();

        $this->endpoint = $endpoint;
        $this->token = $this->getToken();        
    }

    public function getToken()
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
        } catch(\Exception $ex) {
            throw new \Exception("Exception caught while attempting OPTIONS request to $uri. Message: " . $ex->getMessage());
        }

        return $token;
    }

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
        } catch(\Exception $ex) {
            throw new \Exception("Error with GET request to $uri. Message: " . $ex->getMessage());
        }
        
        return $this->decodeResponse($response);
    }

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
        } catch(\Exception $ex) {
            throw new \Exception("Error with POST request to $uri. Message: " . $ex->getMessage());
        }
        
        return true;
    }
}
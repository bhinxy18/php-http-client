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
    }

    public function get(string $resource, array $parameters)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/json', 
                    'Content-Type: application/json'
                ] 
            ]
        ];
        $context = stream_context_create($options);
        $uri = $this->buildEndpointUri($resource, $parameters);
        
        try {
            $response = file_get_contents($uri, false, $context);
        } catch(Exception $ex) {
            throw new Exception("Exception caught while attempting GET to $uri. Message: " . $ex->getMessage());
        }
        
        return $this->decodeResponse($response);
    }

    public function post(string $resource, array $parameters)
    {
        $content = $this->buildParameterList($parameters);
        $header = array(
			"Content-Type: application/x-www-form-urlencoded",
			"Content-Length: " . strlen($content)
		);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'content' => $content,
				'header' => implode("\r\n", $header)
			)
			
        );
        $context = stream_context_create($options);
        $uri = $this->buildEndpointUri($resource, $parameters);
        
        try {
            $response = file_get_contents($uri, false, $context);
        } catch(Exception $ex) {
            throw new Exception("Exception caught while attempting GET to $uri. Message: " . $ex->getMessage());
        }
        
        return $this->decodeResponse($response);
    }

    private function buildEndpointUri(string $resource, array $parameters): string
    {
        return $this->getEndpoint() . $resource . $this->buildParameterList($parameters);
    }

    private function buildParameterList(array $parameters): string
    {
        if (!count($parameters)) {
            return '';
        }

        return '?' . http_build_query($parameters);
    }

    private function decodeResponse($response): array
    {
        var_dump($response);

        return [];
    }
}
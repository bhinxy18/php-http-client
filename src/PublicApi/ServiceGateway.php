<?php

/**
 * ServiceGateway Class
 *
 * PHP version 7
 *
 * @category Class
 * @package  Class
 * @author   Ruby <ruby.lamadora@gmail.com>
 * @license  https://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.github.com/bhinxy18/php-http-client
 */

declare(strict_types=1);

namespace CoreDNA\PublicApi;

use CoreDNA\PublicApi\AbstractGateway;

/**
 * ServiceGateway Class
 *
 * @category Class
 * @package  Class
 * @author   Ruby <ruby.lamadora@gmail.com>
 * @license  https://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.github.com/bhinxy18/php-http-client
 */
class ServiceGateway extends AbstractGateway
{
     
    /**
     * __construct
     *
     * @param  string $endpoint This is the endpoint URI
     * @return void
     */
    public function __construct(string $endpoint)
    {
        parent::__construct();

        $this->endpoint = $endpoint;
        $this->token = $this->getToken();
    }
    
    /**
     * Gets the token from the endpoint.
     *
     * @return string
     */
    public function getToken(): ?string
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

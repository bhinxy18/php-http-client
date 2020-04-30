<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi;

abstract class AbstractGateway
{
    const RESPONSE_CODES = [200, 201, 202, 304, 401, 404];
    
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $token;

    abstract public function get(string $resource, array $parameters);
    abstract public function post(string $resource, array $parameters, string $payload);

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
    
    /**
     * Checks if Response code is in Valid list.
     *
     * @param  int $code
     * @return bool
     */
    public function isResponseCodeValid(int $code): bool
    {
        if (in_array($code, self::RESPONSE_CODES)) {
            return true;
        }

        return false;
    }
    
    /**
     * Builds headers for API request.
     *
     * @param  string $json_data
     * @return array
     */
    public function buildHeaders(string $json_data = ''): array
    {
        return [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
            'Content-length: ' . strlen($json_data)
        ];
    }
    
    /**
     * Builds endpoint URI.
     *
     * @param  string $resource
     * @param  array $parameters
     * @return string
     */
    public function buildEndpointUri(string $resource, array $parameters): string
    {
        return $this->getEndpoint() . $resource . $this->buildParameterList($parameters);
    }
    
    /**
     * Builds Parameters into query string.
     *
     * @param  array $parameters
     * @return string
     */
    public function buildParameterList(array $parameters): string
    {
        if (!count($parameters)) {
            return '';
        }

        return '?' . http_build_query($parameters);
    }
    
    /**
     * Decodes response so it is more readable.
     *
     * @param  string $response
     * @return array
     */
    public function decodeResponse(string $response): array
    {
        // We can add in conditions here based on response data

        return json_decode($response);
    }
    
    /**
     * Parses header to easily manipulate response status.
     *
     * @param  array $headers
     * @return array
     */
    public function parseHeaders(array $headers = []): array
    {
        $formatted = [];
        if (!count($headers)) {
            return $formatted;
        }
        
        foreach ($headers as $k => $v) {
            $httpResponse = explode(':', $v, 2);
            if (isset($httpResponse[1])) {
                $formatted[trim($httpResponse[0])] = trim($httpResponse[1]);
            } else {
                $formatted[] = $v;
                if (preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $v, $out)) {
                    $formatted['reponse_code'] = intval($out[1]);
                }
            }
        }

        return $formatted;
    }
}

<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi;

abstract class AbstractGateway
{
    const RESPONSE_CODES = [200, 201, 202, 304, 401, 404];
    
    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $token;

    public function __construct()
    {
    }

    abstract public function get(string $resource, array $parameters);
    abstract public function post(string $resource, array $parameters, string $payload);

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endpoint;
    }

    public function isResponseCodeValid(int $code): bool
    {
        if (in_array($code, self::RESPONSE_CODES)) {
            return true;
        }

        return false;
    }

    public function buildHeaders(string $json_data = ''): array
    {
        return [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json',
            'Content-length: ' . strlen($json_data)
        ];
    }

    public function buildEndpointUri(string $resource, array $parameters): string
    {
        return $this->getEndpoint() . $resource . $this->buildParameterList($parameters);
    }

    public function buildParameterList(array $parameters): string
    {
        if (!count($parameters)) {
            return '';
        }

        return '?' . http_build_query($parameters);
    }

    public function decodeResponse($response): array
    {
        // We can add in conditions here based on response data

        return json_decode($response);
    }

    public function parseHeaders(array $headers=[])
    {
        $formatted = [];
        if (!count($headers)) {
            return $formatted;
        }
        
        foreach($headers as $k => $v) {
            $httpResponse = explode(':', $v, 2);
            if(isset($httpResponse[1])) {
                $formatted[trim($httpResponse[0])] = trim($httpResponse[1]);
            }
            else {
                $formatted[] = $v;
                if(preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out)) {
                    $formatted['reponse_code'] = intval($out[1]);
                }
            }
        }

        return $formatted;
    }
}

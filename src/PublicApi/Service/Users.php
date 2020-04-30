<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi\Service;

use CoreDNA\PublicApi\ServiceGateway;
use CoreDNA\PublicApi\Model\User;

class Users
{
    const RESOURCE = '/employee';

    /** @var ServiceGateway */
    private $gateway;

    public function __construct(
        ServiceGateway $gateway
    )
    {
        $this->gateway = $gateway;
    }

    public function fetch($userId): User
    {
        $uri = self::RESOURCE . '/' . $userId;        
        
        try {
            $response = $this->gateway->get($uri, []);

            return $this->hydrate($response);
        } catch(Exception $ex) {
            throw $ex;
        }
        
        return json_decode($response);
    }

    public function create($parameters): User
    {
        $uri =  '/create';
        
        try {
            $response = $this->gateway->post($uri, $parameters);

            return $response;
        } catch(Exception $ex) {
            throw $ex;
        }
        
        return json_decode($response);
    }

    private function hydrate(array $data): User
    {
        $userModel = new User();
        $userModel->setName($data['name'] ?? '');
        $userModel->setEmail($data['email'] ?? '');
        $userModel->setUrl($data['url'] ?? '');

        return $userModel;
    }
}
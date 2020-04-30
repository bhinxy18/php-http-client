<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi\Service;

use CoreDNA\PublicApi\ServiceGateway;
use CoreDNA\PublicApi\Model\User;

class Users
{
    const RESOURCE = '';

    /**
     * @var ServiceGateway
     */
    private $gateway;

    public function __construct(
        ServiceGateway $gateway
    ) {
        $this->gateway = $gateway;
    }

    public function fetch($userId): User
    {
        $uri = self::RESOURCE . '/' . $userId ?? '';
        try {
            $response = $this->gateway->get($uri, []);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        return $this->hydrate($response);
    }

    public function create(User $user): ?bool
    {
        $uri =  '';
        // convert user model to json format
        $payload = $this->serialize($user);
        try {
            $response = $this->gateway->post($uri, [], $payload);
        } catch (Exception $ex) {
            throw $ex;
        }
        
        return $response;
    }

    private function hydrate(array $data): User
    {
        $userModel = new User();
        $userModel->setName($data['name'] ?? '');
        $userModel->setEmail($data['email'] ?? '');
        $userModel->setUrl($data['url'] ?? '');

        return $userModel;
    }

    private function serialize(User $user): string
    {
        $userArr = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'url' => $user->getUrl()
        ];

        return json_encode($userArr);
    }
}

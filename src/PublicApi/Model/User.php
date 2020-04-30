<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi\Model;

class User
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $url = '';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name = ''): User
    {
        $this->name = $name;
        
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email = ''): User
    {
        $this->email = $email;
        
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url = ''): User
    {
        $this->url = $url;
        
        return $this;
    }
}

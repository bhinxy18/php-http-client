<?php

declare(strict_types=1);

namespace CoreDNA\PublicApi;

abstract class AbstractGateway
{
    /** @var string */
    protected $endpoint;

    public function __construct()
    {

    }

    abstract public function get(string $resource, array $parameters);

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->endpoint;
    }
}

<?php

namespace App\Factory;

use App\Service\Translator\TranslatorInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TranslatorFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(string $translatorName): ?TranslatorInterface
    {
        if ($this->container->has($translatorName)) {
            return $this->container->get($translatorName);
        }

        return null;
    }
}
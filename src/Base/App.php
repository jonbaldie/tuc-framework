<?php

namespace Tuc\Base;

use Exception;
use Slim\App as SlimApp;
use Tuc\Providers\Provider;

class App extends SlimApp
{
    /**
     * @var string
     */
    protected $dir;

    /**
     * @var Provider[]
     */
    protected $registered_providers = [];

    /**
     * @var object[]
     */
    protected $singletons = [];

    /**
     * @param $dir
     * @return void
     */
    public function setContext(string $dir): void
    {
        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->dir;
    }

    /**
     * @param Provider $provider
     * @return void
     */
    public function registerProvider(Provider $provider): void
    {
        if ($this->registered($provider)) {
            throw new Exception("Provider has already been registered.");
        }

        $provider->boot($this);
        $this->registered_providers[get_class($provider)] = $provider;
    }

    /**
     * @param Provider $provider
     * @return void
     */
    public function registered(Provider $provider): bool
    {
        return isset($this->registered_providers[get_class($provider)]);
    }

    /**
     * @param Provider[]|string[] $providers
     * @return void
     */
    public function registerProviders(array $providers): void
    {
        foreach ($providers as $provider) {
            if (!$provider instanceof Provider) {
                $provider = $this->make($provider);
            }

            $this->registerProvider($provider);
        }
    }

    /**
     * @param $mixed
     * @return mixed
     */
    public function make($mixed)
    {
        if (isset($this->singletons[$mixed])) {
            return $this->singletons[$mixed];
        }

        return $this->getContainer()->get($mixed);
    }

    /**
     * @param $mixed
     * @param $concretion
     */
    public function singleton($mixed, $concretion = null)
    {
        if ($concretion !== null) {
            $this->singletons[$mixed] = $this->make($concretion);

            return $this;
        }

        return $this->singletons[$mixed];
    }
}

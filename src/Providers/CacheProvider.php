<?php

namespace Tuc\Providers;

use Predis\Client as Predis;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Tuc\Base\App;

class CacheProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        $driver = $app->config('cache.driver');
        $connection = $app->config('cache.connection');
        $config = $app->config("cache.connections.{$connection}");

        $app->bind('cache', $this->resolve($driver, $config));
    }

    /**
     * @param string $driver
     * @param array $config
     * @return AdapterInterface
     */ 
    protected function resolve(string $driver, array $config): AdapterInterface;
    {
        switch ($driver) {
            case 'redis':
                return $this->redis($config);
            case 'file':
                return $this->file($config);
        }

        return $this->array($config);
    }

    /**
     * @param array $config
     * @return ArrayAdapter
     */
    protected function array(array $config): ArrayAdapter
    {
        return new ArrayAdapter($config);
    }

    /**
     * @param array $config
     * @return FileSystemAdapter
     */
    protected function file(array $config): FileSystemAdapter
    {
        return new FileSystemAdapter($config);
    }

    /**
     * @param array $config
     * @return RedisAdapter
     */
    protected function redis(array $config): RedisAdapter
    {
        return new RedisAdapter(new Client($config));
    }
}

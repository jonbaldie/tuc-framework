<?php

namespace Tuc\Providers;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver as Driver;
use Tuc\Base\App;
use Tuc\Data\Model;

class DataProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        $config = new Configuration;

        foreach ($app->config('data.connections') as $name => $connection) {
            $connections[$name] = $this->resolveParams($connection, $config);

            if ($name === $app->config('data.default')) {
                $conn = $connections[$name];
            }
        }

        // Bind these to the container so they can be easily accessed.
        $app->bind('dbs', $connections[$name]);

        // If no default connection is configured, we have no need to proceed.
        if ($conn === null) {
            return;
        }
        
        /**
         * This project starts you off with the Doctrine ORM for
         * handling database models. We're using the StaticPHPDriver,
         * which needs you to put table metadata into a static function
         * on your model classes.
         *
         * https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/reference/php-mapping.html#static-function
         */
        $em = EntityManager::create($conn, $config);
        $driver = new Driver($app->getContext() . $app->config('data.models.location'));
        $em->getConfiguration()->setMetadataDriverImpl($driver);

        // Tell the models which EntityManager to use by default
        Model::setEntityManager($em);

        // Now bind these into the application so they can be accessed later
        $app->bind('db', $conn);
        $app->bind('em', $em);
    }

    /**
     * @param array $connection
     * @param Configuration $config
     * @return Connection
     */
    protected function resolveParams(array $connection, Configuration $config): Connection
    {
        if (isset($connection['url'])) {
            $params = [
                'url' => $connection['url']
            ];
        } else {
            $params = [
                'dbname' => $connection['dbname'],
                'user' => $connection['user'],
                'password' => $connection['password'],
                'host' => $connection['host'],
                'driver' => $connection['driver'],
            ];
        }

        return DriverManager::getConnection($params, $config);
        
    }
}

<?php

use PHPUnit\Framework\TestCase;
use Tuc\Base\App;
use Tuc\Base\AppFactory;
use Tuc\Base\Config;
use Tuc\Base\Container;
use Tuc\Providers\AuthProvider;
use Tuc\Providers\EnvProvider;

class BaseTest extends TestCase
{
    public function testMakingApp()
    {
        $app = AppFactory::create();
        $this->assertInstanceOf(App::class, $app);
    }

    public function testAppContext()
    {
        $app = AppFactory::create();
        $app->setContext('/tmp');
        $this->assertEquals($app->getContext(), '/tmp');
    }

    public function testRegisteringProvider()
    {
        $app = AppFactory::create();
        $app->registerProvider($p = new AuthProvider);
        $this->assertTrue($app->registered($p));
    }

    public function testRegisteringProviders()
    {
        AppFactory::setContainer(new Container);
        $app = AppFactory::create();
        $app->setContext('/tmp');
        $app->registerProviders([AuthProvider::class]);
        $this->assertTrue($app->registered(new AuthProvider));
    }

    public function testBindClass()
    {
        AppFactory::setContainer(new Container);
        $app = AppFactory::create();
        $app->bind(AuthProvider::class, $a = new AuthProvider);
        $this->assertSame($a, $app->make(AuthProvider::class));
    }

    public function testBindClosure()
    {
        AppFactory::setContainer(new Container);
        $app = AppFactory::create();
        $app->bind('foo', function () {
            return 'bar';
        });
        $this->assertEquals('bar', $app->make('foo'));
    }
    
    public function testMakeObject()
    {
        AppFactory::setContainer(new Container);
        $app = AppFactory::create();
        $a = $app->make(AuthProvider::class);
        $this->assertInstanceOf(AuthProvider::class, $a);     
    }

    public function testConfig()
    {
        $config = new Config;
        $config->register('foo', [
            'yah' => [
                'yee',
            ],
        ]);
        $this->assertEquals('yee', $config->get('foo.yah.0'));
    }

    public function testAppConfig()
    {
        AppFactory::setContainer(new Container);
        $app = AppFactory::create();
        $config = new Config;
        $config->register('foo', [
            'bar' => 'bat',
        ]);
        $app->setConfig($config);
        $this->assertEquals('bat', $app->config('foo.bar'));
    }
}


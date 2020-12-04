<?php

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testGeneratingKeys()
    {
        $service = new Tuc\Auth\TwoFactorService(new Da\TwoFA\Manager);
        $key = $service->generateSecret();
        $this->assertNotEmpty($key);
    }

    public function testGeneratingKeysOfSpecificLength()
    {
        $service = new Tuc\Auth\TwoFactorService(new Da\TwoFA\Manager);
        $key = $service->generateSecret(16);
        $this->assertTrue(strlen($key) === 16);
    }
}

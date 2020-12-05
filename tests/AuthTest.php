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

    public function testGeneratingTotpUri()
    {
        $service = new Tuc\Auth\TwoFactorService(new Da\TwoFA\Manager);
        $key = $service->generateSecret();
        $uri = $service->totpUri('James Appleseed', 'james@example.com', $key);
        $this->assertNotEmpty($uri);
    }

    public function testGeneratingQrCodeUri()
    {
        $service = new Tuc\Auth\TwoFactorService(new Da\TwoFA\Manager);
        $key = $service->generateSecret();
        $uri = $service->totpUri('James Appleseed', 'james@example.com', $key);
        $qr = $service->qrCodeUri($uri);
        $this->assertNotEmpty($qr);
    }

    public function testGeneratingGoogleQrCodeUrl()
    {
        $service = new Tuc\Auth\TwoFactorService(new Da\TwoFA\Manager);
        $key = $service->generateSecret();
        $uri = $service->totpUri('James Appleseed', 'james@example.com', $key);
        $google = $service->googleQrCodeUrl($uri);
        $this->assertNotEmpty($google);
    }
}

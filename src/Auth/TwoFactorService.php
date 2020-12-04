<?php

namespace Tuc\Auth;

use Da\TwoFA\Manager;
use Da\TwoFA\Service\TOTPSecretKeyUriGeneratorService as KeyUri;
use Da\TwoFA\Service\GoogleQrCodeUrlGeneratorService as Google;
use Da\TwoFA\Service\QrCodeDataUriGeneratorService as QrUri;

class TwoFactorService
{
    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param int $key_length
     * @return string
     */
    public function generateSecret(int $key_length = 32): string
    {
        return $this->manager->generateSecretKey($key_length);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $secret
     * @return string
     */
    public function totpUri(string $name, string $email, string $secret): string
    {
        return (new KeyUri($name, $email, $secret))->run();
    }

    /**
     * @param string $totp_uri
     * @return string
     */
    public function qrCodeUri(string $totp_uri): string
    {
        return (new QrUri($totp_uri))->run();
    }

    /**
     * @param string $totp_uri
     * @return string
     */
    public function googleQrCodeUrl(string $totp_uri): string
    {
        return (new Google($totp_uri))->run();
    }

    /**
     * @param string $posted_key
     * @param string $stored_key
     * @return bool
     */
    public function verify(string $posted_key, string $stored_key): bool
    {
        return (bool)$this->manager->verify($posted_key, $stored_key);
    }
}

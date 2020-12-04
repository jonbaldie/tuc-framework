<?php

namespace Tuc\Providers;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Tuc\Base\App;

class MailProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        if ($app->config('mail.sendmail')) {
            $app->bind('sendmail', new Mailer(Transport::fromDsn('sendmail://default')));
        }

        if ($smtp = $app->config('mail.smtp')) {
            $dsn = $smtp['dsn'] ?: sprintf('smtp://%s:%s@%s:%s', ...[
                $smtp['user'],
                $smtp['pass'],
                $smtp['host'],
                $smtp['port'],
            ]);

            $app->bind('smtp', new Mailer(Transport::fromDsn($dsn)));
        }

        if ($ses = $app->config('mail.ses')) {
            $dsn = $ses['dsn'] ?: sprintf('ses+https://%s:%s@default', ...[
                urlencode($ses['aws_access_key_id']),
                urlencode($ses['aws_secret_key']),
            ]);

            $app->bind('ses', new Mailer(Transport::fromDsn($dsn)));
        }

        if ($default = $app->config('mail.default') && $app->bound($default)) {
            $app->bind('mail', $app->make($default));
        }

        // https://symfony.com/doc/current/mailer.html#signing-and-encrypting-messages
        if ($signing = $app->config('mail.signing')) {
            $signer = new SMimeSigner($signing['cert'], $signing['key']);

            $app->bind('signer', $signer);
        }
    }
}

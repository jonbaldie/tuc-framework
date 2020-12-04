<?php

namespace Tuc\Providers;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Tuc\Base\App;

class FilesProvider implements Provider
{
    /**
     * @param App $app
     *
     * @return void
     */
    public function boot(App $app): void
    {
        $local = new LocalFilesystemAdapter($app->config('files.local'));

        $app->bind('storage', new FileSystem($local));

        if ($app->config('files.s3')) {
            if ($app->config('files.s3.async')) {
                $s3 = new AsyncAwsS3Adapter(
                    new SimpleS3Client($app->config('files.s3.options')),
                    $app->config('files.s3.bucket'),
                    $app->config('files.s3.prefix')
                );
            } else {
                $s3 = new AwsS3V3Adapter(
                    new S3Client($app->config('files.s3.options')),
                    $app->config('files.s3.bucket'),
                    $app->config('files.s3.prefix')
                );
            }

            $app->bind('s3', new FileSystem($s3));
        }
    }
}

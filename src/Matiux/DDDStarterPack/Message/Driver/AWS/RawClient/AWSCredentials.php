<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\RawClient;

use Aws\Credentials\Credentials;
use DDDStarterPack\Tool\EnvVarUtil;

trait AWSCredentials
{
    private function createCredentials(): array
    {
        $accessKey = EnvVarUtil::get('AWS_ACCESS_KEY_ID');
        $secretKey = EnvVarUtil::get('AWS_SECRET_ACCESS_KEY');
        $sessionToken = EnvVarUtil::get('AWS_SESSION_TOKEN');

        if (!empty($accessKey) && !empty($secretKey) && !empty($sessionToken)) {
            $credentials = new Credentials($accessKey, $secretKey, $sessionToken);

            return ['credentials' => $credentials];
        }

        return [];
    }
}

<?php


namespace Cexll\Hyperf\Apihash;


class ConfigProvider
{
    public function __invoke()
    {
        return [
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The RSA public and private certificate path is configured here',
                    'source' => __DIR__ . '/../publish/config/apihash.php',
                    'destination' => BASE_PATH . '/config/autoload/tcc.php',
                ]
            ]
        ];
    }
}
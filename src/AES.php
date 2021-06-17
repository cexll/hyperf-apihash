<?php
declare(strict_types=1);

namespace Cexll\Hyperf\Apihash;


class AES
{
    /**
     * 加密
     * @param string $data
     * @param string $key
     * @return false|string
     */
    public function hash(string $data, string $key)
    {
        return base64_encode(openssl_encrypt($data, 'AES-128-ECB', $key, OPENSSL_RAW_DATA));
    }

    /**
     * 解密
     * @param string $encrypt
     * @param string $key
     * @return false|string
     */
    public function decrypt(string $encrypt, string $key)
    {
        return openssl_decrypt(base64_decode($encrypt), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    }


    /**
     * @throws \Exception
     */
    public function randomString(): string
    {
        return bin2hex(random_bytes(8));
    }
}
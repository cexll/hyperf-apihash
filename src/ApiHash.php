<?php
declare(strict_types=1);

namespace Cexll\Hyperf\Apihash;


class ApiHash
{

    /**
     * 生成私秘钥证书
     * @param array $cert_path
     * @return bool
     */
    public function generateCert(array $cert_path = []): bool
    {
        $rsa = new RSA($cert_path);
        return $rsa->exportOpenSSLFile();
    }

    /**
     * 私钥加密
     * @throws \Exception
     */
    public function privateHash(string $data, string $path): array
    {
        $aes = new AES();
        $rsa = new RSA();
        $string = $aes->randomString();
        $rsa->setPrivatePem($path);
        $hashString = $rsa->privateHash($string);
        $aesHash = $aes->hash($data, $string);
        return [
            'body' => $aesHash,
            'key' => $hashString
        ];
    }

    /**
     * 私钥解密
     * @param array $data
     * @param string $path
     * @return false|string
     */
    public function privateDecrypt(array $data, string $path)
    {
        $aes = new AES();
        $rsa = new RSA();
        $rsa->setPrivatePem($path);
        $key = $rsa->privateDecrypt($data['key']);
        return $aes->decrypt($data['body'], $key);
    }

    /**
     * 公钥加密
     * @param string $data
     * @param string $path
     * @return array
     * @throws \Exception
     */
    public function publicHash(string $data, string $path): array
    {
        $aes = new AES();
        $string = $aes->randomString();
        $rsa = new RSA();
        $rsa->setPublicKey($path);
        $hashString = $rsa->publicHash($string);
        $aesHash = $aes->hash($data, $string);
        return [
            'body' => $aesHash,
            'key' => $hashString
        ];
    }

    /**
     * 公钥解密
     * @param array $data
     * @param string $path
     * @return false|string
     */
    public function publicDecrypt(array $data, string $path)
    {
        $aes = new AES();
        $rsa = new RSA();
        $rsa->setPublicKey($path);
        $key = $rsa->publicDecrypt($data['key']);
        return $aes->decrypt($data['body'], $key);
    }
}
<?php
declare(strict_types=1);

namespace Cexll\Hyperf\Apihash;


class RSA
{
    
    protected $cert_path;
    
    protected $public_key;
    
    protected $private_pem;
    
    public function __construct($cert_path = [])
    {
        $this->cert_path = $cert_path;
    }

    public function setPublicKey(string $path)
    {
        $this->public_key = openssl_pkey_get_public(file_get_contents($path));
    }

    public function getPublicKey()
    {
        return $this->public_key;
    }

    public function setPrivatePem(string $path)
    {
        $this->private_pem = openssl_pkey_get_private(file_get_contents($path));
    }

    public function getPrivatePem()
    {
        return $this->private_pem;
    }

    public function exportOpenSSLFile()
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $res = openssl_pkey_new($config);
        if ($res === false) {
            return false;
        }
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        $public_key = $public_key['key'];
        file_put_contents($this->cert_path['public'] ?? '/tmp/cert_public.key', $public_key);
        $this->setPublicKey($public_key);
        file_put_contents($this->cert_path['private'] ?? '/tmp/cert_private.pem', $private_key);
        $this->setPrivatePem($private_key);
        openssl_free_key($res);
        return true;
    }

    /**
     * 私钥加密
     * @param string $data
     * @return string|false
     */
    public function privateHash(string $data)
    {
        if (openssl_private_encrypt($data, $encrypted, $this->private_pem)) {
            return base64_encode($encrypted);
        }
        return false;

    }

    /**
     * 私钥解密
     * @param string $data
     * @return false|string
     */
    public function privateDecrypt(string $data)
    {
        if (openssl_private_decrypt(base64_decode($data), $encrypted, $this->private_pem)) {
            return $encrypted;
        }
        return false;
    }

    /**
     * 公钥加密
     * @param string $data
     * @return string|false
     */
    public function publicHash(string $data)
    {
        if (openssl_public_encrypt($data, $encrypted, $this->public_key)) {
            return base64_encode($encrypted);
        }
        return false;
    }

    /**
     * 公钥解密
     * @param string $data
     * @return false|string
     */
    public function publicDecrypt(string $data)
    {
        if (openssl_public_decrypt(base64_decode($data), $encrypted, $this->public_key)) {
            return $encrypted;
        }
        return false;
    }
}
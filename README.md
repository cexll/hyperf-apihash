# Hyperf-ApiHash

> 为 hyperf 实现的api加密包

对api返回内容进行加密, 区别与常规加密, 使用rsa+aes进行组合加密, 安全性更高


# Run
```php
class IndexController 
{
    public function index()
    {
        $cert_path = [
            'public' => BASE_PATH . '/config/cert/public.key',
            'private' => BASE_PATH . '/config/cert/private.pem',
        ];
        // 1. 生成RSA证书 已经生成的可以不用生成, 只需要配置好路径即可
        // 2. 实例化 ApiHash 
        // 3. 选择加密 公钥加密 或 私钥加密 将加密后的body 直接返回即可, 加密后的内容为 
        // [
        //  body 为apibody 
        //  key  为加密key
        //  ]
        // 4. 解密 
        //  解密步骤 1. 通过RSA解密key得到用于加密aes的key, 使用解密后的key, 来解密aes, 例 rsa->decrypt(证书, key) -> key 得到解密后的key, aes->decrypt(body, key) -> body, 得到正常要返回的api body
        $apihash  = new ApiHash();
        $data = '123123123123123123';
        $body = $apihash->privateHash($data, $cert_path['private']);
        $decrypt = $apihash->publicDecrypt($body, $cert_path['public']);
        return $this->response->json([
            'body' => $body,
            'decrypt' => $decrypt
        ]);
    }
}


```
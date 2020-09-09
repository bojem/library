<?php
namespace q;

class JwtToken
{
   private  $headers = ['typ' => 'JWT', 'alg' => 'HS256'];

    /**
     * @var array 携带参数
     */
   private  $claims = [];

    /**
     * @var string 密匙
     */
   private $secretKey;

   public function __construct($secretKey)
   {
        $this->secretKey = $secretKey;
   }

    /**
     * 生成JwtToken
     * Created by wqs
     * @return string
     */
   public function generateToken()
   {
       if (empty($this->secretKey)) {
           throw new \Exception('加密密匙不能必传');
       }
       $encodedHeaders  = $this->safeB64Encode(json_encode($this->headers, JSON_UNESCAPED_UNICODE));
       $encodedPayloads = $this->safeB64Encode(json_encode($this->claims, JSON_UNESCAPED_UNICODE));
       $signature       = $this->safeB64Encode($this->sign($encodedHeaders . '.' . $encodedPayloads, $this->secretKey));
       return $encodedHeaders . '.' . $encodedPayloads . '.' . $signature;
   }

    /**
     * 解析JwtToken
     * Created by wqs
     * @param $token
     * @return bool|mixed
     * @throws \Exception
     */
   public function parseToken($token)
   {
       $tokenArr = explode('.', $token);
       if (count($tokenArr) != 3) {
           return false;
       }
       list($encodedHeaders, $encodedPayloads, $sign) = $tokenArr;
       $header = json_decode($this->safeB64Decode($encodedHeaders), JSON_OBJECT_AS_ARRAY);
       if (!isset($header['alg']) || empty($header['alg'])) {
           return false;
       }
       $signature = $this->safeB64Encode($this->sign($encodedHeaders . '.' . $encodedPayloads, $this->secretKey));
       if ($signature !== $sign) {
           return false;
       }
       $payload = json_decode($this->safeB64Decode($encodedPayloads), JSON_OBJECT_AS_ARRAY);
       if (!isset($payload['exp']) || $payload['exp'] < time()) {
           return false;
       }
       return $payload;
   }

    /**
     * 设置payload 载荷 数据
     * Created by wqs
     * @param String $name
     * @param $value
     * @return $this
     */
   public function withClaim(String $name, $value)
   {
        $this->claims[$name] = $value;
        return $this;
   }

    /**
     * 加密
     * Created by wqs
     * @param $input
     * @return string|string[]
     */
   public function safeB64Encode($input)
   {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
   }

    /**
     * 解密
     * Created by wqs
     * @param $input
     * @return false|string
     */
   public function safeB64Decode($input)
   {
       $remainder = strlen($input) % 4;
       if ($remainder) {
           $padlen = 4 - $remainder;
           $input .= str_repeat('=', $padlen);
       }
       return base64_decode(strtr($input, '-_', '+/'));
   }

    /**
     * 签名
     * Created by wqs
     * @param string $payload
     * @param string $secretKey
     * @param string $alg
     * @return string
     * @throws \Exception
     */
   private function sign(string $payload, string $secretKey, $alg = 'HS256')
   {
       $methods = [
           'HS256' => 'sha256',
           'HS384' => 'sha384',
           'HS512' => 'sha512'
       ];
       if (!isset($methods[$alg]) || empty($methods[$alg])) {
           throw new \Exception('alg is not suggest ' . $alg);
       }
       return hash_hmac($methods[$alg], $payload, $secretKey, true);
   }


}

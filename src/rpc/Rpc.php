<?php
namespace q\rpc;
use q\rpc\exceptions\RpcException;

/**
 *
 * 暂未完善 有需要的微服务的时候在继续写
 *
\q\rpc\Rpc::init()->send([
    'url' => 'http://www.card.com/api/default/test',
    'method' => 'index',
    'params' => [
    'page' => 1
    ]
]);
 * Class Rpc
 * @package q\rpc
 */
class Rpc{

    /**
     * @var object 单例对象
     */
    private static $instance;

    /**
     * 初始化对象
     * Created by wqs
     * @return object|Rpc
     */
    public static function init()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 发送请求
     * Created by wqs
     * @param array $params
     * @param string $token
     * @throws RpcException
     */
    public function send(array $params, $token = '')
    {
        if (!isset($params['url']) || empty($params['url'])) {
            throw new RpcException('url参数缺失！');
        }
        if (!isset($params['method']) || empty($params['method'])) {
            throw new RpcException('method参数缺失！');
        }
        try {
            $client = new \Yar_Client($params['url']);
            $client->setOpt(YAR_OPT_PERSISTENT, 1);
            $client->SetOpt(YAR_OPT_HEADER, ['token:' . $token]);
            $client->SetOpt(YAR_OPT_PACKAGER, 'json');
            $result = $client->{$params['method']}($params['params'] ?? []);
            $result = json_decode($result, true);
            return $result;
        } catch (\Throwable $e) {
            throw new RpcException($e->getMessage());
        }
    }


    public function sendAll(array $params)
    {
        foreach ($params as $key => $val) {
            if (!isset($val['url']) || empty($val['url'])) {
                throw new RpcException('url参数缺失！');
            }
            if (!isset($val['method']) || empty($val['method'])) {
                throw new RpcException('method参数缺失！');
            }
            if (!isset($val['key']) || empty($val['key'])) {
                throw new RpcException('key参数缺失！');
            }
            try {
                \Yar_Concurrent_Client::Reset();
                \Yar_Concurrent_Client::call($val['url'], $val['method'], $params['params'] ?? []);
            } catch (\Yar_Server_Exception | \Yar_Client_Exception $e) {
                throw new RpcException($e->getMessage());
            }
        }
        Yar_Concurrent_Client::loop(function ($retval, $callinfo){
            if ($callinfo == NULL) {
                //在这些请求发送完成以后, Yar会调用一次callback, 和普通的请求返回回调不同, 这次的调用的$callinfo参数为空.
                echo "现在, 所有的请求都发出去了, 还没有任何请求返回\n";
            } else {
                echo "这是一个远程调用的返回, 调用的服务名是", $callinfo["method"],
                ". 调用的sequence是 " , $callinfo["sequence"] , "\n";
                var_dump($retval);
            }
        }, function ($type, $error, $callinfo){
            print_r($error);
        });
    }


    private function __construct(){}

    private function __clone(){}


}





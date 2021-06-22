<?php
namespace q;

class Redis
{
    /**
     * @var null 操作句柄
     */
    private static $_instance = null;

    private $config = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 0,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
        'tag_prefix' => 'tag:',
        'serialize'  => [],
    ];

    /**
     * @var null Redis ;
     */
    private $redis = null;

    private function __construct()
    {
    }

    /**
     * 单例入口
     * Created by wqs
     * @return static|null
     * @throws \Exception
     */
    public static function init()
    {
        if (!self::$_instance instanceof Redis) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    /**
     * 配置信息
     * Created by wqs
     * @param $config
     * @return $this
     */
    public function setConfig($config)
    {
        $config = array_merge($this->config, $config);
        if (extension_loaded('redis')) {
            $this->redis = new \Redis();
            $this->redis->connect($config['host'], (int) $config['port'], (int) $config['timeout']);
            if ('' != $config['password']) {
                $this->redis->auth($config['password']);
            }
        } elseif (class_exists('\Predis\Client')) {
            $params = [];
            foreach ($config as $key => $val) {
                if (in_array($key, ['aggregate', 'cluster', 'connections', 'exceptions', 'prefix', 'profile', 'replication', 'parameters'])) {
                    $params[$key] = $val;
                    unset($config[$key]);
                }
            }
            if ('' == $config['password']) {
                unset($config['password']);
            }
            $this->redis = new \Predis\Client($config, $params);
            $config['prefix'] = '';
        } else {
            throw new \Exception("不支持redis");
        }
        if (0 != $config['select']) {
            $this->redis->select($config['select']);
        }
        return $this;
    }

    /**
     * Created by wqs
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * Created by wqs
     * @param $key
     * @param $value
     * @param $expire int 单位秒
     * @return bool
     */
    public function set($key, $value, $expire = 0)
    {
        if (intval($expire) <= 0) {
            return (bool) $this->redis->set($key, $value);
        }
        $expire = (int) ($expire * 1000);
        return $this->redis->set($key, $value, ['NX', 'PX' => $expire]);
    }

    /**
     * 加锁
     * Created by wqs
     * @param $key
     * @param int $expire
     * @return mixed
     */
    public function lock($key, $expire = 3)
    {
        return $this->redis->set($key, 1, ['NX', 'PX' => $expire * 1000]);
    }

    /**
     * 删除单个key
     * Created by wqs
     * @param $key
     * @return bool
     */
    public function del($key)
    {
        return (bool) $this->redis->del($key);
    }

    /**
     * 匹配删除多个key 慎用
     * Created by wqs
     * @param $key
     * @return bool
     */
    public function dels($key)
    {
        $cursor = 0;
        do {
            list($cursor, $keys) = $this->redis->scan($cursor, 'MATCH', $key . '*');
            $cursor = (int) $cursor;
            if (!empty($keys)) {
                call_user_func_array([$this->redis, 'del'], $keys);
            }
        } while ($cursor !== 0);
        return true;
    }
}

<?php
declare(strict_types=1);

namespace Hyperf\Extra\Support\Redis;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Extra\Common\RedisModel;
use Hyperf\Extra\Contract\HashServiceInterface;

class RefreshToken extends RedisModel
{
    protected $key = 'refresh-token:';

    /**
     * @Inject()
     * @var HashServiceInterface
     */
    private $hash;

    public function __construct(\Redis $redis = null)
    {
        parent::__construct($redis);
//        $this->hash = $this->container->get(HashServiceInterface::class);
    }

    /**
     * Factory Refresh Token
     * @param string $jti Token ID
     * @param string $ack Ack Code
     * @param int $expires Expires
     * @return mixed
     */
    public function factory(string $jti, string $ack, int $expires)
    {
        return $this->redis->set('sd', $this->hash->make('xzxz'));
    }
}
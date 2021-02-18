<?php
declare (strict_types=1);

namespace Hyperf\Extra\Redis\Library;

interface AclLibrary
{
    /**
     * 获取访问控制
     * @param string $key 键名
     * @param int $policy 策略
     * @return array
     */
    public function get(string $key, int $policy): array;
}
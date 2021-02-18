<?php
declare (strict_types=1);

namespace Hyperf\Extra\Redis\Library;

interface UserLibrary
{
    /**
     * 获取用户缓存
     * @param string $username 用户名
     * @return array
     */
    public function get(string $username): array;
}
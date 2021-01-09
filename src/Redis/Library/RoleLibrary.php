<?php
declare (strict_types=1);

namespace Hyperf\Extra\Redis\Library;

interface RoleLibrary
{
    /**
     * 清除缓存
     */
    public function clear(): void;

    /**
     * 获取权限组缓存
     * @param array $keys 键名
     * @param string $type 类型
     * @return array
     */
    public function get(array $keys, string $type): array;
}
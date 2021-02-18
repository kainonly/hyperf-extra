<?php
declare (strict_types=1);

namespace Hyperf\Extra\Redis\Library;

interface ResourceLibrary
{
    /**
     * 获取资源缓存
     * @return array
     */
    public function get(): array;
}
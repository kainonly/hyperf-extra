<?php
declare(strict_types=1);

namespace Hyperf\Extra\Rbac;

use Hyperf\Extra\Redis\Library\ResourceLibrary;
use Hyperf\Extra\Redis\Library\RoleLibrary;
use Hyperf\Extra\Redis\Library\UserLibrary;
use Hyperf\Utils\Context;

trait Rbac
{
    protected function fetchResource(ResourceLibrary $resourceRedis, UserLibrary $userRedis, RoleLibrary $roleRedis): array
    {
        $router = $resourceRedis->get();
        $user = Context::get('auth')['user'];
        $data = $userRedis->get($user);
        $resourceData = [
            ...$roleRedis->get($data['role'], 'resource'),
            ...$data['resource']
        ];
        $routerRole = array_unique($resourceData);
        $lists = array_filter($router, static fn($v) => in_array($v['key'], $routerRole, true));
        return array_values($lists);
    }
}
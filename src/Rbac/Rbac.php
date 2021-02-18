<?php
declare(strict_types=1);

namespace Hyperf\Extra\Rbac;

use Hyperf\Extra\Redis\Library\ResourceLibrary;
use Hyperf\Extra\Redis\Library\RoleLibrary;
use Hyperf\Extra\Redis\Library\UserLibrary;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;

trait Rbac
{
    protected function fetchResource(ResourceLibrary $resource, UserLibrary $user, RoleLibrary $role): array
    {
        $router = $resource->get();
        $userData = $user->get(Context::get('auth')['user']);
        $resourceData = [
            ...$role->get($userData['role'], 'resource'),
            ...$userData['resource']
        ];
        $routerRole = array_unique($resourceData);
        $lists = Arr::where(
            $router,
            fn($v) => in_array($v['key'], $routerRole, true)
        );
        return array_values($lists);
    }
}
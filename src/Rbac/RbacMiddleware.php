<?php
declare(strict_types=1);

namespace Hyperf\Extra\Rbac;

use Hyperf\Extra\Redis\Library\AclLibrary;
use Hyperf\Extra\Redis\Library\RoleLibrary;
use Hyperf\Extra\Redis\Library\UserLibrary;
use Hyperf\HttpServer\Response;
use Hyperf\Utils\Context;
use Hyperf\Utils\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class RbacMiddleware implements MiddlewareInterface
{
    protected string $prefix = '';
    protected array $ignore = [];
    private RoleLibrary $roleRedis;
    private AclLibrary $aclRedis;
    private UserLibrary $userRedis;

    public function __construct(RoleLibrary $role, AclLibrary $acl, UserLibrary $user)
    {
        $this->roleRedis = $role;
        $this->aclRedis = $acl;
        $this->userRedis = $user;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = str_replace('/' . $this->prefix . '/', '', $request->getUri()->getPath());
        [$controller, $action] = explode('/', $path);
        if (!empty($this->ignore)) {
            foreach ($this->ignore as $value) {
                if (Str::is($value, $action)) {
                    return $handler->handle($request);
                }
            }
        }
        $user = $this->userRedis->get(Context::get('auth')['user']);
        $acl = [
            ...$this->roleRedis->get($user['role'], 'acl'),
            ...$user['acl']
        ];
        $activePolicy = null;
        foreach ($acl as $value) {
            [$aclKey, $policy] = explode(':', $value);
            if ($controller === $aclKey) {
                $activePolicy = $policy;
                if ($policy === 1) {
                    break;
                }
            }
        }
        if ($activePolicy === null) {
            return (new Response())->json([
                'error' => 1,
                'msg' => '权限验证失败，策略设置不存在'
            ]);
        }
        $lists = $this->aclRedis->get($controller, (int)$activePolicy);
        if (empty($lists)) {
            return (new Response())->json([
                'error' => 1,
                'msg' => '权限验证失败，访问控制不存在'
            ]);
        }

        if (!in_array($action, $lists, true)) {
            return (new Response())->json([
                'error' => 1,
                'msg' => '权限验证拒绝，当前用户尚未授权'
            ]);
        }

        return $handler->handle($request);
    }

}
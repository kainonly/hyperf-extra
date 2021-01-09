<?php
declare(strict_types=1);

namespace Hyperf\Extra\Rbac;

use Hyperf\Extra\Redis\Library\AclLibrary;
use Hyperf\Extra\Redis\Library\RoleLibrary;
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

    public function __construct(RoleLibrary $role, AclLibrary $acl)
    {
        $this->roleRedis = $role;
        $this->aclRedis = $acl;
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

        $roleKey = Context::get('auth')['role'];
        $roleLists = $this->roleRedis->get($roleKey, 'acl');
        rsort($roleLists);
        $policy = null;
        foreach ($roleLists as $k => $value) {
            [$roleController, $roleAction] = explode(':', $value);
            if ($roleController === $controller) {
                $policy = $roleAction;
                break;
            }
        }

        if ($policy === null) {
            return (new Response())->json([
                'error' => 1,
                'msg' => 'rbac invalid, policy is empty'
            ]);
        }

        $aclLists = $this->aclRedis->get($controller, (int)$policy);

        if (empty($aclLists)) {
            return (new Response())->json([
                'error' => 1,
                'msg' => 'rbac invalid, acl is empty'
            ]);
        }

        if (!in_array($action, $aclLists, true)) {
            return (new Response())->json([
                'error' => 1,
                'msg' => 'rbac invalid, access denied'
            ]);
        }

        return $handler->handle($request);
    }

}
<?php
declare(strict_types=1);

namespace Hyperf\Extra\Auth;

use Hyperf\Extra\Redis\RefreshToken;
use Hyperf\HttpServer\Response;
use Lcobucci\JWT\Token;
use Hyperf\Utils\Context;
use Hyperf\Extra\Token\TokenInterface;
use Hyperf\Extra\Utils\UtilsInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AuthMiddleware implements MiddlewareInterface
{
    protected string $scene = 'default';
    private TokenInterface $token;
    private UtilsInterface $utils;
    private RefreshToken $refreshToken;

    /**
     * AuthVerify constructor.
     * @param TokenInterface $token
     * @param UtilsInterface $utils
     * @param RefreshToken $refreshToken
     */
    public function __construct(
        TokenInterface $token,
        UtilsInterface $utils,
        RefreshToken $refreshToken
    )
    {
        $this->token = $token;
        $this->utils = $utils;
        $this->refreshToken = $refreshToken;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookies = $request->getCookieParams();
        if (empty($cookies[$this->scene . '_token'])) {
            return (new Response())->json([
                'error' => 1,
                'msg' => '用户认证失效'
            ]);
        }
        /**
         * @var $response ResponseInterface
         * @var $token Token
         */
        $response = Context::get(ResponseInterface::class);
        $tokenString = $cookies[$this->scene . '_token'];
        $result = $this->token->verify($this->scene, $tokenString);
        assert($result->token instanceof Token\Plain);
        $token = $result->token;
        $claims = $token->claims();
        $symbol = $claims->get('symbol');
        $jti = $claims->get('jti');
        $ack = $claims->get('ack');
        if ($result->expired) {
            $verify = $this->refreshToken->verify($jti, $ack);
            if (!$verify) {
                return (new Response())->json([
                    'error' => 1,
                    'msg' => '刷新令牌已过期'
                ]);
            }
            $newToken = $this->token->create($this->scene, $jti, $ack, $symbol);
            $cookie = $this->utils->cookie($this->scene . '_token', $newToken->toString());
            $response = $response->withCookie($cookie);
        }
        $this->refreshToken->renewal($jti, 3600);
        Context::set('auth', $symbol);
        Context::set(ResponseInterface::class, $response);
        return $handler->handle($request);
    }
}
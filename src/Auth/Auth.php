<?php
declare(strict_types=1);

namespace Hyperf\Extra\Auth;

use Hyperf\Extra\Redis\RefreshToken;
use Redis;
use Exception;
use Hyperf\Utils\Str;
use Lcobucci\JWT\Token;
use Psr\Container\ContainerInterface;
use Hyperf\Extra\Token\TokenInterface;
use Hyperf\Extra\Utils\UtilsInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Exception\Http\InvalidResponseException;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Trait Auth
 * @package Hyperf\Extra\Auth
 * @property RequestInterface $request
 * @property ResponseInterface $response
 * @property ContainerInterface $container
 * @property TokenInterface $token
 * @property UtilsInterface $utils
 * @property RefreshToken $refreshToken
 * @property Redis $redis
 */
trait Auth
{

    /**
     * Create Cookie Auth
     * @param string $scene
     * @param array $symbol
     * @return PsrResponseInterface
     * @throws Exception
     */
    protected function create(string $scene, array $symbol = []): PsrResponseInterface
    {
        $jti = uuid()->toString();
        $ack = Str::random();
        $result = $this->refreshToken->factory($jti, $ack, 3600);
        if (!$result) {
            return $this->response->json([
                'error' => 1,
                'msg' => 'refresh token set failed'
            ]);
        }
        $token = $this->token->create($scene, $jti, $ack, $symbol);
        $cookie = $this->utils->cookie($scene . '_token', $token->toString());
        return $this->response->withCookie($cookie)->json([
            'error' => 0,
            'msg' => 'ok'
        ]);
    }

    /**
     * Auth Verify
     * @param $scene
     * @return PsrResponseInterface
     * @throws Exception
     */
    protected function authVerify($scene): PsrResponseInterface
    {
        try {
            $tokenString = $this->request->cookie($scene . '_token');
            if (empty($tokenString)) {
                throw new InvalidResponseException('refresh token not exists');
            }

            $result = $this->token->verify($scene, $tokenString);
            assert($result->token instanceof Token\Plain);
            $token = $result->token;
            $claims = $token->claims();
            $jti = $claims->get('jti');
            $ack = $claims->get('ack');
            if ($result->expired) {
                $verify = $this->refreshToken->verify($jti, $ack);
                if (!$verify) {
                    throw new InvalidResponseException('refresh token verification expired');
                }
                $symbol = $claims->get('symbol');
                $newToken = $this->token->create($scene, $jti, $ack, $symbol);
                $cookie = $this->utils->cookie($scene . '_token', $newToken->toString());
                return $this->response->withCookie($cookie)->json([
                    'error' => 0,
                    'msg' => 'ok'
                ]);
            }
            $this->refreshToken->renewal($jti, 3600);
            return $this->response->json([
                'error' => 0,
                'msg' => 'ok'
            ]);
        } catch (InvalidResponseException $exception) {
            return $this->response->json([
                'error' => 1,
                'msg' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Destory Auth
     * @param string $scene
     * @return PsrResponseInterface
     * @throws Exception
     */
    protected function destory(string $scene): PsrResponseInterface
    {
        $tokenString = $this->request->cookie($scene . '_token');
        if (!empty($tokenString)) {
            $token = $this->token->get($tokenString);
            $claims = $token->claims();
            $this->refreshToken->clear(
                $claims->get('jti'),
                $claims->get('ack')
            );
        }
        $cookie = $this->utils->cookie($scene . '_token', '');
        return $this->response->withCookie($cookie)->json([
            'error' => 0,
            'msg' => 'ok'
        ]);
    }
}
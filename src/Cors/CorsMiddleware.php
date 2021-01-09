<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cors;

use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    private CorsInterface $cors;

    public function __construct(ContainerInterface $container)
    {
        $this->cors = $container->get(CorsInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        assert($response instanceof ResponseInterface);
        $response = $this->setOrigin($request, $response);
        $response = $this->withHeader($response, 'Access-Control-Allow-Methods', $this->cors->getAllowedMethods());
        $response = $this->withHeader($response, 'Access-Control-Allow-Headers', $this->cors->getAllowedHeaders());
        $response = $this->withHeader($response, 'Access-Control-Expose-Headers', $this->cors->getExposedHeaders());
        $maxAge = $this->cors->getMaxAge();
        if (!empty($maxAge)) {
            $response = $response->withHeader('Access-Control-Max-Age', $maxAge);
        }
        if ($this->cors->isAllowedCredentials() === true) {
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
        }
        Context::set(ResponseInterface::class, $response);
        if ($request->getMethod() === 'OPTIONS') {
            return $response;
        }
        return $handler->handle($request);
    }

    /**
     * Set Access-Control-Allow-Origin
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function setOrigin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $origins = $this->cors->getAllowedOrigins();
        if (in_array('*', $origins, true)) {
            return $response->withHeader('Access-Control-Allow-Origin', '*');
        }
        if (empty($request->getHeader('Origin'))) {
            return $response;
        }
        $origin = $request->getHeader('Origin')[0];
        if (in_array($origin, $origins, true)) {
            return $response->withHeader('Access-Control-Allow-Origin', $origin);
        }
        return $response;
    }

    /**
     * Set Mutli Header
     * @param ResponseInterface $response
     * @param string $name
     * @param array $options
     * @return ResponseInterface
     */
    private function withHeader(ResponseInterface $response, string $name, array $options): ResponseInterface
    {
        if (empty($options)) {
            return $response;
        }
        if (in_array('*', $options, true)) {
            return $response->withHeader($name, '*');
        }
        return $response->withHeader($name, implode(',', $options));
    }

}
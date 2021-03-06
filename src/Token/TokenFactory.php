<?php
declare(strict_types=1);

namespace Hyperf\Extra\Token;

use Hyperf\Contract\ConfigInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Psr\Container\ContainerInterface;

class TokenFactory
{
    public function __invoke(ContainerInterface $container): Token
    {
        $config = $container->get(ConfigInterface::class);
        $key = $config->get('app_key');
        $options = $config->get('token');
        return make(Token::class, [
            Configuration::forSymmetricSigner(
                new Sha256(),
                InMemory::plainText($key)
            ),
            $options
        ]);
    }
}
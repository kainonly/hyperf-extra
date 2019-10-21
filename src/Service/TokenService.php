<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Extra\Contract\UtilsServiceInterface;
use Hyperf\Utils\Str;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

final class TokenService
{
    /**
     * Token secret
     * @var string $secret
     */
    private $secret;

    /**
     * Token options
     * @var array $options
     */
    private $options;

    /**
     * Token signer
     * @var Sha256 $signer
     */
    private $signer;

    /**
     * @var UtilsServiceInterface
     */
    private $utils;

    /**
     * TokenService constructor.
     * @param string $secret
     * @param array $config
     * @param UtilsServiceInterface $utils
     */
    public function __construct(string $secret, array $options, UtilsServiceInterface $utils)
    {
        $this->secret = $secret;
        $this->options = $options;
        $this->utils = $utils;
        $this->signer = new Sha256();
    }

    /**
     * Generate token
     * @param string $scene
     * @param array $symbol
     * @return \Lcobucci\JWT\Token
     * @throws \Exception
     */
    public function create(string $scene, array $symbol = [])
    {
        $jti = $this->utils->uuid()->toString();
        $ack = Str::random();
        return (new Builder())
            ->issuedBy($this->options[$scene]['issuer'])
            ->permittedFor($this->options[$scene]['audience'])
            ->identifiedBy($jti, true)
            ->withClaim('ack', $ack)
            ->withClaim('symbol', $symbol)
            ->expiresAt(time() + $this->options[$scene]['expires'])
            ->getToken($this->signer, new Key($this->secret));
    }

    /**
     * Get token
     * @param string $tokenString
     * @return \Lcobucci\JWT\Token
     */
    public function get(string $tokenString)
    {
        return (new Parser())->parse($tokenString);
    }

    /**
     * Verification token
     * @param string $scene
     * @param string $tokenString
     * @return bool
     * @throws \Exception
     */
    public function verify(string $scene, string $tokenString)
    {
        $token = (new Parser())->parse($tokenString);
        if (!$token->verify($this->signer, $this->secret)) {
            throw new \Exception('Token validation is incorrect');
        }

        if ($token->getClaim('iss') != $this->options[$scene]['issuer'] ||
            $token->getClaim('aud') != $this->options[$scene]['audience']) {
            throw new \Exception('Token information is incorrect');
        }

        if ($token->isExpired()) {
            throw new \Exception('Token expiration time has expired');
        }

        return true;
    }
}
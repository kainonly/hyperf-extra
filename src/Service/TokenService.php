<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

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
     * TokenService constructor.
     * @param string $secret
     * @param array $config
     */
    public function __construct(string $secret, array $options)
    {
        $this->secret = $secret;
        $this->options = $options;
        $this->signer = new Sha256();
    }

    /**
     * Generate token
     * @param string $scene
     * @param string $jti
     * @param string $ack
     * @param array $symbol
     * @return \Lcobucci\JWT\Token
     */
    public function create(string $scene, string $jti, string $ack, array $symbol = [])
    {
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
     * @return \stdClass
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

        $result = new \stdClass();
        $result->expired = $token->isExpired();
        $result->token = $token;
        return $result;
    }
}
<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Exception;
use stdClass;
use Hyperf\HttpServer\Exception\Http\InvalidResponseException;
use Hyperf\Server\Exception\RuntimeException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Hyperf\Extra\Contract\TokenServiceInterface;
use Lcobucci\JWT\Token;

class TokenService implements TokenServiceInterface
{
    /**
     * Token secret
     * @var string $secret
     */
    private string $secret;

    /**
     * Token options
     * @var array $options
     */
    private array $options;

    /**
     * TokenService constructor.
     * @param string $secret
     * @param array $config
     */
    public function __construct(string $secret, array $options)
    {
        $this->secret = $secret;
        $this->options = $options;
    }

    /**
     * @param string $scene
     * @param string $jti
     * @param string $ack
     * @param array $symbol
     * @return Token
     * @inheritDoc
     */
    public function create(string $scene, string $jti, string $ack, array $symbol = []): Token
    {
        if (empty($this->options[$scene])) {
            throw new RuntimeException("The [$scene] does not exist.");
        }

        return (new Builder())
            ->issuedBy($this->options[$scene]['issuer'])
            ->permittedFor($this->options[$scene]['audience'])
            ->identifiedBy($jti, true)
            ->withClaim('ack', $ack)
            ->withClaim('symbol', $symbol)
            ->expiresAt(time() + $this->options[$scene]['expires'])
            ->getToken(new Sha256(), new Key($this->secret));
    }

    /**
     * Get token
     * @param string $tokenString
     * @return Token
     * @inheritDoc
     */
    public function get(string $tokenString): Token
    {
        return (new Parser)->parse($tokenString);
    }

    /**
     * Verification token
     * @param string $scene
     * @param string $tokenString
     * @return stdClass
     */
    public function verify(string $scene, string $tokenString): stdClass
    {
        $token = (new Parser())->parse($tokenString);
        if (!$token->verify(new Sha256(), $this->secret)) {
            throw new RuntimeException('Token validation is incorrect');
        }

        if ($token->getClaim('iss') != $this->options[$scene]['issuer'] ||
            $token->getClaim('aud') != $this->options[$scene]['audience']) {
            throw new InvalidResponseException('Token information is incorrect');
        }

        $result = new stdClass();
        $result->expired = $token->isExpired();
        $result->token = $token;
        return $result;
    }
}
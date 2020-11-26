<?php
declare(strict_types=1);

namespace Hyperf\Extra\Token;

use stdClass;
use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class TokenFactory implements TokenInterface
{
    /**
     * JWT生产配置
     * @var Configuration
     */
    private Configuration $config;

    /**
     * 令牌配置
     * @var array $options
     */
    private array $options;

    /**
     * @param Configuration $config JWT生产配置
     * @param array $options 令牌配置
     */
    public function __construct(Configuration $config, array $options)
    {
        $this->config = $config;
        $this->options = $options;

    }

    /**
     * @param string $scene
     * @param string $jti
     * @param string $ack
     * @param array $symbol
     * @return Plain
     * @inheritDoc
     */
    public function create(string $scene, string $jti, string $ack, array $symbol = []): Plain
    {
        if (empty($this->options[$scene])) {
            throw new InvalidArgumentException("The [{$scene}] does not exist.");
        }
        $now = CarbonImmutable::now();
        return $this->config->builder()
            ->issuedBy($this->options[$scene]['issuer'])
            ->permittedFor($this->options[$scene]['audience'])
            ->identifiedBy($jti)
            ->withClaim('ack', $ack)
            ->withClaim('symbol', $symbol)
            ->expiresAt($now->addSeconds($this->options[$scene]['expires'])->toDateTimeImmutable())
            ->getToken($this->config->signer(), $this->config->signingKey());
    }

    /**
     * @param string $jwt
     * @inheritDoc
     */
    public function get(string $jwt): Plain
    {
        $token = $this->config->parser()->parse($jwt);
        assert($token instanceof Plain);
        return $token;
    }

    /**
     * @param string $scene
     * @param string $jwt
     * @return stdClass
     * @inheritDoc
     */
    public function verify(string $scene, string $jwt): stdClass
    {
        if (empty($this->options[$scene])) {
            throw new InvalidArgumentException("The [{$scene}] does not exist.");
        }
        $this->config->setValidationConstraints(
            new IssuedBy($this->options[$scene]['issuer']),
            new PermittedFor($this->options[$scene]['audience']),
            new SignedWith($this->config->signer(), $this->config->signingKey())
        );
        $token = $this->config->parser()->parse($jwt);
        $constraints = $this->config->validationConstraints();
        if (!$this->config->validator()->validate($token, ...$constraints)) {
            throw new InvalidArgumentException('Token validation is incorrect');
        }
        $result = new stdClass();
        $result->expired = $token->isExpired(CarbonImmutable::now()->toDateTimeImmutable());
        $result->token = $token;
        return $result;
    }
}
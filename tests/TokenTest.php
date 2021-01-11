<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Exception;
use Hyperf\Extra\Token\TokenInterface;
use Hyperf\Utils\ApplicationContext;
use Lcobucci\JWT\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    /**
     * @var TokenInterface
     */
    private TokenInterface $token;

    /**
     * @var string
     */
    private string $scene;

    /**
     * @var string
     */
    private string $jti;

    /**
     * @var string
     */
    private string $ack;
    /**
     * @var array
     */
    private array $symbol = [
        'role' => '*'
    ];

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $container = ApplicationContext::getContainer();
        $this->token = $container->get(TokenInterface::class);
        $this->scene = 'default';
        $this->jti = '12345678';
        $this->ack = 'a1b2c3';
    }

    public function testCreate(): string
    {
        $token = $this->token->create(
            $this->scene,
            $this->jti,
            $this->ack,
            $this->symbol
        );
        self::assertNotEmpty($token->payload(), '令牌创建失败');
        return $token->toString();
    }

    /**
     * @depends testCreate
     * @param string $jwt
     */
    public function testGet(string $jwt): void
    {
        $token = $this->token->get($jwt);
        $claims = $token->claims();
        self::assertEquals($this->jti, $claims->get('jti'));
    }

    /**
     * @depends testCreate
     * @param string $jwt
     */
    public function testVerify(string $jwt): void
    {
        try {
            $result = $this->token->verify('default', $jwt);
            self::assertIsBool($result->expired, '未生成超时状态');
            self::assertInstanceOf(Token::class, $result->token, '令牌信息获取失败');
            self::assertTrue(assert($result->token instanceof Token\Plain));
            $claims = $result->token->claims();
            self::assertEquals($this->jti, $claims->get('jti'));
        } catch (Exception $e) {
            $this->expectErrorMessage($e->getMessage());
        }
    }
}
<?php
declare(strict_types=1);

namespace HyperfExtraTest;

use Hyperf\Extra\Token\TokenInterface;
use Hyperf\Utils\ApplicationContext;
use Lcobucci\JWT\Token;
use PHPUnit\Framework\TestCase;
use stdClass;

class TokenTest extends TestCase
{
    private TokenInterface $token;
    private string $scene;
    private string $jti;
    private string $ack;
    private array $symbol;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $container = ApplicationContext::getContainer();
        $this->token = $container->get(TokenInterface::class);
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->scene = 'default';
        $this->jti = 'test';
        $this->ack = md5('test');
        $this->symbol = [
            'role' => ['*']
        ];
    }

    public function testCreate(): string
    {
        $token = $this->token->create(
            $this->scene,
            $this->jti,
            $this->ack,
            $this->symbol
        );
        self::assertNotEmpty((string)$token, '令牌创建失败');
        return (string)$token;
    }

    /**
     * @depends testCreate
     * @param string $tokenString
     */
    public function testGet(string $tokenString): void
    {
        $token = $this->token->get($tokenString);
        assert($token instanceof Token);
        self::assertEquals($this->jti, $token->claims()->get('jti'));
    }

    /**
     * @depends testCreate
     * @param string $tokenString
     */
    public function testVerify(string $tokenString): void
    {
        $result = $this->token->verify('default', $tokenString);
        self::assertIsBool($result->expired, '未生成超时状态');
        self::assertInstanceOf(Token::class, $result->token, '令牌信息获取失败');
        self::assertEquals($this->jti, $result->token->getClaim('jti'));
    }
}
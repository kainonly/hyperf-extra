<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Extra\Contract\UtilsServiceInterface;
use Hyperf\HttpMessage\Cookie\Cookie;
use Ramsey\Uuid\Uuid;

final class UtilsService implements UtilsServiceInterface
{
    /**
     * @var array
     */
    private $cookieOption = [];

    /**
     * UtilsService constructor.
     * @param array $cookieOption
     */
    public function __construct(array $cookieOption)
    {
        $this->cookieOption = $cookieOption;
    }

    /**
     * Create Uuid V4 Object
     * @return Uuid|\Ramsey\Uuid\UuidInterface
     * @throws \Exception
     */
    public function uuid()
    {
        return Uuid::uuid4();
    }

    /**
     * Create Cookie Object
     * @param string $name
     * @param string $value
     * @param int|null $expire
     * @param string|null $path
     * @param string|null $domain
     * @param bool|null $secure
     * @param bool|null $httpOnly
     * @param bool|null $raw
     * @param string|null $sameSite
     * @return Cookie
     */
    public function cookie(
        string $name,
        string $value,
        ?int $expire,
        ?string $path,
        ?string $domain,
        ?bool $secure,
        ?bool $httpOnly,
        ?bool $raw,
        ?string $sameSite
    )
    {
        return new Cookie(
            $name,
            $value,
            $expire ? $expire : $this->cookieOption['expire'],
            $path ? $path : $this->cookieOption['path'],
            $domain ? $domain : $this->cookieOption['domain'],
            $secure ? $secure : $this->cookieOption['secure'],
            $httpOnly ? $httpOnly : $this->cookieOption['httpOnly'],
            $raw ? $raw : $this->cookieOption['raw'],
            $sameSite ? $sameSite : $this->cookieOption['sameSite']
        );
    }
}
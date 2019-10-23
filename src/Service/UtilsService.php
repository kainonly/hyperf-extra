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
     * @param array $options
     * @return Cookie
     */
    public function cookie(string $name, string $value, array $options = [])
    {
        return new Cookie(
            $name,
            $value,
            !empty($options['expire']) ? $options['expire'] : $this->cookieOption['expire'],
            !empty($options['path']) ? $options['path'] : $this->cookieOption['path'],
            !empty($options['domain']) ? $options['domain'] : $this->cookieOption['domain'],
            !empty($options['secure']) ? $options['secure'] : $this->cookieOption['secure'],
            !empty($options['httpOnly']) ? $options['httpOnly'] : $this->cookieOption['httpOnly'],
            !empty($options['raw']) ? $options['raw'] : $this->cookieOption['raw'],
            !empty($options['sameSite']) ? $options['sameSite'] : $this->cookieOption['sameSite']
        );
    }
}
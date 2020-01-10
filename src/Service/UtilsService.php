<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Exception;
use Hyperf\Extra\Contract\UtilsServiceInterface;
use Hyperf\HttpMessage\Cookie\Cookie;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UtilsService implements UtilsServiceInterface
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
     * @return UuidInterface
     * @throws Exception
     */
    public function uuid(): UuidInterface
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
    public function cookie(string $name, string $value, array $options = []): Cookie
    {
        $options = array_merge($this->cookieOption, $options);
        return new Cookie(
            $name,
            $value,
            $options['expire'],
            $options['path'],
            $options['domain'],
            $options['secure'],
            $options['httpOnly'],
            $options['raw'],
            $options['sameSite']
        );
    }
}
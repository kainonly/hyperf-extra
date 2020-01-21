<?php
declare(strict_types=1);

namespace Hyperf\Extra\Utils;

use Hyperf\HttpMessage\Cookie\Cookie;

class UtilsFactory implements UtilsInterface
{
    private array $cookieOption;

    /**
     * UtilsService constructor.
     * @param array $cookieOption
     */
    public function __construct(array $cookieOption)
    {
        $this->cookieOption = $cookieOption;
        $this->cookieOption['expire'] ??= 0;
        $this->cookieOption['path'] ??= '/';
        $this->cookieOption['domain'] ??= '';
        $this->cookieOption['secure'] ??= false;
        $this->cookieOption['httponly'] ??= false;
        $this->cookieOption['setcookie'] ??= true;
        $this->cookieOption['raw'] ??= false;
        $this->cookieOption['samesite'] ??= null;
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
            $options['httponly'],
            $options['raw'],
            $options['samesite']
        );
    }
}
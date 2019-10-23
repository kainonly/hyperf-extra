<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Extra\Contract\HashServiceInterface;

final class HashService implements HashServiceInterface
{
    /**
     * @var int
     */
    private $algo = PASSWORD_ARGON2ID;
    /**
     * @var array
     */
    private $options;

    /**
     * HashService constructor.
     * @param string $driver
     * @param array $options
     */
    public function __construct(string $driver, array $options)
    {
        $this->options = $options;
        switch ($driver) {
            case 'bcrypt':
                $this->algo = PASSWORD_BCRYPT;
                break;
            case 'argon':
                $this->algo = PASSWORD_ARGON2I;
                break;
            case 'argon2id':
                $this->algo = PASSWORD_ARGON2ID;
                break;
        }
    }

    /**
     * @param string $password
     * @param array $options
     * @return false|string
     */
    public function make(string $password, array $options = [])
    {
        return password_hash($password, $this->algo, array_merge($this->options, $options));
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function check(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }
}
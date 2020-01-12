<?php
declare(strict_types=1);

namespace Hyperf\Extra\Service;

use Hyperf\Extra\Contract\HashServiceInterface;

class HashService implements HashServiceInterface
{
    private string $algo;
    private array $options;

    /**
     * HashService constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        switch ($options['driver']) {
            case 'argon2id':
                $this->algo = PASSWORD_ARGON2ID;
                $this->options = $options['argon'];
                break;
            case 'argon':
                $this->algo = PASSWORD_ARGON2I;
                $this->options = $options['argon'];
                break;
            case 'bcrypt':
                $this->algo = PASSWORD_BCRYPT;
                $this->options = $options['bcrypt'];
                break;
        }
    }

    /**
     * @param string $password
     * @param array $options
     * @return false|string|null
     * @inheritDoc
     */
    public function create(string $password, array $options = [])
    {
        return password_hash(
            $password,
            $this->algo,
            $options ?? $this->options
        );
    }

    /**
     * @param string $password
     * @param string $hashPassword
     * @return bool
     * @inheritDoc
     */
    public function check(string $password, string $hashPassword): bool
    {
        return password_verify($password, $hashPassword);
    }
}
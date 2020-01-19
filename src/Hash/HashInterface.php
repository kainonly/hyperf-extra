<?php
declare(strict_types=1);

namespace Hyperf\Extra\Hash;

interface HashInterface
{
    /**
     * @param string $password
     * @param array $options
     * @return false|string
     */
    public function create(string $password, array $options = []);

    /**
     * @param string $password
     * @param string $hashPassword
     * @return bool
     */
    public function check(string $password, string $hashPassword): bool;
}
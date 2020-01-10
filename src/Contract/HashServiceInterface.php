<?php
declare(strict_types=1);

namespace Hyperf\Extra\Contract;

interface HashServiceInterface
{
    /**
     * @param string $password
     * @param array $options
     * @return false|string
     */
    public function make(string $password, array $options = []);

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function check(string $password, string $hash);
}
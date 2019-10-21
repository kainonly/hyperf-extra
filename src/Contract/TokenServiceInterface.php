<?php
declare(strict_types=1);

namespace Hyperf\Extra\Contract;

interface TokenServiceInterface
{
    /**
     * Generate token
     * @param string $scene
     * @param array $symbol
     * @return \Lcobucci\JWT\Token
     * @throws \Exception
     */
    public function create(string $scene, array $symbol = []);

    /**
     * Get token
     * @param string $tokenString
     * @return \Lcobucci\JWT\Token
     */
    public function get(string $tokenString);

    /**
     * Verification token
     * @param string $scene
     * @param string $tokenString
     * @return bool
     * @throws \Exception
     */
    public function verify(string $scene, string $tokenString);
}
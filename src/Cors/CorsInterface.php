<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cors;

interface CorsInterface
{
    /**
     * @return array
     */
    public function getAllowedMethods(): array;

    /**
     * @return array
     */
    public function getAllowedOrigins(): array;

    /**
     * @return array
     */
    public function getAllowedHeaders(): array;

    /**
     * @return array
     */
    public function getExposedHeaders(): array;

    /**
     * @return int
     */
    public function getMaxAge(): int;

    /**
     * @return bool
     */
    public function isAllowedCredentials(): bool;
}
<?php
declare(strict_types=1);

namespace Hyperf\Extra\Contract;

interface CorsInterface
{
    /**
     * @return array
     */
    public function getPaths(): array;

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
    public function getAllowedOriginsPatterns(): array;

    /**
     * @return array
     */
    public function getAllowedHeaders(): array;

    /**
     * @return bool
     */
    public function isExposedHeaders(): bool;

    /**
     * @return int
     */
    public function getMaxAge(): int;

    /**
     * @return bool
     */
    public function isSupportsCredentials(): bool;
}
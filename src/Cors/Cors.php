<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cors;

class Cors implements CorsInterface
{
    /**
     * Matches the request method. `[*]` allows all methods.
     * @var array
     */
    private array $allowed_methods;

    /**
     * Matches the request origin. `[*]` allows all origins.
     * @var array
     */
    private array $allowed_origins;

    /**
     * Sets the Access-Control-Allow-Headers response header. `[*]` allows all headers.
     * @var array
     */
    private array $allowed_headers;

    /**
     * Sets the Access-Control-Expose-Headers response header.
     * @var array
     */
    private array $exposed_headers;

    /**
     * Sets the Access-Control-Max-Age response header.
     * @var int
     */
    private int $max_age;

    /**
     * Sets the Access-Control-Allow-Credentials header.
     * @var bool
     */
    private bool $allowed_credentials;

    /**
     * CorsService constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->allowed_methods = $options['allowed_methods'] ?? ['*'];
        $this->allowed_origins = $options['allowed_origins'] ?? ['*'];
        $this->allowed_headers = $options['allowed_headers'] ?? ['*'];
        $this->exposed_headers = $options['exposed_headers'] ?? [];
        $this->max_age = $options['max_age'] ?? 0;
        $this->allowed_credentials = $options['allowed_credentials'] ?? false;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return $this->allowed_methods;
    }

    /**
     * @return array
     */
    public function getAllowedOrigins(): array
    {
        return $this->allowed_origins;
    }

    /**
     * @return array
     */
    public function getAllowedHeaders(): array
    {
        return $this->allowed_headers;
    }

    /**
     * @return array
     */
    public function getExposedHeaders(): array
    {
        return $this->exposed_headers;
    }

    /**
     * @return int
     */
    public function getMaxAge(): int
    {
        return $this->max_age;
    }

    /**
     * @return bool
     */
    public function isAllowedCredentials(): bool
    {
        return $this->allowed_credentials;
    }
}
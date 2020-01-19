<?php
declare(strict_types=1);

namespace Hyperf\Extra\Cors;

class CorsFactory implements CorsInterface
{
    /**
     * You can enable CORS for 1 or multiple paths.
     * @var array
     */
    private array $paths;

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
     * Matches the request origin with, similar to `Request::is()`
     * @var array
     */
    private array $allowed_origins_patterns;

    /**
     * Sets the Access-Control-Allow-Headers response header. `[*]` allows all headers.
     * @var array
     */
    private array $allowed_headers;

    /**
     * Sets the Access-Control-Expose-Headers response header.
     * @var bool
     */
    private bool $exposed_headers = false;

    /**
     * Sets the Access-Control-Max-Age response header.
     * @var int
     */
    private int $max_age = 0;

    /**
     * Sets the Access-Control-Allow-Credentials header.
     * @var bool
     */
    private bool $supports_credentials = false;

    /**
     * CorsFactory constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->paths = $options['paths'] ?? [];
        $this->allowed_methods = $options['allowed_methods'] ?? ['*'];
        $this->allowed_origins = $options['allowed_origins'] ?? ['*'];
        $this->allowed_origins_patterns = $options['allowed_origins_patterns'] ?? [];
        $this->allowed_headers = $options['allowed_headers'] ?? ['*'];
        $this->exposed_headers = $options['exposed_headers'] ?? false;
        $this->max_age = $options['max_age'] ?? 0;
        $this->supports_credentials = $options['supports_credentials'] ?? false;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
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
    public function getAllowedOriginsPatterns(): array
    {
        return $this->allowed_origins_patterns;
    }

    /**
     * @return array
     */
    public function getAllowedHeaders(): array
    {
        return $this->allowed_headers;
    }

    /**
     * @return bool
     */
    public function isExposedHeaders(): bool
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
    public function isSupportsCredentials(): bool
    {
        return $this->supports_credentials;
    }
}
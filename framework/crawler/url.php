<?php namespace Framework\Crawler;

use InvalidArgumentException;

/**
 * Represents and parses a Uniform Resource Identifier (URI).
 *
 * The URL class parses the URI into its individual components and exposes
 * both the original URI and derived information such as the authority,
 * origin, and effective port.
 *
 * The class does not determine whether a URI is safe or appropriate to
 * retrieve. Those concerns belong to the component responsible for making
 * the request.
 *
 * @author McSwampy <mcswampy@sylph.co.za>
 * @copyright 2026 Sylph Syndicate
 * 
 */
class URL
{
    /**
     * The original URI supplied to the constructor.
     *
     * @var string
     */
    private string $uri;

    /**
     * The URI scheme, such as "http" or "https".
     *
     * @var string|null
     */
    private ?string $scheme = null;

    /**
     * The username component of the URI.
     *
     * @var string|null
     */
    private ?string $user = null;

    /**
     * The password component of the URI.
     *
     * @var string|null
     */
    private ?string $password = null;

    /**
     * The hostname component of the URI.
     *
     * @var string|null
     */
    private ?string $host = null;

    /**
     * The explicitly specified port.
     *
     * This does not contain the default port when one was omitted from
     * the original URI. Use {@see getEffectivePort()} for that.
     *
     * @var int|null
     */
    private ?int $port = null;

    /**
     * The path component of the URI.
     *
     * @var string|null
     */
    private ?string $path = null;

    /**
     * The query string without the leading question mark.
     *
     * @var string|null
     */
    private ?string $query = null;

    /**
     * The fragment identifier without the leading hash.
     *
     * @var string|null
     */
    private ?string $fragment = null;

    /**
     * Parse and validate a URI.
     *
     * @param string $uri URI to parse.
     *
     * @throws InvalidArgumentException If the URI is empty or invalid.
     */
    public function __construct(string $uri)
    {
        $uri = trim($uri);

        if ($uri === '') {
            throw new InvalidArgumentException('URI cannot be empty.');
        }

        if (!filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(
                sprintf('Invalid URI: "%s"', $uri)
            );
        }

        $parts = parse_url($uri);

        if ($parts === false) {
            throw new InvalidArgumentException(
                sprintf('Unable to parse URI: "%s"', $uri)
            );
        }

        $this->uri = $uri;

        $this->scheme = isset($parts['scheme'])
            ? strtolower($parts['scheme'])
            : null;

        $this->user = $parts['user'] ?? null;
        $this->password = $parts['pass'] ?? null;

        $this->host = isset($parts['host'])
            ? strtolower($parts['host'])
            : null;

        $this->port = $parts['port'] ?? null;
        $this->path = $parts['path'] ?? null;
        $this->query = $parts['query'] ?? null;
        $this->fragment = $parts['fragment'] ?? null;
    }

    /**
     * Return the original URI.
     *
     * @return string Original URI.
     */
    public function __toString(): string
    {
        return $this->uri;
    }

    /**
     * Return the original URI.
     *
     * @return string Original URI.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Return the URI scheme.
     *
     * @return string|null Scheme or null if none is present.
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * Return the username component.
     *
     * @return string|null Username or null if none is present.
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * Return the password component.
     *
     * @return string|null Password or null if none is present.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Return the hostname.
     *
     * @return string|null Hostname or null if none is present.
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * Return the explicitly specified port.
     *
     * @return int|null Port or null if no port was specified.
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Return the path component.
     *
     * @return string|null Path or null if none is present.
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Return the query component without its leading question mark.
     *
     * @return string|null Query string or null if none is present.
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * Return the fragment component without its leading hash.
     *
     * @return string|null Fragment or null if none is present.
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * Return the authority component.
     *
     * The authority consists of user information, host, and port.
     *
     * @return string|null Authority or null if no host is present.
     */
    public function getAuthority(): ?string
    {
        if ($this->host === null) {
            return null;
        }

        $authority = '';

        if ($this->user !== null) {
            $authority .= $this->user;

            if ($this->password !== null) {
                $authority .= ':' . $this->password;
            }

            $authority .= '@';
        }

        $authority .= $this->host;

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * Return the URI origin.
     *
     * The origin consists of the scheme and authority.
     *
     * @return string|null Origin or null if the scheme or host is missing.
     */
    public function getOrigin(): ?string
    {
        if ($this->scheme === null || $this->host === null) {
            return null;
        }

        return $this->scheme . '://' . $this->getAuthority();
    }

    /**
     * Return the effective port for the URI scheme.
     *
     * If an explicit port was provided, that port is returned.
     * Otherwise, the standard port for the supported scheme is returned.
     *
     * @return int|null Effective port or null if no default is known.
     */
    public function getEffectivePort(): ?int
    {
        if ($this->port !== null) {
            return $this->port;
        }

        return match ($this->scheme) {
            'http'  => 80,
            'https' => 443,
            'ftp'   => 21,
            default => null,
        };
    }

    /**
     * Determine whether the URI contains a scheme.
     *
     * @return bool True when a scheme is present.
     */
    public function hasScheme(): bool
    {
        return $this->scheme !== null;
    }

    /**
     * Determine whether the URI contains user information.
     *
     * @return bool True when a username or password is present.
     */
    public function hasCredentials(): bool
    {
        return $this->user !== null || $this->password !== null;
    }

    /**
     * Determine whether the URI contains a hostname.
     *
     * @return bool True when a hostname is present.
     */
    public function hasHost(): bool
    {
        return $this->host !== null;
    }

    /**
     * Determine whether an explicit port was provided.
     *
     * @return bool True when a port is explicitly present.
     */
    public function hasPort(): bool
    {
        return $this->port !== null;
    }

    /**
     * Determine whether the URI contains a path.
     *
     * @return bool True when a non-empty path is present.
     */
    public function hasPath(): bool
    {
        return $this->path !== null && $this->path !== '';
    }

    /**
     * Determine whether the URI contains a query string.
     *
     * @return bool True when a query string is present.
     */
    public function hasQuery(): bool
    {
        return $this->query !== null;
    }

    /**
     * Determine whether the URI contains a fragment.
     *
     * @return bool True when a fragment is present.
     */
    public function hasFragment(): bool
    {
        return $this->fragment !== null;
    }

    /**
     * Determine whether the URI contains a scheme and is therefore absolute.
     *
     * @return bool True when a scheme is present.
     */
    public function isAbsolute(): bool
    {
        return $this->scheme !== null;
    }

    /**
     * Determine whether the URI uses HTTPS.
     *
     * @return bool True when the URI uses HTTPS.
     */
    public function isSecure(): bool
    {
        return $this->scheme === 'https';
    }

    /**
     * Determine whether the URI uses HTTP or HTTPS.
     *
     * @return bool True when the URI uses HTTP or HTTPS.
     */
    public function isHttp(): bool
    {
        return in_array($this->scheme, ['http', 'https'], true);
    }

    /**
     * Determine whether the explicitly specified port is the default
     * port for the URI scheme.
     *
     * @return bool True when the explicit port matches the default port.
     */
    public function isDefaultPort(): bool
    {
        return $this->port !== null
            && $this->port === $this->getEffectivePort();
    }

    /**
     * Return all parsed and derived URI information.
     *
     * @return array{
     *     uri: string,
     *     scheme: string|null,
     *     user: string|null,
     *     password: string|null,
     *     host: string|null,
     *     port: int|null,
     *     path: string|null,
     *     query: string|null,
     *     fragment: string|null,
     *     authority: string|null,
     *     origin: string|null,
     *     effective_port: int|null
     * }
     */
    public function toArray(): array
    {
        return [
            'uri'            => $this->uri,
            'scheme'         => $this->scheme,
            'user'           => $this->user,
            'password'       => $this->password,
            'host'           => $this->host,
            'port'           => $this->port,
            'path'           => $this->path,
            'query'          => $this->query,
            'fragment'       => $this->fragment,
            'authority'      => $this->getAuthority(),
            'origin'         => $this->getOrigin(),
            'effective_port' => $this->getEffectivePort(),
        ];
    }
}

<?php namespace Framework\Crawler;

/**
 * Handler
 *
 * Standard crawler handler to retrieve content via cURL
 *
 * @author McSwampy <mcswampy@sylph.co.za>
 * @copyright 2026 Sylph Syndicate
 *
 */
class Handler
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $this->validateUrl($url);
    }

    private function validateUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            throw new \InvalidArgumentException('URL cannot be empty.');
        }

        if (strlen($url) > 2048) {
            throw new \InvalidArgumentException('URL is too long.');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid URL: "%s"', $url)
            );
        }

        $parts = parse_url($url);

        if ($parts === false) {
            throw new \InvalidArgumentException(
                sprintf('Unable to parse URL: "%s"', $url)
            );
        }

        $scheme = strtolower($parts['scheme'] ?? '');

        if (!in_array($scheme, ['http', 'https'], true)) {
            throw new \InvalidArgumentException(
                'URL must use either HTTP or HTTPS.'
            );
        }

        if (empty($parts['host'])) {
            throw new \InvalidArgumentException(
                'URL must contain a hostname.'
            );
        }

        // Credentials in crawler URLs are usually undesirable.
        if (isset($parts['user']) || isset($parts['pass'])) {
            throw new \InvalidArgumentException(
                'URLs containing username/password credentials are not supported.'
            );
        }

        // Fragments are never sent to the HTTP server.
        if (isset($parts['fragment'])) {
            $url = substr($url, 0, strrpos($url, '#'));
        }

        return $url;
    }

    public function fetch(): string
    {
        $curl = curl_init($this->url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      => 'Framework Crawler/1.0',
        ]);

        $content = curl_exec($curl);

        if ($content === false) {
            $error = curl_error($curl);
            
            throw new \RuntimeException(
                sprintf(
                    'Failed to retrieve "%s": %s',
                    $this->url,
                    $error
                )
            );
        }

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status >= 400) {
            throw new \RuntimeException(
                sprintf(
                    'HTTP request failed for "%s" with status %d.',
                    $this->url,
                    $status
                )
            );
        }

        return $content;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}

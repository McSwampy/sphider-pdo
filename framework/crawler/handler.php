<?php namespace Framework\Crawler;

/**
 * Handler
 *
 * Standard crawler handler to retrieve content via cURL.
 *
 * The handler is responsible for executing HTTP requests. URL parsing,
 * validation, and URI inspection are handled by the URL class.
 *
 * @author McSwampy <mcswampy@sylph.co.za>
 * @since 2.0.0
 * @copyright 2026 Sylph Syndicate
 * 
 * @example 
 * $url = new URL('https://example.com/foo?bar=baz');
 * $handler = new Handler($url);
 * $content = $handler->fetch();
 *
 */
class Handler
{
    /**
     * URL to retrieve.
     *
     * @var URL
     */
    private URL $url;

    /**
     * Create a crawler handler.
     *
     * @param URL $url URL to retrieve.
     */
    public function __construct(URL $url)
    {
        $this->url = $url;
    }

    /**
     * Retrieve the content at the configured URL.
     *
     * @return string Response body.
     *
     * @throws \RuntimeException If cURL cannot be initialized, the request
     *                           fails, or the server returns an HTTP error.
     */
    public function fetch(): string
    {
        $curl = curl_init((string) $this->url);

        if ($curl === false) {
            throw new \RuntimeException(
                sprintf(
                    'Unable to initialize cURL for "%s".',
                    $this->url
                )
            );
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_MAXREDIRS => constant(
                'settings'
            )['crawler']['max_redirects'],

            CURLOPT_CONNECTTIMEOUT => constant(
                'settings'
            )['crawler']['connection_timeout'],

            CURLOPT_TIMEOUT => constant(
                'settings'
            )['crawler']['timeout'],

            CURLOPT_USERAGENT => constant(
                'settings'
            )['crawler']['user_agent'],
        ]);

        $content = curl_exec($curl);

        if ($content === false) {
            $error = curl_error($curl);

            curl_close($curl);

            throw new \RuntimeException(
                sprintf(
                    'Failed to retrieve "%s": %s',
                    $this->url,
                    $error
                )
            );
        }

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

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

    /**
     * Return the URL being crawled.
     *
     * @return URL URL instance.
     */
    public function getUrl(): URL
    {
        return $this->url;
    }
}

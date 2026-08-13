<?php

declare(strict_types=1);

namespace Framework\Search;

/**
 * Search Handler
 *
 * Represents and validates a search request.
 *
 * The handler is responsible for collecting, normalizing, and validating
 * search parameters. It does not perform the actual search.
 *
 * @author McSwampy <mcswampy@sylph.co.za>
 * @since 2.0.0
 * @copyright 2026 Sylph Syndicate
 *
 * @example
 * $search = Handler::fromRequest();
 * $search->getStart();       // int
 * $search->getResults();     // int
 * $search->getCategoryId();  // int|null
 * $search->isAdvanced();     // bool
 * $search->getLanguage();    // string
 * $search->getDomain();      // string|null
 */
class Handler
{
    /**
     * Default number of results.
     *
     * @var int
     */
    private const DEFAULT_RESULTS = 10;

    /**
     * Maximum number of results permitted.
     *
     * @var int
     */
    private const MAX_RESULTS = 100;

    /**
     * Maximum result offset permitted.
     *
     * @var int
     */
    private const MAX_START = 10000;

    /**
     * Search query.
     *
     * @var string
     */
    private string $query;

    /**
     * Search provider or search type.
     *
     * @var string|null
     */
    private ?string $search;

    /**
     * Requested language.
     *
     * @var string
     */
    private string $language;

    /**
     * Result offset.
     *
     * @var int
     */
    private int $start;

    /**
     * Domain restriction.
     *
     * @var string|null
     */
    private ?string $domain;

    /**
     * Result type restriction.
     *
     * @var string|null
     */
    private ?string $type;

    /**
     * Category ID.
     *
     * @var int|null
     */
    private ?int $categoryId;

    /**
     * Category restriction.
     *
     * @var string|null
     */
    private ?string $category;

    /**
     * Number of results requested.
     *
     * @var int
     */
    private int $results;

    /**
     * Whether advanced search is enabled.
     *
     * @var bool
     */
    private bool $advanced;

    /**
     * Create a search handler.
     *
     * @param array<string, mixed> $input Search parameters.
     *
     * @throws \InvalidArgumentException When a parameter is invalid.
     */
    public function __construct(array $input = [])
    {
        $this->query = $this->string(
            $input['query'] ?? ''
        );

        $this->search = $this->nullableString(
            $input['search'] ?? null
        );

        $this->language = $this->language(
            $input['lang'] ?? 'en'
        );

        $this->start = $this->boundedInteger(
            $input['start'] ?? 0,
            'start',
            0,
            self::MAX_START
        );

        $this->domain = $this->nullableString(
            $input['domain'] ?? null
        );

        $this->type = $this->nullableString(
            $input['type'] ?? null
        );

        $this->categoryId = $this->nullableInteger(
            $input['catid'] ?? null,
            'catid'
        );

        $this->category = $this->nullableString(
            $input['category'] ?? null
        );

        $this->results = $this->boundedInteger(
            $input['results'] ?? self::DEFAULT_RESULTS,
            'results',
            1,
            self::MAX_RESULTS
        );

        $this->advanced = $this->boolean(
            $input['adv'] ?? false
        );
    }

    /**
     * Create a search handler from the current HTTP request.
     *
     * POST parameters take precedence over GET parameters.
     *
     * @return static
     *
     * @throws \InvalidArgumentException When a parameter is invalid.
     */
    public static function fromRequest(): static
    {
        return new static([
            'query'    => $_POST['query'] ?? $_GET['query'] ?? '',
            'search'   => $_POST['search'] ?? $_GET['search'] ?? null,
            'lang'     => $_POST['lang'] ?? $_GET['lang'] ?? 'en',
            'start'    => $_POST['start'] ?? $_GET['start'] ?? 0,
            'domain'   => $_POST['domain'] ?? '',
            'type'     => $_POST['type'] ?? '',
            'catid'    => $_POST['catid'] ?? '',
            'category' => $_POST['category'] ?? '',
            'results'  => $_POST['results'] ?? self::DEFAULT_RESULTS,
            'adv'      => $_POST['adv'] ?? false,
        ]);
    }

    /**
     * Return the search query.
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Return the search provider or search type.
     *
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * Return the requested language.
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Return the result offset.
     *
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * Return the domain restriction.
     *
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Return the result type restriction.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Return the category ID.
     *
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * Return the category restriction.
     *
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * Return the requested result count.
     *
     * @return int
     */
    public function getResults(): int
    {
        return $this->results;
    }

    /**
     * Determine whether advanced search is enabled.
     *
     * @return bool
     */
    public function isAdvanced(): bool
    {
        return $this->advanced;
    }

    /**
     * Determine whether a query was supplied.
     *
     * @return bool
     */
    public function hasQuery(): bool
    {
        return $this->query !== '';
    }

    /**
     * Return the normalized search parameters.
     *
     * @return array{
     *     query: string,
     *     search: string|null,
     *     language: string,
     *     start: int,
     *     domain: string|null,
     *     type: string|null,
     *     category_id: int|null,
     *     category: string|null,
     *     results: int,
     *     advanced: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'query'       => $this->query,
            'search'      => $this->search,
            'language'    => $this->language,
            'start'       => $this->start,
            'domain'      => $this->domain,
            'type'        => $this->type,
            'category_id' => $this->categoryId,
            'category'    => $this->category,
            'results'     => $this->results,
            'advanced'    => $this->advanced,
        ];
    }

    /**
     * Normalize a string value.
     *
     * Non-scalar values are treated as empty strings.
     *
     * @param mixed $value Value to normalize.
     *
     * @return string
     */
    private function string(mixed $value): string
    {
        if (!is_scalar($value)) {
            return '';
        }

        return trim((string) $value);
    }

    /**
     * Normalize an optional string value.
     *
     * Empty strings are converted to null.
     *
     * @param mixed $value Value to normalize.
     *
     * @return string|null
     */
    private function nullableString(mixed $value): ?string
    {
        $value = $this->string($value);

        return $value === '' ? null : $value;
    }

    /**
     * Normalize and validate a language code.
     *
     * Supported formats include "en", "en-us", and "pt-br".
     *
     * @param mixed $value Language code.
     *
     * @return string
     *
     * @throws \InvalidArgumentException When the language is invalid.
     */
    private function language(mixed $value): string
    {
        $value = strtolower($this->string($value));

        if (!preg_match('/^[a-z]{2}(?:-[a-z]{2})?$/', $value)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid language code: "%s".', $value)
            );
        }

        return $value;
    }

    /**
     * Normalize and validate an integer within a permitted range.
     *
     * @param mixed $value Value to normalize.
     * @param string $name Parameter name.
     * @param int $minimum Minimum permitted value.
     * @param int $maximum Maximum permitted value.
     *
     * @return int
     *
     * @throws \InvalidArgumentException When the value is invalid.
     */
    private function boundedInteger(
        mixed $value,
        string $name,
        int $minimum,
        int $maximum
    ): int {
        $result = filter_var(
            $value,
            FILTER_VALIDATE_INT
        );

        if ($result === false || $result < $minimum || $result > $maximum) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid %s value: "%s". Expected a value between %d and %d.',
                    $name,
                    (string) $value,
                    $minimum,
                    $maximum
                )
            );
        }

        return $result;
    }

    /**
     * Normalize an optional integer.
     *
     * Empty values are converted to null.
     *
     * @param mixed $value Value to normalize.
     * @param string $name Parameter name.
     *
     * @return int|null
     *
     * @throws \InvalidArgumentException When the value is invalid.
     */
    private function nullableInteger(
        mixed $value,
        string $name
    ): ?int {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->boundedInteger(
            $value,
            $name,
            0,
            PHP_INT_MAX
        );
    }

    /**
     * Normalize a boolean value.
     *
     * @param mixed $value Value to normalize.
     *
     * @return bool
     *
     * @throws \InvalidArgumentException When the value is invalid.
     */
    private function boolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value)) {
            return match ($value) {
                0 => false,
                1 => true,
                default => throw new \InvalidArgumentException(
                    sprintf('Invalid boolean value: "%d".', $value)
                ),
            };
        }

        if (is_string($value)) {
            return match (strtolower(trim($value))) {
                '1', 'true', 'yes', 'on' => true,
                '0', 'false', 'no', 'off', '' => false,
                default => throw new \InvalidArgumentException(
                    sprintf('Invalid boolean value: "%s".', $value)
                ),
            };
        }

        throw new \InvalidArgumentException(
            'Invalid boolean value.'
        );
    }
}
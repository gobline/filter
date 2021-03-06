<?php

namespace Gobline\Filter\Sanitizer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class StripTags implements SanitizerInterface
{
    private $allowedTags;

    /**
     * @param string $allowedTags
     */
    public function __construct($allowedTags = '')
    {
        $this->allowedTags = (string) $allowedTags;
    }

    /**
     * {@inheritdoc}
     */
    public function sanitize($value)
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        return strip_tags($value, $this->allowedTags);
    }
}

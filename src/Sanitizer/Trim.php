<?php

namespace Gobline\Filter\Sanitizer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Trim implements SanitizerInterface
{
    private $chars;

    /**
     * @param string $chars
     */
    public function __construct($chars = ' ')
    {
        $this->chars = (string) $chars;
    }

    /**
     * {@inheritdoc}
     */
    public function sanitize($value)
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        return trim($value, $this->chars);
    }
}

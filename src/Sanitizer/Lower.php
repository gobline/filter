<?php

namespace Gobline\Filter\Sanitizer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Lower implements SanitizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function sanitize($value)
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        return strtolower($value);
    }
}

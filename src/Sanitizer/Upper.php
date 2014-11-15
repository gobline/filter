<?php

namespace Mendo\Filter\Sanitizer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Upper implements SanitizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function sanitize($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        return strtoupper($value);
    }
}

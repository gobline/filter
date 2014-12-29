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
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        return strtoupper($value);
    }
}

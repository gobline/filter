<?php

namespace Mendo\Filter\Sanitizer;

use Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Cast implements SanitizerInterface
{
    private $type;

    /**
     * @param string $allowedChars
     */
    public function __construct($type)
    {
        $type = (string) $type;

        switch ($type) {
            default:
                throw new \InvalidArgumentException('invalid type "'.$type.'"');
            case 'bool':
            case 'boolean':
            case 'int':
            case 'integer':
            case 'float':
            case 'string':
            case 'null':
        }

        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function sanitize($value)
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        switch ($this->type) {
            case 'bool':
            case 'boolean':
                if ((new Validator\Boolean())->isValid($value)) {
                    return (bool) $value;
                }
                break;
            case 'int':
            case 'integer':
                if ((new Validator\Float(true))->isValid($value) || is_bool($value)) {
                    return (int) $value;
                }
                break;
            case 'float':
                if ((new Validator\Float(true))->isValid($value) || is_bool($value)) {
                    return (float) $value;
                }
                break;
            case 'string':
                if (is_bool($value)) {
                    return $value ? '1' : '0';
                }

                return (string) $value;
            case 'null':
                return null;
        }

        throw new \RuntimeException('Cannot cast "'.$value.'" to '.$this->type);
    }
}

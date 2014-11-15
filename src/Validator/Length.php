<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Length extends AbstractValidator
{
    private $value;
    private $min;
    private $max;

    const TOO_SHORT = 'TOO_SHORT';
    const TOO_LONG = 'TOO_LONG';

    /**
     * @param int $min
     * @param int $max
     */
    public function __construct($min, $max = null)
    {
        parent::__construct();

        $this->min = (int) $min;

        if ($this->min < 0) {
            throw new \InvalidArgumentException('$min must be a positive integer');
        }

        if ($max !== null) {
            $this->max = (int) $max;

            if ($this->max < 0) {
                throw new \InvalidArgumentException('$max must be a positive integer');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->resetMessages();

        $this->value = $value;

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }

        if (strlen($value) < $this->min) {
            $this->addMessage(self::TOO_SHORT);

            return false;
        }

        if ($this->max !== null && strlen($value) > $this->max) {
            $this->addMessage(self::TOO_LONG);

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageTemplates()
    {
        return [
            self::TOO_SHORT => 'The input is less than %min% characters long',
            self::TOO_LONG  => 'The input is more than %max% characters long',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return [
            'value' => $this->value,
            'min' => $this->min,
            'max' => $this->max,
        ];
    }
}

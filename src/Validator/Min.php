<?php

namespace Gobline\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Min extends AbstractValidator
{
    private $value;
    private $min;

    /**
     * @param int|float $min
     */
    public function __construct($min)
    {
        parent::__construct();

        $this->min = (float) $min;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->resetMessages();

        $this->value = $value;

        if (!is_scalar($value) || is_bool($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('Unexpected value: '.$value);
        }

        if ($value < $this->min) {
            $this->addMessage();

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageTemplates()
    {
        return ['The input is not greater than %min%'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return [
            'value' => $this->value,
            'min' => $this->min,
        ];
    }
}

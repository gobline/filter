<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Max extends AbstractValidator
{
    private $value;
    private $max;

    /**
     * @param int|float $max
     */
    public function __construct($max)
    {
        parent::__construct();

        $this->max = (float) $max;
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

        if ($value > $this->max) {
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
        return ['The input is not less than %max%'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return [
            'value' => $this->value,
            'max' => $this->max,
        ];
    }
}

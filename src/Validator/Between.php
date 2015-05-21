<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Between extends AbstractValidator
{
    private $value;
    private $min;
    private $max;
    private $isStringComparison = false;

    /**
     * @param int|float|string $min
     * @param int|float|string $max
     */
    public function __construct($min, $max)
    {
        parent::__construct();

        $this->min = $min;
        $this->max = $max;

        if (
            is_string($min) && !is_numeric($min) &&
            is_string($max) && !is_numeric($max)
        ) {
            $this->isStringComparison = true;
        } else {
            $this->min = (float) $this->min;
            $this->max = (float) $this->max;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->resetMessages();

        $this->value = $value;

        if ($this->isStringComparison) {
            if (strcmp($this->value, $this->min) < 0) {
                $this->addMessage();

                return false;
            }
            if (strcmp($this->value, $this->max) > 0) {
                $this->addMessage();

                return false;
            }

            return true;
        }

        if (!is_scalar($value) || is_bool($value)) {
            throw new \InvalidArgumentException('Unexpected type: '.gettype($value));
        }
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('Unexpected value: '.$value);
        }

        if ($value < $this->min || $value > $this->max) {
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
        if ($this->isStringComparison) {
            return ['The input must be between "%min%" and "%max%"'];
        } else {
            return ['The input must be between %min% and %max%'];
        }
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

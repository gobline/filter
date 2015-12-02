<?php

namespace Gobline\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Float extends AbstractValidator
{
    private $value;
    private $allowNegative;

    /**
     * @param bool $allowNegative
     */
    public function __construct($allowNegative = false)
    {
        parent::__construct();

        $this->allowNegative = (bool) $allowNegative;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->resetMessages();

        $this->value = $value;

        if (!is_numeric($value)) {
            $this->addMessage();

            return false;
        }

        $value = (string) $value;

        if (substr_count($value, '.') > 1) {
            $this->addMessage();

            return false;
        }

        $value = str_replace('.', '', $value);

        if ($this->allowNegative) {
            $value = ltrim($value, '-');
        }

        if (!ctype_digit($value)) {
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
        return ['The input is not a valid number'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return ['value' => $this->value];
    }
}

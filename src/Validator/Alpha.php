<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Alpha extends AbstractValidator
{
    private $value;
    private $allowedChars;

    /**
     * @param string $allowedChars
     */
    public function __construct($allowedChars = '')
    {
        parent::__construct();

        $this->allowedChars = (string) $allowedChars;
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

        if ($this->allowedChars !== '') {
            $value = str_replace(str_split($this->allowedChars), '', $value);
        }

        if (!ctype_alpha($value)) {
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
        if ($this->allowedChars === '') {
            return ['The input must contain only letters'];
        } else {
            return ['The input contains invalid characters'];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return ['value' => $this->value];
    }
}

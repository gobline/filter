<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Email extends AbstractValidator
{
    private $value;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->resetMessages();

        $this->value = $value;

        if (!is_string($value)) {
            $this->addMessage();

            return false;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
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
        return ['The input is not a valid email address'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return ['value' => $this->value];
    }
}

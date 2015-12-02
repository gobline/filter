<?php

namespace Gobline\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Boolean extends AbstractValidator
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

        if (!is_scalar($value)) {
            $this->addMessage();

            return false;
        }

        $value = (string) $value;

        if ($value !== '1' && $value !== '0' && $value !== '') {
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
        return ['The input is not valid'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return ['value' => $this->value];
    }
}

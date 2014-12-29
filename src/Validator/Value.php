<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Value extends AbstractValidator
{
    private $value;
    private $allowedValues;

    /**
     * @param string $allowedTags
     */
    public function __construct(...$allowedValues)
    {
        parent::__construct();

        if (!$allowedValues) {
            throw new \InvalidArgumentException('__construct() expects at least 1 parameter');
        }

        $this->allowedValues = $allowedValues;
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

        if (!in_array($value, $this->allowedValues)) {
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
        return ['The input content is not valid'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessageVariables()
    {
        return ['value' => $this->value];
    }
}

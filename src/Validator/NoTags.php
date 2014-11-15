<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class NoTags extends AbstractValidator
{
    private $value;
    private $allowedTags;

    /**
     * @param string $allowedTags
     */
    public function __construct($allowedTags = '')
    {
        parent::__construct();

        $this->allowedTags = (string) $allowedTags;
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

        if (strlen($value) !== strlen(strip_tags($value, $this->allowedTags))) {
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

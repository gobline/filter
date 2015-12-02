<?php

namespace Gobline\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Regex extends AbstractValidator
{
    private $value;
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        parent::__construct();

        $this->pattern = (string) $pattern;
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

        $value = (string) $value;

        $result = preg_match($this->pattern, $value);

        if ($result === false) {
            throw new \RuntimeException('Pattern "'.$this->pattern.'" gave an error for value "'.$value.'"');
        }

        if (!$result) {
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

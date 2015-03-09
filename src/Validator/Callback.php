<?php

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Callback extends AbstractValidator
{
    private $value;
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        parent::__construct();

        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        $this->resetMessages();

        $this->value = $value;

        if (!call_user_func($this->callback)) {
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
        return [
            'value' => $this->value,
        ];
    }
}

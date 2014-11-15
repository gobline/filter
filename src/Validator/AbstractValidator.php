<?php

namespace Mendo\Filter\Validator;

use Mendo\Translator\TranslatorInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
abstract class AbstractValidator implements ValidatorInterface
{
    private $messages = [];
    private $messageTemplates = [];
    private $translator;
    protected static $defaultTranslator;

    public function __construct()
    {
        $templates = $this->getMessageTemplates();

        if (!is_array($templates)) {
            $templates = [(string) $templates];
        }

        $this->messageTemplates = $templates;
    }

    /**
     * @param mixed $templateKey
     */
    public function addMessage($templateKey = null)
    {
        $message = $templateKey ?
            $this->messageTemplates[$templateKey] :
            current($this->messageTemplates);

        if (!in_array($message, $this->messages)) {
            $this->messages[] = $message;
        }
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {
        return (bool) $this->messages;
    }

    public function resetMessages()
    {
        $this->messages = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages($language = null)
    {
        if (!$this->messages) {
            return [];
        }

        $messages = [];
        try {
            do {
                $messages[] = $this->getMessage($language);
                next($this->messages);
            } while (current($this->messages));
        } finally {
            reset($this->messages);
        }

        return $messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage($language = null)
    {
        if (!$this->messages) {
            return null;
        }

        $message = $this->translateMessage(current($this->messages), $language);

        $messageVariables = $this->getMessageVariables();
        foreach ($messageVariables as $name => $value) {
            $message = str_replace('%'.$name.'%', $value, $message);
        }

        return $message;
    }

    /**
     * @return string[]
     */
    abstract protected function getMessageTemplates();

    /**
     * @return array
     */
    abstract protected function getMessageVariables();

    /**
     * {@inheritdoc}
     */
    public function setMessageTemplate($message, $key = null)
    {
        $message = (string) $message;

        if ($key === null) {
            $keys = array_keys($this->messageTemplates);
            foreach ($keys as $key) {
                $this->setMessageTemplate($message, $key);
            }

            return $this;
        } else {
            $this->messageTemplates[$key] = $message;
        }
    }

    /**
     * @param string $message
     * @param string $language
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function translateMessage($message, $language = null)
    {
        if ($language !== null) {
            $language = (string) $language;
            if ($language === '') {
                throw new \InvalidArgumentException('$language cannot be empty');
            }
        }

        $translator = $this->getTranslator();

        if (!$translator) {
            return $message;
        }

        return $translator->translate($message, null, $language);
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        if ($this->translator) {
            return $this->translator;
        }

        if (static::$defaultTranslator) {
            return static::$defaultTranslator;
        }

        return null;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param TranslatorInterface $translator
     */
    public static function setDefaultTranslator(TranslatorInterface $translator)
    {
        static::$defaultTranslator = $translator;
    }
}

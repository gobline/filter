<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Filter;

use Gobline\Filter\Validator\ValidatorInterface;
use Gobline\Filter\Sanitizer\SanitizerInterface;
use Gobline\Translator\Translator;

/**
 * Allows to filter a variable through multiple sanitizers and validators at once.
 *
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FilterFunnel
{
    private $chain = [];
    private $filterClassMap;
    private $messages = [];
    private $optional = false;

    public function __construct(FilterClassMap $filterClassMap = null)
    {
        $this->filterClassMap = $filterClassMap ?: new FilterClassMap();
    }

    /**
     * @param ValidatorInterface|string $validator
     * @param mixed                     $params
     *
     * @throws \InvalidArgumentException
     *
     * @return FilterFunnel
     */
    public function addValidator($validator, ...$params)
    {
        if (!$validator instanceof ValidatorInterface) {
            $validator = $this->filterClassMap->getValidator($validator);
            $validator = new $validator(...$params);
        }
        $this->chain[] = $validator;

        return $this;
    }

    /**
     * @param SanitizerInterface|string $sanitizer
     * @param mixed                     $params
     *
     * @throws \InvalidArgumentException
     *
     * @return FilterFunnel
     */
    public function addSanitizer($sanitizer, ...$params)
    {
        if (!$sanitizer instanceof SanitizerInterface) {
            $sanitizer = $this->filterClassMap->getSanitizer($sanitizer);
            $sanitizer = new $sanitizer(...$params);
        }
        $this->chain[] = $sanitizer;

        return $this;
    }

    public function add($filter, ...$params)
    {
        if ($this->filterClassMap->hasValidator($filter)) {
            return $this->addValidator($filter, ...$params);
        } elseif ($this->filterClassMap->hasSanitizer($filter)) {
            return $this->addSanitizer($filter, ...$params);
        }

        throw new \RuntimeException('filter "'.$filter.'" not found');
    }

    public function __call($name, array $params)
    {
        return $this->add($name, ...$params);
    }

    public function __get($name)
    {
        return $this->add($name);
    }

    public function setOptional()
    {
        $this->optional = true;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public function filter($value, $filters = null)
    {
        $this->messages = [];

        if ($this->optional && ($value === null || (is_string($value) && trim($value) === ''))) {
            return null;
        }

        foreach ($this->chain as $filter) {
            if ($filter instanceof ValidatorInterface) {
                if ($filter->isValid($value)) {
                    continue;
                }
                $this->messages = $filter->getMessages();

                return null;
            } elseif ($filter instanceof SanitizerInterface) {
                $value = $filter->sanitize($value);
            }
        }

        if ($filters) {
            $filters = explode('|', (string) $filters);

            if ($filters && current($filters) === 'optional') {
                array_shift($filters);
                if ($value === null || (is_string($value) && trim($value) === '')) {
                    return null;
                }
            }

            foreach ($filters as $filter) {
                $filter = trim($filter);
                $name = $filter;
                $params = [];

                if (strpos($filter,'(') !== false) {
                    $name = trim(substr($filter, 0, strpos($filter,'(')));
                    $params = substr($filter, strpos($filter,'(') + 1, -1);
                    $params = explode(',', $params);
                }

                if ($this->filterClassMap->hasValidator($name)) {
                    $validator = $this->filterClassMap->getValidator($name);
                    $validator = new $validator(...$params);
                    if ($validator->isValid($value)) {
                        continue;
                    }
                    $this->messages = $validator->getMessages();

                    return null;
                } elseif ($this->filterClassMap->hasSanitizer($name)) {
                    $sanitizer = $this->filterClassMap->getSanitizer($name);
                    $sanitizer = new $sanitizer(...$params);
                    $value = $sanitizer->sanitize($value);
                } else {
                    throw new \RuntimeException('filter "'.$name.'" not found');
                }
            }
        }

        return $value;
    }

    /**
     * @param string $message
     * @param mixed  $key
     *
     * @throws \RuntimeException
     *
     * @return FilterFunnel
     */
    public function setMessageTemplate($message, $key = null)
    {
        $validator = end($this->chain);
        if (!$validator instanceof ValidatorInterface) {
            throw new \RuntimeException('setMessageTemplate() must be preceeded by addValidator()');
        }
        $validator->setMessageTemplate($message, $key);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasMessages()
    {
        return (bool) $this->messages;
    }

    /**
     * @return string[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        if (!$this->messages) {
            return null;
        }

        return current($this->messages);
    }

    /**
     * @return FilterClassMap
     */
    public function getFilterClassMap()
    {
        return $this->filterClassMap;
    }

    public function setDefaultTranslator(Translator $translator)
    {
        AbstractValidator::setDefaultTranslator($translator);
    }
}

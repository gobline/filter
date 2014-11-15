<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Filter;

use Mendo\Filter\Validator\ValidatorInterface;
use Mendo\Filter\Sanitizer\SanitizerInterface;

/**
 * Allows to filter a variable through multiple sanitizers and validators at once.
 *
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FilterFunnel
{
    private $chain = [];
    private $filterClassMap;
    private $messages;

    public function __construct()
    {
        $this->filterClassMap = new FilterClassMap();
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

    /**
     * @param mixed $value
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function filter($value)
    {
        $this->messages = [];
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
     * @param FilterClassMap $map
     */
    public function setFilterClassMap(FilterClassMap $map)
    {
        return $this->filterClassMap = $map;
    }
}

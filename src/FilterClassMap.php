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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FilterClassMap
{
    private $validators;
    private $sanitizers;

    public function __construct()
    {
        $this->validators = [
            'alpha' => '\\Gobline\\Filter\\Validator\\Alpha',
            'alphanum' => '\\Gobline\\Filter\\Validator\\Alphanum',
            'between' => '\\Gobline\\Filter\\Validator\\Between',
            'boolean' => '\\Gobline\\Filter\\Validator\\Boolean',
            'callback' => '\\Gobline\\Filter\\Validator\\Callback',
            'email' => '\\Gobline\\Filter\\Validator\\Email',
            'float' => '\\Gobline\\Filter\\Validator\\Float',
            'int' => '\\Gobline\\Filter\\Validator\\Int',
            'length' => '\\Gobline\\Filter\\Validator\\Length',
            'max' => '\\Gobline\\Filter\\Validator\\Max',
            'min' => '\\Gobline\\Filter\\Validator\\Min',
            'notags' => '\\Gobline\\Filter\\Validator\\NoTags',
            'regex' => '\\Gobline\\Filter\\Validator\\Regex',
            'required' => '\\Gobline\\Filter\\Validator\\Required',
            'uuid' => '\\Gobline\\Filter\\Validator\\Uuid',
            'value' => '\\Gobline\\Filter\\Validator\\Value',
        ];

        $this->sanitizers = [
            'cast' => '\\Gobline\\Filter\\Sanitizer\\Cast',
            'lower' => '\\Gobline\\Filter\\Sanitizer\\Lower',
            'ltrim' => '\\Gobline\\Filter\\Sanitizer\\LTrim',
            'rtrim' => '\\Gobline\\Filter\\Sanitizer\\RTrim',
            'striptags' => '\\Gobline\\Filter\\Sanitizer\\StripTags',
            'trim' => '\\Gobline\\Filter\\Sanitizer\\Trim',
            'upper' => '\\Gobline\\Filter\\Sanitizer\\Upper',
        ];
    }

    /**
     * @param string $name
     * @param string $validator
     *
     * @throws \InvalidArgumentException
     */
    public function addValidator($name, $validator)
    {
        $name = (string) $name;
        if ($name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        $validator = (string) $validator;
        if ($validator === '') {
            throw new \InvalidArgumentException('$validator cannot be empty');
        }

        $this->validators[$name] = $validator;
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return ValidatorInterface
     */
    public function getValidator($name)
    {
        if (!$this->hasValidator($name)) {
            throw new \InvalidArgumentException('validator "'.$name.'" not found');
        }

        return $this->validators[$name];
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function hasValidator($name)
    {
        if ((string) $name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        return isset($this->validators[$name]);
    }

    /**
     * @param string $name
     * @param string $validator
     *
     * @throws \InvalidArgumentException
     */
    public function addSanitizer($name, $sanitizer)
    {
        $name = (string) $name;
        if ($name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        $sanitizer = (string) $sanitizer;
        if ($sanitizer === '') {
            throw new \InvalidArgumentException('$sanitizer cannot be empty');
        }

        $this->sanitizers[$name] = $sanitizer;
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return SanitizerInterface
     */
    public function getSanitizer($name)
    {
        if (!$this->hasSanitizer($name)) {
            throw new \InvalidArgumentException('sanitizer "'.$name.'" not found');
        }

        return $this->sanitizers[$name];
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function hasSanitizer($name)
    {
        if ((string) $name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        return isset($this->sanitizers[$name]);
    }
}

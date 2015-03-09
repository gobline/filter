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
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FilterClassMap
{
    private $validators;
    private $sanitizers;

    public function __construct()
    {
        $this->validators = [
            'alpha' => '\\Mendo\\Filter\\Validator\\Alpha',
            'alphanum' => '\\Mendo\\Filter\\Validator\\Alphanum',
            'between' => '\\Mendo\\Filter\\Validator\\Between',
            'boolean' => '\\Mendo\\Filter\\Validator\\Boolean',
            'callback' => '\\Mendo\\Filter\\Validator\\Callback',
            'email' => '\\Mendo\\Filter\\Validator\\Email',
            'float' => '\\Mendo\\Filter\\Validator\\Float',
            'int' => '\\Mendo\\Filter\\Validator\\Int',
            'length' => '\\Mendo\\Filter\\Validator\\Length',
            'max' => '\\Mendo\\Filter\\Validator\\Max',
            'min' => '\\Mendo\\Filter\\Validator\\Min',
            'notags' => '\\Mendo\\Filter\\Validator\\NoTags',
            'regex' => '\\Mendo\\Filter\\Validator\\Regex',
            'required' => '\\Mendo\\Filter\\Validator\\Required',
            'value' => '\\Mendo\\Filter\\Validator\\Value',
        ];

        $this->sanitizers = [
            'cast' => '\\Mendo\\Filter\\Sanitizer\\Cast',
            'lower' => '\\Mendo\\Filter\\Sanitizer\\Lower',
            'ltrim' => '\\Mendo\\Filter\\Sanitizer\\LTrim',
            'rtrim' => '\\Mendo\\Filter\\Sanitizer\\RTrim',
            'striptags' => '\\Mendo\\Filter\\Sanitizer\\StripTags',
            'trim' => '\\Mendo\\Filter\\Sanitizer\\Trim',
            'upper' => '\\Mendo\\Filter\\Sanitizer\\Upper',
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

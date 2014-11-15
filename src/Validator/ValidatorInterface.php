<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Filter\Validator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function isValid($value);

    /**
     * @param string $language
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getMessage($language = null);

    /**
     * @param string $language
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    public function getMessages($language = null);

    /**
     * @param string $message
     * @param mixed  $key
     */
    public function setMessageTemplate($message, $key = null);
}

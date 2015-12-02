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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class ObjectFilter
{
    private $filterFunnelFactory;
    private $messages = [];

    /**
     * @param FilterClassMap $filterClassMap
     */
    public function __construct(FilterFunnelFactory $filterFunnelFactory = null)
    {
        $this->filterFunnelFactory = $filterFunnelFactory ?: new FilterFunnelFactory();
    }

    /**
     * @param FilterableInterface $object
     *
     * @throws \RuntimeException
     *
     * @return FilterableInterface|null
     */
    public function filter(FilterableInterface $object)
    {
        $this->messages = [];

        $rules = $object->getRules();

        foreach ($rules as $property => $filters) {
            if ((string) $property === '') {
                throw new \RuntimeException(
                    get_class($object).'->getRules() returns an invalid array format (property name cannot be empty)');
            }

            $filterArrayElements = false;
            if (strpos($property, '[') !== false) {
                $property = substr($property, 0, -2);
                $filterArrayElements = true;
            }

            if (is_callable([$object, 'get'.ucfirst($property)])) {
                $value = $object->{'get'.ucfirst($property)}();
            } elseif (is_callable([$object, 'is'.ucfirst($property)])) {
                $value = $object->{'is'.ucfirst($property)}();
            } elseif ((new \ReflectionObject($object))->getProperty($property)->isPublic()) {
                $value = $object->$property;
            } else {
                throw new \RuntimeException(
                    get_class($object).'->getRules() returns an invalid array format (property "'.$property.'" has no accessor)');
            }

            $filterFunnel = $this->filterFunnelFactory->createFunnel();

            if (is_array($filters)) {
                foreach ($filters as $filter) {
                    $name = null;
                    $params = null;
                    $type = null;
                    $message = null;

                    if (is_array($filter)) {
                        if (isset($filter['name'])) {
                            $name = $filter['name'];
                        } elseif (isset($filter[0])) {
                            $name = $filter[0];
                        } else {
                            throw new \RuntimeException(
                                get_class($object).'->getRules() returns an invalid array format (filter name required)');
                        }

                        if ((string) $name === '') {
                            throw new \RuntimeException(
                                get_class($object).'->getRules() returns an invalid array format (filter name cannot be empty)');
                        }

                        if (isset($filter['params'])) {
                            $params = $filter['params'];
                        } elseif (isset($filter[1])) {
                            $params = $filter[1];
                        } else {
                            $params = [];
                        }

                        if (!is_array($params)) {
                            throw new \RuntimeException(
                                get_class($object).'->getRules() returns an invalid array format (filter params must be array)');
                        }

                        if (isset($filter['message'])) {
                            $message = $filter['message'];
                        } elseif (isset($filter[2])) {
                            $message = $filter[2];
                        }

                        if (isset($filter['type'])) {
                            $type = $filter['type'];
                        }
                    } else {
                        if ($filter === 'optional') {
                            $filterFunnel->setOptional();
                            continue;
                        }

                        $name = $filter;
                        $params = [];
                    }

                    if (!$type) {
                        $filterFunnel->add($name, ...$params);
                    } else {
                        switch ($type) {
                            default:
                                throw new \RuntimeException(
                                    get_class($object).'->getRules() returns an invalid array format (filter type invalid)');
                            case 'validator':
                                $filterFunnel->addValidator($name, ...$params);
                                break;
                            case 'sanitizer':
                                $filterFunnel->addSanitizer($name, ...$params);
                                break;
                        }
                    }

                    if ($message) {
                        $filterFunnel->setMessageTemplate($message);
                    }
                } // end foreach

                $filters = null;
            }

            $updateProperty = false;

            if ($filterArrayElements) {
                if (!is_array($value)) {
                    throw new \RuntimeException(
                        get_class($object).'->getRules() returns an invalid array format (invalid array property)');
                }

                $sanitizedValue = [];
                foreach ($value as $k => $v) {
                    $sanitizedValue[$k] = $filterFunnel->filter($v, $filters);

                    if ($sanitizedValue[$k] !== $v) {
                        $updateProperty = true;
                    }

                    if ($filterFunnel->hasMessages()) {
                        $this->messages[$property][$k] = $filterFunnel->getMessages();
                    }
                }
            } else {
                $sanitizedValue = $filterFunnel->filter($value, $filters);

                $updateProperty = ($sanitizedValue !== $value);

                if ($filterFunnel->hasMessages()) {
                    $this->messages[$property] = $filterFunnel->getMessages();
                }
            }

            if ($updateProperty) {
                $setter = 'set'.ucfirst($property);
                if (is_callable([$object, $setter])) {
                    $object->$setter($sanitizedValue);
                } elseif ((new \ReflectionObject($object))->getProperty($property)->isPublic()) {
                    $object->$property = $sanitizedValue;
                } else {
                    throw new \RuntimeException(
                        get_class($object).'->getRules() returns an invalid array format (property "'.$property.'" has no setter)');
                }
            }
        } // end foreach

        return ($this->hasMessages()) ? null : $object;
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
}

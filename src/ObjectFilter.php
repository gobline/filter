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

            if (!is_array($filters)) {
                $sanitizedValue = $filterFunnel->filter($value, $filters);
            } else {
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
                }

                $sanitizedValue = $filterFunnel->filter($value);
            }

            if ($filterFunnel->hasMessages()) {
                $this->messages[$property] = $filterFunnel->getMessages();
            }

            if ($sanitizedValue !== $value) {
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
        }

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

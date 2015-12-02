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
class ObjectFilterFactory
{
    private $filterFunnelFactory;

    /**
     * @param FilterFunnelFactory $filterFunnelFactory
     */
    public function __construct(FilterFunnelFactory $filterFunnelFactory = null)
    {
        $this->filterFunnelFactory = $filterFunnelFactory;
    }

    /**
     * @return ObjectFilter
     */
    public function createOjectFilter()
    {
        return new ObjectFilter($this->filterFunnelFactory);
    }
}

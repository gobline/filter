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
class FilterFunnelFactory
{
    private $filterClassMap;

    /**
     * @param FilterClassMap $filterClassMap
     */
    public function __construct(FilterClassMap $filterClassMap = null)
    {
        $this->filterClassMap = $filterClassMap;
    }

    /**
     * @return FilterFunnel
     */
    public function createFunnel()
    {
        return new FilterFunnel($this->filterClassMap);
    }
}

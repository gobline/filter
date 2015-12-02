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

use Gobline\Translator\Translator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FilterFunnelFactory
{
    private $filterClassMap;
    private $translator;

    /**
     * @return FilterFunnel
     */
    public function createFunnel()
    {
        $filterFunnel = new FilterFunnel($this->filterClassMap);

        if ($this->translator) {
            $filterFunnel->setDefaultTranslator($this->translator);
        }

        return $filterFunnel;
    }

    /**
     * @param FilterClassMap $filterClassMap
     */
    public function setFilterClassMap(FilterClassMap $filterClassMap)
    {
        $this->filterClassMap = $filterClassMap;
    }

    public function setDefaultTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }
}

<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Filter\Provider\Pimple;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Mendo\Filter\FilterFunnelFactory;
use Mendo\Filter\Validator\AbstractValidator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FunnelFactoryServiceProvider implements ServiceProviderInterface
{
    private $reference;

    public function __construct($reference = 'funnelFactory')
    {
        $this->reference = $reference;
    }

    public function register(Container $container)
    {
        $container[$this->reference.'.translator'] = null;

        $container[$this->reference] = function ($c) {
            if (!empty($c[$this->reference.'.translator'])) {
                if (empty($c[$c[$this->reference.'.translator']])) {
                    throw new \Exception('Dependency "'.$this->reference.'.translator" not found');
                }
                AbstractValidator::setDefaultTranslator($translator);
            }

            return new FilterFunnelFactory();
        };
    }
}

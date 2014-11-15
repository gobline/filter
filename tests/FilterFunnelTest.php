<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Filter\FilterFunnel;
use Mendo\Filter\FilterFunnelFactory;
use Mendo\Filter\FilterClassMap;
use Mendo\Filter\Sanitizer;
use Mendo\Filter\Validator;
use Mendo\Filter\Validator\AbstractValidator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class FilterFunnelTest extends PHPUnit_Framework_TestCase
{
    public function testFilterFunnel()
    {
        $funnel = (new FilterFunnel())
            ->addSanitizer(new Sanitizer\Trim())
            ->addValidator(new Validator\Int())
            ->addValidator(new Validator\Between(1, 100))
            ->addSanitizer(new Sanitizer\Cast('int'));

        $this->assertSame(42, $funnel->filter(' 42 '));
        $this->assertSame(null, $funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());

        $this->assertSame(null, $funnel->filter('foo'));
        $this->assertSame('The input is not a valid number', $funnel->getMessage());
        $this->assertSame(['The input is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelClassMap()
    {
        $funnel = (new FilterFunnel())
            ->addSanitizer('trim')
            ->addValidator('int')
            ->addValidator('between', 1, 100)
            ->addSanitizer('cast', 'int');

        $this->assertSame(42, $funnel->filter(' 42 '));
        $this->assertSame(null, $funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());

        $this->assertSame(null, $funnel->filter('foo'));
        $this->assertSame('The input is not a valid number', $funnel->getMessage());
        $this->assertSame(['The input is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelCustomMessage()
    {
        $funnel = (new FilterFunnel())
            ->addSanitizer('trim')
            ->addValidator('int')
            ->setMessageTemplate('"%value%" is not a valid number')
            ->addValidator('between', 1, 100)
            ->addSanitizer('cast', 'int');

        $this->assertSame(42, $funnel->filter(' 42 '));
        $this->assertSame(null, $funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());

        $this->assertSame(null, $funnel->filter('foo'));
        $this->assertSame('"foo" is not a valid number', $funnel->getMessage());
        $this->assertSame(['"foo" is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelFactoryCustomFilter()
    {
        $map = new FilterClassMap();
        $map->addValidator('dummy', 'DummyValidator');

        $factory = new FilterFunnelFactory($map);

        $funnel = $factory->createFunnel()
            ->addValidator('dummy')
            ->addSanitizer('cast', 'int');

        $this->assertSame(42, $funnel->filter('42'));
    }
}

class DummyValidator extends AbstractValidator
{
    public function isValid($value)
    {
        return true;
    }

    protected function getMessageTemplates()
    {
        return [];
    }

    protected function getMessageVariables()
    {
        return [];
    }
}

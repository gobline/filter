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
        $this->assertNull($funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());
        $this->assertFalse($funnel->hasMessages());

        $this->assertNull($funnel->filter('foo'));
        $this->assertSame('The input is not a valid number', $funnel->getMessage());
        $this->assertSame(['The input is not a valid number'], $funnel->getMessages());

        $this->assertNull($funnel->filter(''));
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
        $this->assertNull($funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());
        $this->assertFalse($funnel->hasMessages());

        $this->assertNull($funnel->filter('foo'));
        $this->assertSame('The input is not a valid number', $funnel->getMessage());
        $this->assertSame(['The input is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelMagicFilters()
    {
        $funnel = (new FilterFunnel())
            ->trim
            ->int
            ->between(1, 100)
            ->cast('int');

        $this->assertSame(42, $funnel->filter(' 42 '));
        $this->assertNull($funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());
        $this->assertFalse($funnel->hasMessages());

        $this->assertNull($funnel->filter('foo'));
        $this->assertSame('The input is not a valid number', $funnel->getMessage());
        $this->assertSame(['The input is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelStringFilters()
    {
        $funnel = new FilterFunnel();

        $this->assertSame(42, $funnel->filter(' 42 ', 'trim|int|between(1,100)|cast(int)'));
        $this->assertNull($funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());
        $this->assertFalse($funnel->hasMessages());

        $this->assertNull($funnel->filter('foo', 'trim|int|between(1,100)|cast(int)'));
        $this->assertSame('The input is not a valid number', $funnel->getMessage());
        $this->assertSame(['The input is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelOptional()
    {
        $funnel = (new FilterFunnel())
            ->addSanitizer(new Sanitizer\Trim())
            ->addValidator(new Validator\Int())
            ->addValidator(new Validator\Between(1, 100))
            ->addSanitizer(new Sanitizer\Cast('int'))
            ->setOptional();

        $this->assertNull($funnel->filter(''));
        $this->assertNull($funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());
        $this->assertFalse($funnel->hasMessages());

        $this->assertNull((new FilterFunnel())->filter('  ', 'optional|trim|int|between(1,100)|cast(int)'));
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
        $this->assertNull($funnel->getMessage());
        $this->assertSame([], $funnel->getMessages());
        $this->assertFalse($funnel->hasMessages());

        $this->assertNull($funnel->filter('foo'));
        $this->assertSame('"foo" is not a valid number', $funnel->getMessage());
        $this->assertSame(['"foo" is not a valid number'], $funnel->getMessages());
    }

    public function testFilterFunnelFactoryCustomFilter()
    {
        $map = new FilterClassMap();
        $map->addValidator('dummy', 'DummyValidator');

        $factory = new FilterFunnelFactory();
        $factory->setFilterClassMap($map);

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

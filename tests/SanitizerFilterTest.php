<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Filter\Sanitizer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class SanitizerFilterTest extends PHPUnit_Framework_TestCase
{
    public function testTrimSanitizer()
    {
        $this->assertSame('hello world', (new Sanitizer\Trim())->sanitize(' hello world   '));
        $this->assertSame('hello world', (new Sanitizer\Trim('_'))->sanitize('_hello world___'));
    }

    public function testTrimSanitizerValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Trim())->sanitize(42);
    }

    public function testTrimSanitizerValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Trim())->sanitize(true);
    }

    public function testTrimSanitizerValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Trim())->sanitize(null);
    }

    public function testTrimSanitizerValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Trim())->sanitize([]);
    }

    public function testLTrimSanitizer()
    {
        $this->assertSame('hello world   ', (new Sanitizer\LTrim())->sanitize(' hello world   '));
    }

    public function testLTrimSanitizerValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\LTrim())->sanitize(42);
    }

    public function testLTrimSanitizerValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\LTrim())->sanitize(true);
    }

    public function testLTrimSanitizerValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\LTrim())->sanitize(null);
    }

    public function testLTrimSanitizerValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\LTrim())->sanitize([]);
    }

    public function testRTrimSanitizer()
    {
        $this->assertSame(' hello world', (new Sanitizer\RTrim())->sanitize(' hello world   '));
    }

    public function testRTrimSanitizerValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\RTrim())->sanitize(42);
    }

    public function testRTrimSanitizerValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\RTrim())->sanitize(true);
    }

    public function testRTrimSanitizerValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\RTrim())->sanitize(null);
    }

    public function testRTrimSanitizerValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\RTrim())->sanitize([]);
    }

    public function testLowerSanitizer()
    {
        $this->assertSame(' hello world 42 ', (new Sanitizer\Lower())->sanitize(' HeLLo WoRLD 42 '));
    }

    public function testLowerSanitizerValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Lower())->sanitize(42);
    }

    public function testLowerSanitizerValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Lower())->sanitize(true);
    }

    public function testLowerSanitizerValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Lower())->sanitize(null);
    }

    public function testLowerSanitizerValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Lower())->sanitize([]);
    }

    public function testUpperSanitizer()
    {
        $this->assertSame(' HELLO WORLD 42 ', (new Sanitizer\Upper())->sanitize(' HeLLo WoRLD 42 '));
    }

    public function testUpperSanitizerValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Upper())->sanitize(42);
    }

    public function testUpperSanitizerValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Upper())->sanitize(true);
    }

    public function testUpperSanitizerValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Upper())->sanitize(null);
    }

    public function testUpperSanitizerValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Upper())->sanitize([]);
    }

    public function testStripTagsSanitizer()
    {
        $this->assertSame(' hello world  ', (new Sanitizer\StripTags())->sanitize('<span> hello</span> <a href="#">world </a> '));
        $this->assertSame(' hello world  ', (new Sanitizer\StripTags())->sanitize('<span> hello <a href="#">world </a> '));
        $this->assertSame(' hello <a href="#">world </a> ', (new Sanitizer\StripTags('<a>'))->sanitize('<span> hello <a href="#">world </a> '));
    }

    public function testStripTagsSanitizerValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\StripTags())->sanitize(42);
    }

    public function testStripTagsSanitizerValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\StripTags())->sanitize(true);
    }

    public function testStripTagsSanitizerValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\StripTags())->sanitize(null);
    }

    public function testStripTagsSanitizerValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\StripTags())->sanitize([]);
    }

    public function testCastSanitizer()
    {
        $this->assertSame('foo', (new Sanitizer\Cast('string'))->sanitize('foo'));
        $this->assertSame('5', (new Sanitizer\Cast('string'))->sanitize(5));
        $this->assertSame('5.2', (new Sanitizer\Cast('string'))->sanitize(5.2));
        $this->assertSame('1', (new Sanitizer\Cast('string'))->sanitize(true));
        $this->assertSame('0', (new Sanitizer\Cast('string'))->sanitize(false));

        $this->assertSame(true, (new Sanitizer\Cast('bool'))->sanitize(true));
        $this->assertSame(false, (new Sanitizer\Cast('bool'))->sanitize(false));
        $this->assertSame(true, (new Sanitizer\Cast('bool'))->sanitize(1));
        $this->assertSame(false, (new Sanitizer\Cast('bool'))->sanitize(0));
        $this->assertSame(true, (new Sanitizer\Cast('bool'))->sanitize('1'));
        $this->assertSame(false, (new Sanitizer\Cast('bool'))->sanitize('0'));
        $this->assertSame(false, (new Sanitizer\Cast('bool'))->sanitize(''));

        $this->assertSame(5, (new Sanitizer\Cast('int'))->sanitize(5));
        $this->assertSame(-5, (new Sanitizer\Cast('int'))->sanitize(-5));
        $this->assertSame(5, (new Sanitizer\Cast('int'))->sanitize(5.2));
        $this->assertSame(-5, (new Sanitizer\Cast('int'))->sanitize(-5.2));
        $this->assertSame(5, (new Sanitizer\Cast('int'))->sanitize('5'));
        $this->assertSame(-5, (new Sanitizer\Cast('int'))->sanitize('-5'));
        $this->assertSame(5, (new Sanitizer\Cast('int'))->sanitize('5.2'));
        $this->assertSame(-5, (new Sanitizer\Cast('int'))->sanitize('-5.2'));
        $this->assertSame(5, (new Sanitizer\Cast('int'))->sanitize('005'));
        $this->assertSame(0, (new Sanitizer\Cast('int'))->sanitize('00'));
        $this->assertSame(1, (new Sanitizer\Cast('int'))->sanitize(true));
        $this->assertSame(0, (new Sanitizer\Cast('int'))->sanitize(false));

        $this->assertSame(5.0, (new Sanitizer\Cast('float'))->sanitize(5));
        $this->assertSame(-5.0, (new Sanitizer\Cast('float'))->sanitize(-5));
        $this->assertSame(5.2, (new Sanitizer\Cast('float'))->sanitize(5.2));
        $this->assertSame(-5.2, (new Sanitizer\Cast('float'))->sanitize(-5.2));
        $this->assertSame(5.0, (new Sanitizer\Cast('float'))->sanitize('5'));
        $this->assertSame(-5.0, (new Sanitizer\Cast('float'))->sanitize('-5'));
        $this->assertSame(5.2, (new Sanitizer\Cast('float'))->sanitize('5.2'));
        $this->assertSame(-5.2, (new Sanitizer\Cast('float'))->sanitize('-5.2'));
        $this->assertSame(5.0, (new Sanitizer\Cast('float'))->sanitize('005'));
        $this->assertSame(0.0, (new Sanitizer\Cast('float'))->sanitize('00'));
        $this->assertSame(1.0, (new Sanitizer\Cast('float'))->sanitize(true));
        $this->assertSame(0.0, (new Sanitizer\Cast('float'))->sanitize(false));

        $this->assertSame(null, (new Sanitizer\Cast('null'))->sanitize(true));
        $this->assertSame(null, (new Sanitizer\Cast('null'))->sanitize(5));
        $this->assertSame(null, (new Sanitizer\Cast('null'))->sanitize('foo'));
    }

    public function testCastSanitizerFromArrayToString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Cast('string'))->sanitize(['a']);
    }

    public function testCastSanitizerFromAlphaStringToBool()
    {
        $this->setExpectedException('\RuntimeException', 'cast');
        (new Sanitizer\Cast('bool'))->sanitize('a');
    }

    public function testCastSanitizerFromIntToBool()
    {
        $this->setExpectedException('\RuntimeException', 'cast');
        (new Sanitizer\Cast('bool'))->sanitize(5);
    }

    public function testCastSanitizerFromArrayToBool()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Cast('bool'))->sanitize([true]);
    }

    public function testCastSanitizerFromAlphaStringToInt()
    {
        $this->setExpectedException('\RuntimeException', 'cast');
        (new Sanitizer\Cast('int'))->sanitize('a');
    }

    public function testCastSanitizerFromNullToInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Cast('int'))->sanitize(null);
    }

    public function testCastSanitizerFromArrayToInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Cast('int'))->sanitize([42]);
    }

    public function testCastSanitizerFromAlphaStringToFloat()
    {
        $this->setExpectedException('\RuntimeException', 'cast');
        (new Sanitizer\Cast('float'))->sanitize('a');
    }

    public function testCastSanitizerFromNullToFloat()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Cast('float'))->sanitize(null);
    }

    public function testCastSanitizerFromArrayToFloat()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Sanitizer\Cast('float'))->sanitize([42.1]);
    }
}

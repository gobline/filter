<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Filter\Validator;
use Mendo\Translator\Translator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class ValidatorFilterTest extends PHPUnit_Framework_TestCase
{
    public function testEmailValidator()
    {
        $this->assertTrue((new Validator\Email())->isValid('mdecaffmeyer@gmail.com'));
        $this->assertFalse((new Validator\Email())->isValid('hello'));
        $this->assertFalse((new Validator\Email())->isValid(''));
    }

    public function testEmailValidatorValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Email())->isValid(42);
    }

    public function testEmailValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Email())->isValid(true);
    }

    public function testEmailValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Email())->isValid(null);
    }

    public function testEmailValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Email())->isValid([]);
    }

    public function testNoTagsValidator()
    {
        $this->assertTrue((new Validator\NoTags())->isValid('hello world'));
        $this->assertFalse((new Validator\NoTags())->isValid('<hello world'));
        $this->assertTrue((new Validator\NoTags())->isValid('>hello world'));
        $this->assertTrue((new Validator\NoTags())->isValid('hello world/>'));
        $this->assertFalse((new Validator\NoTags())->isValid('<span>hello world'));
        $this->assertTrue((new Validator\NoTags('<span>'))->isValid('<span>hello world'));
        $this->assertTrue((new Validator\NoTags())->isValid('mdecaffmeyer@gmail.com'));
        $this->assertTrue((new Validator\NoTags())->isValid(''));
    }

    public function testNoTagsValidatorValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NoTags())->isValid(42);
    }

    public function testNoTagsValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NoTags())->isValid(true);
    }

    public function testNoTagsValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NoTags())->isValid(null);
    }

    public function testNoTagsValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NoTags())->isValid([]);
    }

    public function testNotEmptyValidator()
    {
        $this->assertTrue((new Validator\NotEmpty())->isValid('hello world'));
        $this->assertFalse((new Validator\NotEmpty())->isValid(''));
    }

    public function testNotEmptyValidatorValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NotEmpty())->isValid(42);
    }

    public function testNotEmptyValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NotEmpty())->isValid(true);
    }

    public function testNotEmptyValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NotEmpty())->isValid(null);
    }

    public function testNotEmptyValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\NotEmpty())->isValid([]);
    }

    public function testValueValidator()
    {
        $this->assertTrue((new Validator\Value('hello', 'world', 42))->isValid('hello'));
        $this->assertFalse((new Validator\Value('hello', 'world', 42))->isValid('hello world'));
        $this->assertTrue((new Validator\Value('hello', 'world', 42))->isValid(42));
        $this->assertFalse((new Validator\Value('hello', 'world', 42))->isValid(''));
    }

    public function testValueValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Value('hello'))->isValid(true);
    }

    public function testValueValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Value('hello'))->isValid(null);
    }

    public function testValueValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Value('hello'))->isValid([]);
    }

    public function testLengthValidator()
    {
        $this->assertTrue((new Validator\Length(3, 6))->isValid('abc'));
        $this->assertTrue((new Validator\Length(3))->isValid('abc'));
        $this->assertTrue((new Validator\Length(3, 6))->isValid('abcdef'));
        $this->assertFalse((new Validator\Length(3, 6))->isValid('abcdefg'));
        $this->assertFalse((new Validator\Length(3, 6))->isValid('ab'));
        $this->assertFalse((new Validator\Length(3, 6))->isValid(''));
        $this->assertTrue((new Validator\Length(0))->isValid(''));
    }

    public function testLengthValidatorMessages()
    {
        $validator = new Validator\Length(4, 20);

        $this->assertFalse($validator->isValid('abc'));
        $this->assertSame('The input is less than 4 characters long', $validator->getMessage());

        $validator = new Validator\Length(4, 20);

        $validator->setMessageTemplate('The username "%value%" is less than %min% characters long', Validator\Length::TOO_SHORT);
        $this->assertFalse($validator->isValid('abc'));
        $this->assertSame('The username "abc" is less than 4 characters long', $validator->getMessage());
    }

    public function testLengthValidatorTranslatedMessages()
    {
        $translator = new Translator();
        $translator->addTranslationFile(__DIR__.'./resources/test-translator-fr.php', 'fr');
        $translator->setDefaultLanguage('fr');

        $validator = new Validator\Length(4, 20);
        $validator->setTranslator($translator);
        $this->assertFalse($validator->isValid('abc'));
        $this->assertSame('L\'entrée contient moins de 4 caractères', $validator->getMessage());

        $validator = new Validator\Length(4, 20);
        $this->assertFalse($validator->isValid('abc'));
        $this->assertSame('The input is less than 4 characters long', $validator->getMessage());

        Validator\AbstractValidator::setDefaultTranslator($translator);
        $validator = new Validator\Length(4, 20);
        $this->assertFalse($validator->isValid('abc'));
        $this->assertSame('L\'entrée contient moins de 4 caractères', $validator->getMessage());
    }

    public function testLengthValidatorValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Length(3, 6))->isValid(42);
    }

    public function testLengthValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Length(3, 6))->isValid(true);
    }

    public function testLengthValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Length(3, 6))->isValid(null);
    }

    public function testLengthValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Length(3, 6))->isValid([]);
    }

    public function testRegexValidator()
    {
        $this->assertTrue((new Validator\Regex('/^foo/'))->isValid('foobar'));
        $this->assertTrue((new Validator\Regex('/bar$/'))->isValid('foobar'));
        $this->assertFalse((new Validator\Regex('/^bar/'))->isValid('foobar'));
    }

    public function testRegexValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Regex('/^foo/'))->isValid(true);
    }

    public function testRegexValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Regex('/^foo/'))->isValid(null);
    }

    public function testRegexValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Regex('/^foo/'))->isValid([]);
    }

    public function testAlphanumValidator()
    {
        $this->assertTrue((new Validator\Alphanum())->isValid('abc123'));
        $this->assertFalse((new Validator\Alphanum())->isValid(' abc123'));
        $this->assertFalse((new Validator\Alphanum())->isValid('_abc_123_'));
        $this->assertTrue((new Validator\Alphanum('_'))->isValid('_abc_123_'));
        $this->assertFalse((new Validator\Alphanum('_'))->isValid(' _abc_ _123_ '));
        $this->assertTrue((new Validator\Alphanum('_ '))->isValid(' _abc_ _123_ '));
        $this->assertFalse((new Validator\Alphanum())->isValid(''));
    }

    public function testAlphanumValidatorValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alphanum())->isValid(42);
    }

    public function testAlphanumValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alphanum())->isValid(true);
    }

    public function testAlphanumValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alphanum())->isValid(null);
    }

    public function testAlphanumValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alphanum())->isValid([]);
    }

    public function testAlphaValidator()
    {
        $this->assertFalse((new Validator\Alpha())->isValid('abc123'));
        $this->assertTrue((new Validator\Alpha())->isValid('abc'));
        $this->assertFalse((new Validator\Alpha())->isValid(' _abc_ '));
        $this->assertFalse((new Validator\Alpha('_'))->isValid(' _abc_ '));
        $this->assertTrue((new Validator\Alpha('_ '))->isValid(' _abc_ '));
        $this->assertFalse((new Validator\Alpha())->isValid(''));
    }

    public function testAlphaValidatorValueInt()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alpha())->isValid(42);
    }

    public function testAlphaValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alpha())->isValid(true);
    }

    public function testAlphaValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alpha())->isValid(null);
    }

    public function testAlphaValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Alpha())->isValid([]);
    }

    public function testBetweenValidator()
    {
        $this->assertTrue((new Validator\Between(1, 3))->isValid(2));
        $this->assertTrue((new Validator\Between(1, 3))->isValid('2'));
        $this->assertTrue((new Validator\Between(1, 3))->isValid(1));
        $this->assertTrue((new Validator\Between(1, 3))->isValid(3));
        $this->assertFalse((new Validator\Between(1, 3))->isValid(0));
        $this->assertFalse((new Validator\Between(1, 3))->isValid(4));
        $this->assertTrue((new Validator\Between(-5, 5))->isValid(-4));
        $this->assertTrue((new Validator\Between(-5.12, 5.18))->isValid(5.16));
        $this->assertFalse((new Validator\Between(-5.12, 5.18))->isValid(5.2));
    }

    public function testBetweenValidatorValueAlphaString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'value');
        (new Validator\Between(0, 3))->isValid('a');
    }

    public function testBetweenValidatorValueEmptyString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'value');
        (new Validator\Between(0, 3))->isValid('');
    }

    public function testBetweenValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Between(0, 3))->isValid(true);
    }

    public function testBetweenValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Between(0, 3))->isValid(null);
    }

    public function testBetweenValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Between(0, 3))->isValid([]);
    }

    public function testMinValidator()
    {
        $this->assertTrue((new Validator\Min(5))->isValid(6));
        $this->assertTrue((new Validator\Min(5))->isValid('6'));
        $this->assertTrue((new Validator\Min(5))->isValid('06'));
        $this->assertTrue((new Validator\Min(5))->isValid(5));
        $this->assertFalse((new Validator\Min(5))->isValid(4));
        $this->assertFalse((new Validator\Min(5))->isValid('4'));
        $this->assertFalse((new Validator\Min(5))->isValid('04'));
        $this->assertTrue((new Validator\Min(-5))->isValid(-4));
        $this->assertTrue((new Validator\Min(-5))->isValid(-5));
        $this->assertFalse((new Validator\Min(-5))->isValid(-6));
        $this->assertTrue((new Validator\Min(-5.12))->isValid(-5.11));
        $this->assertTrue((new Validator\Min(-5.12))->isValid(-5.12));
        $this->assertTrue((new Validator\Min(-5.12))->isValid(-5));
        $this->assertFalse((new Validator\Min(-5.12))->isValid(-5.13));
    }

    public function testMinValidatorValueAlphaString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'value');
        (new Validator\Min(0))->isValid('a');
    }

    public function testMinValidatorValueEmptyString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'value');
        (new Validator\Min(0))->isValid('');
    }

    public function testMinValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Min(0))->isValid(true);
    }

    public function testMinValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Min(0))->isValid(null);
    }

    public function testMinValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Min(0))->isValid([]);
    }

    public function testMaxValidator()
    {
        $this->assertTrue((new Validator\Max(5))->isValid(4));
        $this->assertTrue((new Validator\Max(5))->isValid('4'));
        $this->assertTrue((new Validator\Max(5))->isValid('04'));
        $this->assertTrue((new Validator\Max(5))->isValid(5));
        $this->assertFalse((new Validator\Max(5))->isValid(6));
        $this->assertFalse((new Validator\Max(5))->isValid('6'));
        $this->assertFalse((new Validator\Max(5))->isValid('06'));
        $this->assertTrue((new Validator\Max(-5))->isValid(-6));
        $this->assertTrue((new Validator\Max(-5))->isValid(-5));
        $this->assertFalse((new Validator\Max(-5))->isValid(-4));
        $this->assertTrue((new Validator\Max(-5.12))->isValid(-5.13));
        $this->assertTrue((new Validator\Max(-5.12))->isValid(-5.12));
        $this->assertFalse((new Validator\Max(-5.12))->isValid(-5));
        $this->assertFalse((new Validator\Max(-5.12))->isValid(-5.11));
    }

    public function testMaxValidatorValueAlphaString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'value');
        (new Validator\Max(0))->isValid('a');
    }

    public function testMaxValidatorValueEmptyString()
    {
        $this->setExpectedException('\InvalidArgumentException', 'value');
        (new Validator\Max(0))->isValid('');
    }

    public function testMaxValidatorValueBoolean()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Max(0))->isValid(true);
    }

    public function testMaxValidatorValueNull()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Max(0))->isValid(null);
    }

    public function testMaxValidatorValueNonScalar()
    {
        $this->setExpectedException('\InvalidArgumentException', 'type');
        (new Validator\Max(0))->isValid([]);
    }

    public function testBooleanValidator()
    {
        $this->assertTrue((new Validator\Boolean())->isValid(true));
        $this->assertTrue((new Validator\Boolean())->isValid(false));
        $this->assertTrue((new Validator\Boolean())->isValid(1));
        $this->assertTrue((new Validator\Boolean())->isValid(0));
        $this->assertTrue((new Validator\Boolean())->isValid('1'));
        $this->assertTrue((new Validator\Boolean())->isValid('0'));
        $this->assertTrue((new Validator\Boolean())->isValid(''));
        $this->assertFalse((new Validator\Boolean())->isValid('a'));
        $this->assertFalse((new Validator\Boolean())->isValid(5));
        $this->assertFalse((new Validator\Boolean())->isValid(null));
    }

    public function testIntValidator()
    {
        $this->assertTrue((new Validator\Int())->isValid(5));
        $this->assertTrue((new Validator\Int())->isValid('5'));
        $this->assertTrue((new Validator\Int())->isValid(005));
        $this->assertFalse((new Validator\Int())->isValid(-5));
        $this->assertTrue((new Validator\Int(true))->isValid(-5));
        $this->assertFalse((new Validator\Int())->isValid(5.2));
        $this->assertFalse((new Validator\Int())->isValid('a'));
        $this->assertFalse((new Validator\Int())->isValid('1a'));
        $this->assertFalse((new Validator\Int())->isValid('a1'));
        $this->assertFalse((new Validator\Int())->isValid(true));
        $this->assertFalse((new Validator\Int())->isValid(false));
        $this->assertFalse((new Validator\Int())->isValid(null));
        $this->assertFalse((new Validator\Int())->isValid(''));
    }

    public function testFloatValidator()
    {
        $this->assertTrue((new Validator\Float())->isValid(5));
        $this->assertTrue((new Validator\Float())->isValid(5.2));
        $this->assertTrue((new Validator\Float())->isValid('5.2'));
        $this->assertTrue((new Validator\Float())->isValid(005.2));
        $this->assertFalse((new Validator\Float())->isValid(-5.2));
        $this->assertTrue((new Validator\Float(true))->isValid(-5.2));
        $this->assertFalse((new Validator\Float())->isValid('a'));
        $this->assertFalse((new Validator\Float())->isValid('1.1a'));
        $this->assertFalse((new Validator\Float())->isValid('a1.1'));
        $this->assertFalse((new Validator\Float())->isValid(true));
        $this->assertFalse((new Validator\Float())->isValid(false));
        $this->assertFalse((new Validator\Float())->isValid(null));
        $this->assertFalse((new Validator\Float())->isValid(''));
    }
}

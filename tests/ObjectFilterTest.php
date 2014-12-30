<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Filter\ObjectFilter;
use Mendo\Filter\FilterableInterface;
use Mendo\Filter\ObjectFilterFactory;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class ObjectFilterTest extends PHPUnit_Framework_TestCase
{
    public function testObjectFilter()
    {
        $name = '  Mathieu  ';
        $age = '  31  ';
        $gender = '  M  ';

        $person = new Person($name, $age, $gender);

        $objectFilter = new ObjectFilter();
        $person = $objectFilter->filter($person);

        $this->assertInstanceOf('Person', $person);
        $this->assertSame('Mathieu', $person->getName());
        $this->assertSame(31, $person->getAge());
        $this->assertSame('M', $person->getGender());
        $this->assertSame(false, $person->isDeceased());
    }

    public function testObjectFilterFail()
    {
        $name = '  Mathieu  ';
        $age = '  xyz  '; // invalid age
        $gender = '  B  '; // invalid gender
        $deceased = '';

        $person = new Person($name, $age, $gender, $deceased);

        $objectFilter = new ObjectFilter();
        $person = $objectFilter->filter($person);

        $this->assertNull($person);
        $this->assertTrue($objectFilter->hasMessages());
        $this->assertSame('The input is not a valid number', $objectFilter->getMessages()['age'][0]);
        $this->assertSame('The input content is not valid', $objectFilter->getMessages()['gender'][0]);
    }

    public function testObjectFilterFactory()
    {
        $factory = new ObjectFilterFactory();

        $this->assertInstanceOf('Mendo\Filter\ObjectFilter', $factory->createOjectFilter());
    }
}

class Person implements FilterableInterface
{
    private $name;
    private $age;
    private $gender;
    private $deceased;
    private $email;

    public function __construct($name, $age, $gender, $deceased = false)
    {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
        $this->deceased = $deceased;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function isDeceased()
    {
        return $this->deceased;
    }

    public function setDeceased($deceased)
    {
        $this->deceased = $deceased;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getRules()
    {
        return [
            'name' => 'trim|alpha|length(2,50)',
            'age' => 'trim|int|between(0,110)|cast(int)',
            'gender' => 'trim|value(M,F)',
            'deceased' => 'trim|boolean|cast(boolean)',
            'email' => 'optional|trim|email',
        ];
    }
}

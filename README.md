# Filter Component - Mendo Framework

The Mendo Filter component is used to sanitize and/or validate variables.
Its power lies in the ability to define "filter funnels" which allow to filter a variable through multiple (built-in or custom) sanitizers and validators easily at once.

There are two kinds of filters:

* **Sanitizers** sanitize the data. They may alter the data like removing undesired characters.
* **Validators** validate the data. They return true if the data is valid, false otherwise.

## Sanitizers

Example:

```php
(new Mendo\Filter\Sanitizer\LTrim('/'))->sanitize('/some/path/'); // returns "some/path/"

(new Mendo\Filter\Sanitizer\Cast('int'))->sanitize('42'); // returns integer 42
```

### Built-in Sanitizers

* Cast
* Lower
* LTrim
* RTrim
* StripTags
* Trim
* Upper

## Validators

There are two types of validators.

* **data validators** check if the data meets certain qualifications.
* **data type validators** (or simply type validators) check if the variable is of a certain data type.

```php
(new Mendo\Filter\Validator\Email())->isValid('mdecaffmeyer@gmail.com') // returns true

(new Mendo\Filter\Validator\Length(5))->isValid('foo') // returns false
```

```php
(new Mendo\Filter\Validator\Int())->isValid('foo') // returns false, "foo" is not an integer

(new Mendo\Filter\Validator\Int())->isValid(42) // returns true
```

### Built-in Validators

Data validators:
* Alpha
* Alphanum
* Between
* Email
* Length
* Max
* Min
* NoTags
* NotEmpty
* Regex
* Value

Data type validators:
* Boolean *('', '0', '1', 0 and 1 are considered valid booleans)*
* Float *(numeric strings are considered valid integers)*
* Int *(numeric strings are considered valid integers)*
* Scalar

### Error messages

It is possible to retrieve the validator's error messages in case data failed validating.

```php
$validator = new Mendo\Filter\Validator\Int();

if (!$validator->isValid("foo")) {
    echo $validator->getMessage(); // prints "The input is not a valid number"
}
```

Customization of error messages is supported:

```php
$validator = new Mendo\Filter\Validator\Int();
$validator->setMessageTemplate('%value% is not a valid number');

if (!$validator->isValid("foo")) {
    echo $validator->getMessage(); // prints "foo is not a valid number"
}
```

Note that you can use placeholders as *%value%* above. Each validator has its own placeholders (all of the built-in validators have at least *%value%*).
For example, the validator *Between* who checks if a number is between two numbers provides 3 placeholders: *%value%*, *%min%* and *%max%*.
Its default message is: *The input is not between "%min%" and "%max%" (inclusively)*.

### Translator

You can add a translator for your error messages globally

```php
Mendo\Filter\Validator\AbstractValidator::setDefaultTranslator($translator);
```

or for an instance

```php
$validator->setTranslator($translator);
```

## Filter Funnels

The real value of the component is its ability to create filter funnels. Filter funnels allow you to filter a variable through multiple sanitizers and/or validators at once.

Below is an example that trims the variable and checks if it contains a valid age (between 0 and 110). Eventually it will cast the variable to an integer.

```php
$funnel = (new Mendo\Filter\FilterFunnel())
    ->addSanitizer(new Sanitizer\Trim())
    ->addValidator(new Validator\Int())
    ->addValidator(new Validator\Between(0, 110))
    ->addSanitizer(new Sanitizer\Cast('int'));

$funnel->filter(30); // returns 30

$funnel->filter("foo"); // returns null
echo $validator->getMessage(); // prints "The input is not a valid number"
```

If one of the validators invalidates data, the funnel will return ```null``` (and will not execute the sanitizers and validators that follow).

It is possible to register filters in a class map in order to reference the filters by classname when using the funnel.
Built-in filters are registered in the class map by default. The example above could be rewritten as follows:

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->addSanitizer('trim')
    ->addValidator('int')
    ->addValidator('between', 1, 110)
    ->addSanitizer('cast', 'int')
    ->filter($age);
```

The above could also be written like:

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->add('trim')
    ->add('int')
    ->add('between', 1, 110)
    ->add('cast', 'int')
    ->filter($age);
```

or

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->trim
    ->int
    ->between(1, 110)
    ->cast('int')
    ->filter($age);
```

or

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->filter($age, 'trim|int|between(1,110)|cast(int)');
```

### Optional Values

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->setOptional()
    ->addSanitizer('trim')
    ->addValidator('int')
    ->addValidator('between', 1, 110)
    ->addSanitizer('cast', 'int')
    ->filter($age);
```

or

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->filter($age, 'optional|trim|int|between(1,110)|cast(int)');
```

If ```$age``` is an empty string or null, the filter funnel will return null without any error messages as the field is optional.

### Adding Custom Filters

To add your own custom filters into the class map, use the ```FilterFunnelFactory``` factory class:

```php
$map = new FilterClassMap();
$map->addValidator('foo', 'My\\Filter\\Validator\\FooValidator');

$factory = new FilterFunnelFactory($map);

$funnel = $factory->createFunnel();
```

You can still set custom messages for validators when using funnels:

```php
$age = (new Mendo\Filter\FilterFunnel())
    ->addSanitizer('trim')
    ->addValidator('int')
    ->setMessageTemplate('"%value%" is not a valid number')
    ->addValidator('between', 1, 110)
    ->setMessageTemplate('%value% must be a number between %min% and %max%')
    ->addSanitizer('cast', 'int')
    ->filter($age);
```

Note that ```setMessageTemplate()``` should directly follow an ```addValidator()``` call or an exception will be thrown.

## Installation

You can install Mendo Filter using the dependency management tool [Composer](https://getcomposer.org/).
Run the *require* command to resolve and download the dependencies:

```
composer require mendoframework/filter
```

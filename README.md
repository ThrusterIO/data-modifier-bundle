# DataModifier Bundle

[![Latest Version](https://img.shields.io/github/release/ThrusterIO/data-modifier-bundle.svg?style=flat-square)]
(https://github.com/ThrusterIO/data-modifier-bundle/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)]
(LICENSE)
[![Build Status](https://img.shields.io/travis/ThrusterIO/data-modifier-bundle.svg?style=flat-square)]
(https://travis-ci.org/ThrusterIO/data-modifier-bundle)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ThrusterIO/data-modifier-bundle.svg?style=flat-square)]
(https://scrutinizer-ci.com/g/ThrusterIO/data-modifier-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/ThrusterIO/data-modifier-bundle.svg?style=flat-square)]
(https://scrutinizer-ci.com/g/ThrusterIO/data-modifier-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/thruster/data-modifier-bundle.svg?style=flat-square)]
(https://packagist.org/packages/thruster/data-modifier-bundle)

[![Email](https://img.shields.io/badge/email-team@thruster.io-blue.svg?style=flat-square)]
(mailto:team@thruster.io)

The Thruster DataModifier Bundle.


## Install

Via Composer

``` bash
$ composer require thruster/data-modifier-bundle
```

## Usage

This bundle wraps DataModifier Component and provides support for modifiers as tagged services.

Example configuration:

```xml
<service id="some_modifier" class="SomeModifier">
    <tag name="thruster_data_modifier" group="first_group"/>
    <tag name="thruster_data_modifier" group="second_group" priority="2"/>
</service>


<service id="another_modifier" class="AnotherModifier">
    <tag name="thruster_data_modifier" group="first_group"/>
    <tag name="thruster_data_modifier" group="second_group" priority="1"/>
</service>
```

Usage:

```php
$this->container->get('thruster_data_modifiers')->getGroup('first_group')->modify($input);
```

Using provided trait:

```php
use DataModifiersAwareTrait;
//...
$this->getDataModifierGroup('second_group')->modify($input);
```


## Testing

``` bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.


## License

Please see [License File](LICENSE) for more information.

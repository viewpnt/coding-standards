Sclable Coding Standards
========================

Our code of conduct requires strict adherence to the FIG PSR standards. The automated checks are collected in this Tool.

[![Build Status](https://travis-ci.org/sclable/coding-standards.svg?branch=0.1.0.0)](https://travis-ci.org/sclable/coding-standards)

Usage
-----

### Installation

Installation with [composer](https://getcomposer.org):

```bash
# install with composer
composer require --dev sclable/coding-standards
```

### Included tools

Currently two tools are integraged:

- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/wiki)
- [PHP MessDetector](http://phpmd.org)

### Run checks

After the installation with composer you're able to run the checks with a single command:

```bash
# Linux/Mac OS X
cd path/to/project/root
vendor/bin/sclcheck path/to/source

# Windows
cd path/to/project/root
php vendor/bin/sclcheck path/to/source
```

### Run fixes

Some of the problems can be fixed automatically (e.g. curly braces on wrong lines, spaces at the end of a line):


```bash
# Linux/Mac OS X
cd path/to/project/root
vendor/bin/sclfix path/to/source

# Windows
cd path/to/project/root
php vendor/bin/sclfix path/to/source
```

Changelog
---------

* 0.1.0.0
** Mess detection configuration/added
** Code sniffer standards
*** ruleset.xml
*** NamingConventions Sniff
*** ControlStructures Sniff
*** CamelCapsMethodNames Sniff
*** CommentingNoHash Sniff


License
-------

For the license and copyright see the [LICENSE](LICENSE) file.

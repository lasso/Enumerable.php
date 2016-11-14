[![Build Status](https://travis-ci.org/lasso/Enumerable.php.svg?branch=master)](https://travis-ci.org/lasso/Enumerable.php)
&nbsp;&nbsp;&nbsp;&nbsp;
[![Code Climate](https://codeclimate.com/github/lasso/Enumerable.php/badges/gpa.svg)](https://codeclimate.com/github/lasso/Enumerable.php)
&nbsp;&nbsp;&nbsp;&nbsp;
[![codecov](https://codecov.io/gh/lasso/Enumerable.php/branch/master/graph/badge.svg)](https://codecov.io/gh/lasso/Enumerable.php)
&nbsp;&nbsp;&nbsp;&nbsp;
[![Latest docs](https://img.shields.io/badge/docs-latest-brightgreen.svg?style=flat)](https://docs.lassoweb.se/Enumerable.php)

# lasso3000/enumerable

Inspired by ruby's Enumerable module, this library allows you to create enumerables in PHP.

## How to use this library in your own project
`composer require lasso3000/enumerable`

## How to fork/work with this library

### Clone the repository
`git clone https://github.com/lasso/Enumerable.php.git`

### Install dependencies
`composer install`

### Run tests
`vendor/bin/phpunit --bootstrap vendor/autoload.php tests`

### Run tests with coverage (XDebug must be installed)
`vendor/bin/phpunit --bootstrap vendor/autoload.php --coverage-html=coverage --whitelist=src tests`

### Generate docs (will be placed in the phpdoc directory)
` vendor/bin/phpdoc -d src/ -t phpdoc --template="responsive-twig"`
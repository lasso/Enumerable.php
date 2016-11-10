[![Build Status](https://travis-ci.org/lasso/Enumerable.php.svg?branch=master)](https://travis-ci.org/lasso/Enumerable.php)&nbsp;&nbsp;&nbsp;&nbsp;[![codecov](https://codecov.io/gh/lasso/Enumerable.php/branch/master/graph/badge.svg)](https://codecov.io/gh/lasso/Enumerable.php)

# lasso/enumerable

IMPORTANT NOTICE: This package is INCOMPLETE. I'm giving a talk in a few weeks (2016-11-17) and this package will be showcased at that time. Hold on, we're getting there!

Inspired by ruby's Enumerable module, this library allows you to create enumerables in PHP.

## Install dependencies
`composer install`

## Run tests
`vendor/bin/phpunit --bootstrap vendor/autoload.php tests`

## Run tests with coverage (XDebug must be installed)
`vendor/bin/phpunit --bootstrap vendor/autoload.php --coverage-html=coverage --whitelist=src tests`

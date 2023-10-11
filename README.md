# Fr3nch QR tracker

[![Build Status](https://github.com/fr3nch13/qr.fr3nch.com/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/fr3nch13/qr.fr3nch.com/actions/workflows/ci.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/fr3nch13/qr.fr3nch.com.svg?style=flat-square)](https://packagist.org/packages/fr3nch13/qr.fr3nch.com)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)
[![codecov](https://codecov.io/gh/fr3nch13/qr.fr3nch.com/graph/badge.svg?token=xHC0xjLXxq)](https://codecov.io/gh/fr3nch13/qr.fr3nch.com)

The code for https://qr.fr3nch.com

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist fr3nch13/qr.fr3nch.com [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist fr3nch13/qr.fr3nch.com
```

In case you want to use a custom app dir name (e.g. `/myapp/`):

```bash
composer create-project --prefer-dist fr3nch13/qr.fr3nch.com myapp
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Configuration

Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

## Template

This uses the Bootstrap Cube Template and UI Kit.
https://themes.getbootstrap.com/product/cube-multipurpose-template-ui-kit/

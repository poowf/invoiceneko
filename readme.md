# Invoice Neko
[![Latest Stable Version][release-image]][release-url]
[![Total Downloads][download-image]][download-url]
[![Build Status][travis-image]][travis-url]

<img align="center" width="150" height="150" src="https://invoiceneko.blob.core.windows.net/assets/invoiceneko-circle.png">

## Setup and Deployment
```bash
composer install
npm install
npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve --host 0.0.0.0
```

## About Invoice Neko

An Invoice System developed for anyone who needs to generate out an invoice and manage clients


## License

Copyright 2018 Zane J. Chua

This project is licensed under the Attribution Assurance License, please refer to the LICENSE file for more details

[release-image]: https://img.shields.io/github/release/poowf/invoiceneko.svg?style=flat-square
[release-url]: https://github.com/poowf/invoiceneko/releases

[download-image]: https://img.shields.io/github/downloads/poowf/invoiceneko/total.svg?style=flat-square
[download-url]: https://github.com/poowf/invoiceneko/releases

[travis-image]: https://img.shields.io/travis/poowf/invoiceneko/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/poowf/invoiceneko

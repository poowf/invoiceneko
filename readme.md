# Invoice Neko
[![GitHub license][license-image]][license-url]
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

Copyright 2018 Poowf Labs LLP

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.


[license-image]: https://img.shields.io/github/license/poowf/invoiceneko.svg?style=flat-square
[license-url]: https://github.com/poowf/invoiceneko/blob/master/LICENSE

[release-image]: https://img.shields.io/github/release/poowf/invoiceneko.svg?style=flat-square
[release-url]: https://github.com/poowf/invoiceneko/releases

[download-image]: https://img.shields.io/github/downloads/poowf/invoiceneko/total.svg?style=flat-square
[download-url]: https://github.com/poowf/invoiceneko/releases

[travis-image]: https://img.shields.io/travis/poowf/invoiceneko/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/poowf/invoiceneko
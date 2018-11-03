# Invoice Plz
[![GitHub license](https://img.shields.io/github/license/poowf/invoiceplz.svg)](https://github.com/poowf/invoiceplz/blob/master/LICENSE) ![Latest Stable Version](https://img.shields.io/github/release/poowf/invoiceplz.svg) ![Total Downloads](https://img.shields.io/github/downloads/poowf/invoiceplz/total.svg) ![Build Status](https://travis-ci.com/poowf/invoiceplz.svg)
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

## About Invoice Plz

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

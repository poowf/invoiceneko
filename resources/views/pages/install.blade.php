@extends("layouts.default", ['page_title' => 'Install'])

@section("head")
    <link href="{{ asset(mix('/assets/css/prism-okaidia.css')) }}" rel="stylesheet" type="text/css">
    <style>
        .installation-wrapper ul {
            padding-left: 20px;
        }
        .installation-wrapper ul li {
            list-style-type: disc;
        }

        .installation-wrapper ul li ul li {
            list-style-type: square;
        }
    </style>
@stop

@section("content")
    <div class="row pall30" style="background-color: #585454;">
        <div class="mini-container">
            <div class="col s12 center">
                <div class="hero-logo-container circle">
                    <img src="{{ asset('assets/img/logo.svg') }}" class="hero-logo-image">
                </div>
                <h2 class="hero-header white-text no-margin">{{ config('app.name') }} Installation</h2>
                <p class="hero-description flow-text white-text mtop20">Steps on deploying {{ config('app.name') }} on your own server</p>
            </div>
        </div>
    </div>
    <div class="mini-container">
        <div class="row">
            <div class="col s12">
                <div class="card-panel center pricing-panel">
                    <i class="mdi mdi-48px mdi-ubuntu"></i>
                    <h4>Ubuntu</h4>
                    <p>16.04 LTS, 18.04 LTS</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 installation-wrapper">
                <h6>System Requirements</h6>
                <ul>
                    <li>MySQL >= 5.6</li>
                    <li>
                        PHP >= 7.0
                        <ul>
                            <li>php-bcmath</li>
                            <li>php-ctype</li>
                            <li>php-curl</li>
                            <li>php-dom</li>
                            <li>php-fileinfo</li>
                            <li>php-gd</li>
                            <li>php-iconv</li>
                            <li>php-json</li>
                            <li>php-libxml</li>
                            <li>php-mbstring</li>
                            <li>php-openssl</li>
                            <li>php-pcntl</li>
                            <li>php-pcre</li>
                            <li>php-PDO</li>
                            <li>php-posix</li>
                            <li>php-SimpleXML</li>
                            <li>php-tokenizer</li>
                            <li>php-xml</li>
                            <li>php-xmlreader</li>
                            <li>php-xmlwriter</li>
                            <li>php-zip</li>
                            <li>php-zlib</li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h6>1. Clone the repository</h6>
                <pre class="language-bash">git clone https://github.com/poowf/invoiceneko.git</pre>
                <h6 class="mtop20">2. Run set-up commands</h6>
<pre class="language-bash">
cd invoiceneko
composer install --no-dev
npm install
npm run prod
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve --host 0.0.0.0
</pre>
                <h6 class="mtop20">3. Set-up your environment and communication credentials</h6>
                <p>Set-up your email credentials in the .env file, configure the APP_URL variable to your domain</p>
                <h6 class="mtop20">4. Create a company and user</h6>
                <p>Create a company and user by going through the start page and you'll be able to use InvoiceNeko</p>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        //
        // al pre tags on the page
        const pres = document.getElementsByTagName("pre")

        //
        // reformat html of pre tags
        if (pres !== null ) {
            console.log("hello");
            for (let i = 0; i < pres.length; i++) {
                // check if its a pre tag with a prism class
                if (isPrismClass(pres[i])) {
                    // insert code and copy element
                    pres[i].innerHTML = `<div class="copy">copy</div><code class="${pres[i].className}">${pres[i].innerHTML}</code>`
                }
            }
        }

        // create clipboard for every copy element
        const clipboard = new Clipboard('.copy', {
            target: (trigger) => {
                return trigger.nextElementSibling;
            }
        });

        // do stuff when copy is clicked
        clipboard.on('success', (event) => {
            event.trigger.textContent = 'copied!';
            setTimeout(() => {
                event.clearSelection();
                event.trigger.textContent = 'copy';
            }, 2000);
        });

        //
        // helper function
        function isPrismClass(preTag) {
            return preTag.className.substring(0, 8) === 'language'
        }

        $(function() {

        });
    </script>
@stop
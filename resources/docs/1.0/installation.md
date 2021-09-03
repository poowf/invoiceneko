# Installation

---

- [Instructions](#section-1)

<a name="section-1"></a>
## Instructions

##### Clone the repository
```bash
git clone https://github.com/poowf/invoiceneko.git
```

##### Run set-up commands
```bash
cd invoiceneko
cp .env.example .env
composer install --no-dev
npm install
npm run prod
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve --host 0.0.0.0
```

##### Set-up your environment and communication credentials
Set-up your email credentials in the .env file, configure the APP_URL variable to your domain

##### Create a company and user
Create a company and user by going through the start page and you'll be able to use Invoice Neko

### Docker
docker build -t invneko -f Dockerfile.production .
docker run -p 8080:80 -e "ENV_DATA=$(<envData.json)" invneko

#### Test
docker run -e "ENV_DATA=$(<envData.json)" -it invneko sh
# development

---

-   [Instructions](#section-1)

<a name="section-1"></a>

## Instructions

##### Clone the repository

```bash
git clone https://github.com/poowf/invoiceneko.git
```

##### Run set-up commands

```bash
cd invoiceneko
yarn install
yarn run dev
docker-compose up -d
docker exec -it invoiceneko sh
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan storage:link
```

Environment should be accessible at http://localhost:8181

# HOW TO INSTALL IN YOUR LOCAL

## Pull Repository

1. Clone this repository, with code below :

```
git clone https://github.com/alfaz86/laravel-cafe.git
```

2. Open in your cmd or terminal and paste the code

## Setting Project

1. Copy paste the `.env.example` file and rename to `.env`
2. Setting DB config on `.env` file, example :

```
DB_DATABASE=laravel_cafe
DB_USERNAME=root
DB_PASSWORD=
```

3. create a new database with the same name as the database in the env file
4. Run composer install on your cmd or terminal :

```
composer install
```

5. Run npm install :

```
npm install
```

6. Run mix asset management :

```
npm run development
```

7. Run generate key :

```
php artisan key:generate
```

8. Run migration database :

```
php artisan migrate
```

9. Run serve :

```
php artisan serve
```

10. Go to [http://localhost:8000](http://localhost:8000)
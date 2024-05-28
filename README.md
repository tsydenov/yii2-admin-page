# How to start:
- Set environment variables in `/docker/.env` that are defined in `/docker/.env.example`

### NB
---
Values in `.env` file can be overriden by another `.env` with `--env-file` option:

`docker compose --env-file ./docker/.env.prod up`

---
<br>

- Install the application dependencies

`docker compose run --rm backend composer install`
- Initialize the application by running the `init` command within a container

`docker compose run --rm backend php /app/init`
- Adjust the components['db'] configuration in `common/config/main-local.php` accordingly

`
    'dsn' => 'mysql:host=mysql;dbname=yii2advanced',
    'username' => 'yii2advanced',
    'password' => 'secret',
`
- Start the application

`docker-compose up -d`

- Run the migrations

`docker-compose run --rm backend yii migrate`

- Access application in your browser by opening

> frontend: http://127.0.0.1:20080 <br>
> backend: http://127.0.0.1:21080



## How to create users
- Create tables used by rbac\DbManager

`yii migrate --migrationPath=@yii/rbac/migrations`

- Define `admin` and `user` roles by running

`yii rbac/init`

- Create a user

`yii make-user`

- You can assign your user admin role using 

`yii rbac/assign-admin`
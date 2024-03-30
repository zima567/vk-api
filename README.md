# REST API + JSON
Simple rest api application that let user register, signin, add products, view products and filter them.
This project is simple and basic and was realized for an internship.

## Getting Started

Clone the project on your local machine:

```bash
$ git clone https://github.com/zima567/vk-api.git
```

Make sure you have docker installed on your machine.
To run the project on docker run:

```bash
$ docker compose up -d
```

## API endpoints and frontend

The API should be served on your localhost:80/
The frontend should be served on your localhost:80/frontend/

> ```
>   Endpoints
>   POST /api/register [Create a new user account]
>   POST /api/auth [Authentificate yourself and get access token]
>   POST /api/product [Add a new product]
>   GET /api/products [See all products]
>   Special endpoint that is used to upload file
>   POST /frontend/upload
> ```

## In case app does not work properly

Causes the backend API may not work properly:
- Dependencies did not install properly
- Migration did not run
To solve this issue you can open an interactive shell in the `application-backend` container.
```bash
$ docker exec -it <container-name> sh
```
Replace <container-name> by application-backend.
Inside the container /var/www/application/  make sure all the dependencies are proper installed
and all migrations were successfully up:
```bash
$ composer install
```

```bash
$ ./vendor/bin/phpmig migrate
```
By now everything should be working properly. The frontend to interact with the API  will served on:
`localhost:80/frontend`

Please make sure port 80 is available on your localhost. In case you need to change ports of
mysql, nginx  you can edit the docker-compose.yaml file.

## Tools used in development
> ```
>   PHP7.4
>   Docker, Docker compose, Windows Docker desktop
>   MYSQL
>   Eloquent ORM
>   Laminas Mezzio middleware framework
> ```

## Other useful commands

Run migrations

```bash
$ ./vendor/bin/phpmig migrate
```
Install dependencies

```bash
$ composer install
```

Run containers application-backend, application-nginx, application-mysql
```bash
$ docker compose up -d
```

Stop containers
```bash
$ docker compose down
```

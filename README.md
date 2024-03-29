# REST API + JSON
Simple rest api application that let user register, signin, add products, view products and filter them.
This project is simple and basic and was realized for an internship.

## Getting Started

Clone the project on your local machine:

```bash
$ git clone <url of this repo>
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
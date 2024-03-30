# REST API + JSON
Simple REST API application that let user register, signin, add products, view products and filter them.
This project is simple and basic and was realized for an internship. The container technology has been used.  
In that case we have 3 containers that communicate on a default_network.  
List of containers:  
- application-backend: contain the API itself
- application-mysql: contain the mysql server. Listen on ports 3307 (host machine):3306 (On docker default network)
- application-nginx: contain the nginx server. API available on `localhost:80/` --- frontend `localhost:80/frontend`

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

## In case application does not work properly

Causes the backend API may not work properly:
- Dependencies did not install properly
- Migration did not run
- folder /upload need access (chmod 777)  
 
To solve these issues you can open an interactive shell in the `application-backend` container.
```bash
$ docker exec -it <container-name> sh
```
Replace container-name by `application-backend`.  
Inside the container /var/www/application/  make sure all the dependencies are properly installed
and all migrations were successfully up:
```bash
$ composer install
```

```bash
$ ./vendor/bin/phpmig migrate
```

Setting folder ./public/upload  permissions to 777 allows read, write, and execute permissions for the owner, group, and all users.  
Make sure you are in the working directory /var/www/application.
```bash
$ chmod 777 ./public/upload
```

By now everything should be working properly. To interact with the API, a simple frontend  will be served on:  
`localhost:80/frontend`  

Please make sure port 80, port 3307, port 443 are free on your localhost. In case you need to change ports of containers you can edit the docker-compose.yaml file.

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

## Special notes
I need to solve some issues with my dockerfile and docker-compose file to make the containerization complete and easy to work correctly by just running `docker compose up -d`. Because of deadline I just put the instructions to handle the small issues and see the result of a working application( API+ Json and simple frontend). I count on your understanding in that matter. Thank you!

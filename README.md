# About the project
This project involves the development of a Symfony microservices application hosted on Docker containers, orchestrated by Nginx, and communicating via RabbitMQ message broker. This architecture enables flexible, scalable, and resilient application development, catering to modern software development requirements.

By integrating Firebase configuration into the Symfony microservices application, developers can leverage the power and flexibility of Firebase's backend services to build scalable, secure, and feature-rich applications that meet the demands of modern web and mobile development.
# Setup the project
Clone the project

```
make up
```

# Install composer packages

For the Notification service:

```
docker compose exec notification-service bash
```
And install composer packages
```
composer install
```
The service show be running on 
```
http://nextbaskettech.notification.localhost
```
For the User service:

```
docker compose exec user-service bash
```
And install composer packages
```
composer install
```
Also install database migration 
```
php bin/console doctrine:migrations:migrate
```
The service show be running on
```
http://nextbaskettech.user.localhost
```

- If  composer packages are not installed

### Error

@see https://datatracker.ietf.org/doc/html/rfc7807 via  symfony/serializer-pack and https://symfony.com/doc/current/controller/error_pages.html

For loggin:  Monolog\Formatter\JsonFormatter (this is WIP)

## Database


connect to the container then run

```
docker exec [CONTAINER_NAME] rabbitmq-plugins enable rabbitmq_management
```

## TEST

For User service
```
make test-user
```

## RabbitQM

For User service
```
make consume
```
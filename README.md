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
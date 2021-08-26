# Assessment Backend (Symfony/API-Platform) Internet Company

## Description

You are tasked with implementing RESTful API backend support for a simple forum
application. This forum allows registered users to create their own forum threads, as well as
post messages to these forum threads. And when a new message is posted, all users
involved with that thread receive a notification.

First, users must be able to authenticate themselves by means of an email address and a
password. Only authenticated users can interact with the API.
Each user should have the possibility to create, update or delete their own forum threads.
These forum threads consist of (at least) a text title between 4 and 64 characters, a text
body between 4 and 1024 characters and a timestamp. Users are not allowed to modify or
delete forum threads of other users.

Each user should also have the possibility to create or delete messages to or from forum
threads, both to threads of their own and to that of other users. A forum post consists of (at
least) a text body between 4 and 512 characters and a timestamp. Users are not allowed to
modify or delete messages of other users.

When a new message is posted, a notification is sent to all users involved in the respective
forum thread. This includes the forum thread creator and all users that have posted
messages in this thread. Notifications require a small text body and only need to be stored in
the persistence layer for now. They don’t actually have to be sent anywhere. Users should
be able to fetch and read their own notifications.

## Details

This boilerplate requests you to (at least) use the following technologies:
* PHP 8
* Symfony 5
* API-Platform 2
* Doctrine ORM
* Docker

You will have to add your own persistence mechanism to the Docker setup and configure it
with Doctrine ORM appropriately.

The RESTful API will consume and expose data in application/jsonformat. Any sensitive
information, like user email and password values, are to be excluded from response
payloads appropriately.

The response payload when fetching forum threads should also include the data of all of its
messages!

## Deploy

```sh
docker-compose down &&
docker-compose up -d --build &&
docker-compose exec php sh -c 'composer install'
docker-compose exec php sh -c 'php bin/console doctrine:migrations:migrate'
docker-compose exec php sh -c 'php bin/console doctrine:fixtures:load'
```

The application is served to `127.0.0.1:8080`

## Tests

```sh
docker-compose exec php sh -c 'php ./vendor/bin/phpunit'
```

## Api documentation

Go to the page [http://127.0.0.1:8080/api](http://127.0.0.1:8080/api).

Use fixture users credentials for authentication:

```
username: test@test.loc
password: test

username: notified@test.loc
password: test

username: author@test.loc
password: test
```

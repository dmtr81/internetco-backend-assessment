# Assessment Backend (Symfony/API-Platform) Internet Company

## Deploy

```sh
docker-compose down &&
docker-compose up -d --build &&
docker-compose exec php sh -c 'composer install'
```

The application is served to `127.0.0.1:8080`

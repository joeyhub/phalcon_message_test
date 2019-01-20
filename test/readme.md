Run:


```php -S 0.0.0.0:80 router.php```

docker-compose run

php app/cli.php Setup createTestUser

docker run --rm -it -v "$(pwd):/app" app /bin/bash
docker run --rm -it -v "$(pwd):/app" app php app/cli.php Setup createTestUser
Run:


docker-compose build

docker run --rm -it -v "$(pwd):/app" app php app/cli.php Setup createTestUser


docker-compose up

docker run --rm -it -v "$(pwd):/app" app /bin/bash
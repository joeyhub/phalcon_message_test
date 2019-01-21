Run:

```
docker-compose build
docker-compose up install
docker-compose up test
docker-compose up -d job
docker-compose up -d api
```

```
(
    export jwt=$(curl -q -X POST -d 'username=test&password=password' http://localhost/authentication/getJwt | php -r 'echo json_decode(stream_get_contents(STDIN));')
    curl -v -H "Authorization: Bearer $jwt" http://localhost/message/sent
    curl -v -X POST -H "Authorization: Bearer $jwt" -d 'message=test message' http://localhost/message/send
    curl -v -H "Authorization: Bearer $jwt" http://localhost/message/sent
)
```

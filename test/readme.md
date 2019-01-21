Run:

Setup:

```
docker-compose build
docker-compose up install
```

Assumes that standard ports for services are not in use on host.

Test that it works:

```
./test.sh
```

Test for errors (depends on first test for services):

```
(
    export jwt=$(curl -q -X POST -d 'username=test&password=password' http://localhost/authentication/getJwt | php -r 'echo json_decode(stream_get_contents(STDIN));')
    curl -v -H "Authorization: Bearer wrong" http://localhost/message/sent
    # Expect 401
    curl -v -H "Authorization: Bearer wrong" http://localhost/message/send
    # Expect 401
    # Remaining: expect error
    curl -q -X GET http://localhost/authentication/getJwt
    curl -q -X POST -d 'username=test' http://localhost/authentication/getJwt
    curl -q -X POST -d 'password=password' http://localhost/authentication/getJwt
    curl -q -X POST -d 'username=wrong&password=password' http://localhost/authentication/getJwt
    curl -q -X POST -d 'username=test&password=wrong' http://localhost/authentication/getJwt
    curl -X GET -H "Authorization: Bearer $jwt" http://localhost/message/sent
    curl -v -X GET -H "Authorization: Bearer $jwt" http://localhost/message/send
    curl -v -X POST -H "Authorization: Bearer $jwt" http://localhost/message/send
    curl -v -X POST -H "Authorization: Bearer $jwt" -d "message=\xc3\x28" http://localhost/message/send
)
```

Coverage does not include missing user scenarios (by id).
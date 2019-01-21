#/bin/bash

docker-compose up -d api
# Note: Need a proper wait (could work if this is converted to docker service).
sleep 5

jwt=$(curl -q -X POST -d 'username=test&password=password' http://localhost/authentication/getJwt | php -r 'echo json_decode(stream_get_contents(STDIN));')
# Expect JSON string with token

curl -v -H "Authorization: Bearer $jwt" http://localhost/message/sent
# Expect reads as in starting from 1 on first run, empty message array (json)

curl -v -X POST -H "Authorization: Bearer $jwt" -d 'message=test message' http://localhost/message/send
# Expect json int to be returned (jobid)

curl -v -H "Authorization: Bearer $jwt" http://localhost/message/sent
# Expext same as before but reads increased by one.

docker-compose up -d job
# Time dependent, not guaranteed to always work.

sleep 1
curl -v -H "Authorization: Bearer $jwt" http://localhost/message/sent
# Expect reads increased by one, message in list.


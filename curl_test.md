## Register
curl --request POST \
  --url http://127.0.0.1:8000/api/register \
  --header 'accept: application/json' \
  --header 'content-type: multipart/form-data' \
  --form firstName=testfirstname \
  --form lastName=testlastname \
  --form email=test1@test.test \
  --form password=test1234 \
  --form password_confirmation=test1234


## Log in
curl --request POST \
  --url http://127.0.0.1:8000/api/login \
  --header 'accept: application/json' \
  --header 'content-type: multipart/form-data' \
  --form email=test1@test.test \
  --form password=test1234


## The following API calls require us to provide token in Authorization header, so we need to replace <TOKEN> with the one we got from Login call
curl --request POST \
  --url http://127.0.0.1:8000/api/logout \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <TOKEN>' \
  --header 'content-type: multipart/form-data;'


## Find User with ID 1
curl --request GET \
  --url http://localhost:8000/api/users/show/1 \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <TOKEN>'


## Update user with ID 1
curl --request PUT \
  --url http://localhost:8000/api/users/edit/1 \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <TOKEN>' \
  --header 'content-type: application/x-www-form-urlencoded' \
  --data firstName=updatedFirst \
  --data lastName=updatedLast


## Show all Users
curl --request GET \
  --url http://localhost:8000/api/users/showall \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <TOKEN>'


## Delete user with ID 1
curl --request GET \
  --url http://localhost:8000/api/users/del/1 \
  --header 'accept: application/json' \
  --header 'authorization: Bearer <TOKEN>'

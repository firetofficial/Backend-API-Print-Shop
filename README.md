# Backend-API-Print-Shop
Backend PHP  for online printing website
</br>
POST /tamphuc/api/login.php HTTP/1.1
Host: localhost
Content-Type: application/json
Content-Length: 56

</br>Data {
    "username": "admin",
    "password": "123456"
}
</br>Result "{ 
    "success": true,
    "session_token": "9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe",
    "permissions": {
        "all": true
    },
    "user_id": 1
}"
</br></br>
</br>POST /tamphuc/api/register.php HTTP/1.1
Host: localhost
Authorization: 9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe
Content-Type: application/json
Content-Length: 164

</br>Data{
    "username": "user2",
    "password": "password123",
    "permissions": {
        "read": true,
        "write": false,
        "delete": false
    }
}
</br>Result ""{
    "success": true,
    "user_id": "4",
    "message": "User registered successfully"
}""
</br></br>
</br>PUT /tamphuc/api/update_permissions.php HTTP/1.1
Host: localhost
Authorization: d128720a880f55b01b4f8a334f3a787a3bc70b6bc80a302ef220829174e62fc1
Content-Type: application/json
Content-Length: 124

</br>Data {
    "user_id": 2,
    "permissions": {
        "read": true,
        "write": true,
        "delete": false
    }
}
</br>Result {
    "success": true,
    "message": "Permissions updated successfully"
}</br></br>
</br>POST http://localhost/tamphuc/api/check_role.php
Content-Type: application/json

</br>Data {
    "session_token": "9f384172a5482a37c01f08e4c927d7153b6fd7dc7d294f7bfac4ea03c59dcffe",
    "feature": "write"
}

</br>{
    "success": true,
    "access_granted": true
}
</br></br></br></br>
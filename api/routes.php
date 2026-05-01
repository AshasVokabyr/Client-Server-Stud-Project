<?php
return [
    'POST /register'   => ['UserController', 'register'],
    'POST /login'      => ['UserController', 'login'],
    'GET /users'       => ['UserController', 'index'],
    'GET /users/{id}'  => ['UserController', 'show'],
    'PUT /users/{id}'  => ['UserController', 'updatePassword'],
    'DELETE /users/{id}'=> ['UserController', 'delete']
];
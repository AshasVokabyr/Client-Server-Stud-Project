<?php

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
        exit;
    }

    // POST /register
    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['name']) || empty($input['email']) || empty($input['password'])) {
            $this->sendResponse(['status' => 'error', 'message' => 'Missing fields (name, email, password)'], 400);
        }
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(['status' => 'error', 'message' => 'Invalid email'], 400);
        }
        if (strlen($input['password']) < 6) {
            $this->sendResponse(['status' => 'error', 'message' => 'Password must be at least 6 characters'], 400);
        }
        $user = $this->userModel->create($input['name'], $input['email'], $input['password']);
        if (!$user) {
            $this->sendResponse(['status' => 'error', 'message' => 'Email already exists'], 409);
        }
        $this->sendResponse(['status' => 'success', 'message' => 'User registered', 'user' => $user], 201);
    }

    // POST /login
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['email']) || empty($input['password'])) {
            $this->sendResponse(['status' => 'error', 'message' => 'Email and password required'], 400);
        }
        if ($this->userModel->verifyPassword($input['email'], $input['password'])) {
            $this->sendResponse(['status' => 'success', 'message' => 'Login successful']);
        } else {
            $this->sendResponse(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }
    }

    // GET /users
    public function index() {
        $users = $this->userModel->getAll();
        $this->sendResponse(['status' => 'success', 'data' => $users]);
    }

    // GET /users/{id}
    public function show($id) {
        $user = $this->userModel->getById((int)$id);
        if (!$user) {
            $this->sendResponse(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $this->sendResponse(['status' => 'success', 'data' => $user]);
    }

    // PUT /users/{id}
    public function updatePassword($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['new_password'])) {
            $this->sendResponse(['status' => 'error', 'message' => 'New password required'], 400);
        }
        if (strlen($input['new_password']) < 6) {
            $this->sendResponse(['status' => 'error', 'message' => 'Password must be at least 6 characters'], 400);
        }
        $updated = $this->userModel->updatePassword((int)$id, $input['new_password']);
        if (!$updated) {
            $this->sendResponse(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $this->sendResponse(['status' => 'success', 'message' => 'Password updated']);
    }

    // DELETE /users/{id}
    public function delete($id) {
        $deleted = $this->userModel->delete((int)$id);
        if (!$deleted) {
            $this->sendResponse(['status' => 'error', 'message' => 'User not found'], 404);
        }
        $this->sendResponse(['status' => 'success', 'message' => 'User deleted']);
    }
}
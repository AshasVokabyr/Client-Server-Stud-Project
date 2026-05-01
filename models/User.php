<?php

class User {
    private $storageFile = __DIR__ . '/../storage/users.json';

    private function readUsers() {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        $json = file_get_contents($this->storageFile);
        return json_decode($json, true) ?? [];
    }

    private function writeUsers($users) {
        file_put_contents($this->storageFile, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function getAll() {
        $users = $this->readUsers();
        return array_map(function($user) {
            unset($user['password']);
            return $user;
        }, $users);
    }

    public function getById($id) {
        $users = $this->readUsers();
        foreach ($users as $user) {
            if ($user['id'] == $id) {
                unset($user['password']);
                return $user;
            }
        }
        return null;
    }

    public function findByEmail($email) {
        $users = $this->readUsers();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function create($name, $email, $password) {
        $users = $this->readUsers();
        if ($this->findByEmail($email)) {
            return false;
        }
        $newId = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;
        $newUser = [
            'id' => $newId,
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $users[] = $newUser;
        $this->writeUsers($users);
        unset($newUser['password']);
        return $newUser;
    }

    public function updatePassword($id, $newPassword) {
        $users = $this->readUsers();
        foreach ($users as &$user) {
            if ($user['id'] == $id) {
                $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->writeUsers($users);
                return true;
            }
        }
        return false;
    }

    public function delete($id) {
        $users = $this->readUsers();
        $originalCount = count($users);
        $users = array_filter($users, function($user) use ($id) {
            return $user['id'] != $id;
        });
        if (count($users) === $originalCount) {
            return false;
        }
        $this->writeUsers(array_values($users));
        return true;
    }

    public function verifyPassword($email, $plainPassword) {
        $user = $this->findByEmail($email);
        if (!$user) return false;
        return password_verify($plainPassword, $user['password']);
    }
}
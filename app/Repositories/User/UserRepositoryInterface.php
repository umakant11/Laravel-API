<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function login(array $data);
    public function register(array $data);
    public function userDetails();
    public function all();
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /*
     ** User login
     */
    public function login(UserLoginRequest $request)
    {
        try {
            $response = $this->userRepository->login($request->all());
            return $response;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /*
     ** User Register
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $response = $this->userRepository->register($request->all());
            return $response;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /*
     ** User Details
     */
    public function userDetails(Request $request)
    {
        try {
            $response = $this->userRepository->userDetails();
            return $response;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function all()
    {
        try {
            $response = $this->userRepository->all();
            return $response;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function csvCreate(){
        $headers = array(
            "Content-type" => "text/csv",
        );

        $users = User::select(
            'users.name',
            'users.email',
        )
        ->get();

        $columns = array(
            'Name',
            'Email',
        );

        $callback = function () use ($users, $columns) {
            $file = fopen('export.csv', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                fputcsv($file, array(
                    $user->name,
                    $user->email
                ));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

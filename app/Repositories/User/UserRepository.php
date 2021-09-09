<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Resources\Json\JsonResource;


class UserRepository implements UserRepositoryInterface
{
    public function __construct(Controller $response)
    {
        $this->response = $response;
    }

    public function login(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $user = User::find(Auth::user()->id);
            $detail['name'] =  $user->name;
            $detail['email'] = $user->email;
            $detail['token'] = $user->createToken('MyApp')->accessToken;
            return $this->response->sendResponse(true, "User login successfully.", $detail,  $this->response::SUCCESS_STATUS_CODE);
        } else {
            return $this->response->sendResponse(false, "Invalid username or password.", [],  $this->response::UNAUTHORISED_STATUS_CODE);
        }
    }

    public function register(array $data)
    {
        $input = $data;
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        \App\Jobs\SendUserRegisterEmail::dispatch($user)->delay(now()->addSeconds(5));

        $detail['name'] =  $user->name;
        $detail['email'] = $user->email;
        $detail['token'] =  $user->createToken('MyApp')->accessToken;
        return $this->response->sendResponse(true, "User register successfully.", $detail,  $this->response::SUCCESS_STATUS_CODE);
    }

    public function userDetails()
    {
        $user =  Auth::user();
        if ($user != null) {
            $detail =  (new UserResource($user))->additional(['success' => 'true','message'=>"User detail"]);;
            return $detail;
        }
        $data['message'] = "User details not found.";
        return $this->response->sendResponse(false,  $data['message'], [],  $this->response::NOTFOUND_STATUS_CODE);
    }

    public function all()
    {
        $users =  User::paginate(5);
        if ($users->isNotEmpty()) {
            $userList =  (new UserCollection($users))->additional(['success' => 'true','message'=>"User list"]);
            return $userList;
        }
        $data['message'] = "No record found.";
        return $this->response->sendResponse(false,  $data['message'], [],  $this->response::NOTFOUND_STATUS_CODE);
    }
}

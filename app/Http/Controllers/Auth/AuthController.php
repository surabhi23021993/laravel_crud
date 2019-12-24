<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Auth;

//use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use App\Http\Transformers\UserAuthTransformer;
//use Illuminate\Http\Response;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

//use Symfony\Component\Debug\ErrorHandler;

/**
 * Description of AuthController
 *
 * @author chandrashekar
 */
class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware("guest", ["except" => "getLogout"]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only("email", "password");
        // print_r("hiiii");
        // die();
        $userObject = new User();
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                // return $this->response->error(["error" => "User credentials are not correct!"], 401);
                $response = 'Your Username and/or Password is incorrect.';
//                return $response;
                //                throw new \Exception($response);
                throw new \Dingo\Api\Exception\StoreResourceFailedException($response, $userObject->errors());
            }
        } catch (JWTException $ex) {
            return $this->response->error(["error", "Something went wrong."]);
        }

        $email = $request["email"];

        $user = User::where('email', $email)->first();

        $user->remember_token = $token;

        if ($user) {
            return $this->response->item($user, new UserAuthTransformer())->setStatusCode(200);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Invalid email address entered.', $userObject->errors());
        }
//        return $this->response->array(compact("token"))->setStatusCode(200);
    }

}

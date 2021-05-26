<?php

namespace App\Http\Controllers;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use DB;
use app;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Check if field is not empty
        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all fields']);
        }

        $client = new Client();

        try {
            return $client->post(config('service.passport.login_endpoint'), [
                "form_params" => [
                    "client_secret" => config('service.passport.client_secret'),
                    "grant_type" => "password",
                    "client_id" => config('service.passport.client_id'),
                    "username" => $request->email,
                    "password" => $request->password
                ]
            ]);
        } catch (BadResponseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function register(Request $request)
        {
            $first_name = $request->first_name;
            $last_name  = $request->last_name;
            $email = $request->email;
            $password = $request->password;

            // Check if field is not empty
            if (empty($first_name) or empty($email) or empty($password)) {
                return response()->json(['status' => 'error', 'message' => 'Вы должны заполнить все поля']);
            }

            // Check if email is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['status' => 'error', 'message' => 'Вы должны ввести действующий адрес электронной почты']);
            }

            // Check if password is greater than 5 character
            if (strlen($password) < 6) {
                return response()->json(['status' => 'error', 'message' => 'Пароль должен состоять минимум из 6 символов.']);
            }
            // Check if user already exist
            if (User::where('email', '=', $email)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'Пользователь с этим адресом электронной почты уже существует']);
            }
            // Create new user
            try {
                $user = new User();
                $user->first_name = $first_name;
                $user->last_name  = $last_name;
                $user->email      = $email;
                $user->password   = app('hash')->make($password);
                $user->confirm_password = app('hash')->make($password);
                $user->save();
                return response()->json(['status'             => 'Success',
                                         'first_name'         =>  $first_name,
                                         'last_name'          =>  $last_name,
                                         'email'              =>  $email,
                                         'password'           =>  $password,
                                         'confirm_password'   =>  $password,
                                        ]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }
}

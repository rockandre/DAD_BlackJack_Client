<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use App\Mail\resetlink; 
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Illuminate\Support\Facades\App;
use Illuminate\Mail\TransportManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

define('YOUR_SERVER_URL', 'http://blackjackdad.ga');
// Check "oauth_clients" table for next 2 values:
define('CLIENT_ID', '2');

// lage pc
// define('CLIENT_SECRET','AOy4KbJhtpqmM8oSDQY7DxEszOzOih5tWB9YZgIF');

// server online
define('CLIENT_SECRET','zAU1HuNKTz1sBW28xXdoVeNBadUbdZpG7LQZtO8z');



class LoginControllerAPI extends Controller
{

    use SendsPasswordResetEmails;

	public function login(Request $request)
	{
        $user = User::orWhere('email', $request->email)->orWhere('nickname', $request->email)->first();
        if(!$user){
            return response()->json(['msg'=>'User/Email dont exist'], 400);
        }

        if($user->activated == 0){
            return response()->json(['msg'=>'User not active.'], 400);
        }

        if($user->blocked == 1){
            return response()->json(['msg'=>'User blocked.'], 400);
        }

		$http = new \GuzzleHttp\Client;
		$response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
			'form_params' => [
				'grant_type' => 'password',
				'client_id' => CLIENT_ID,
				'client_secret' => CLIENT_SECRET,
				'username' => $request->email,
				'password' => $request->password,
				'scope' => ''
			],
			'exceptions' => false,
		]);
		$errorCode= $response->getStatusCode();

		if ($errorCode=='200') {
			return json_decode((string) $response->getBody(), true);
		} else {
			return response()->json(
				['msg'=>'User credentials are invalid'], $errorCode);
		}
	}

	public function logout()
	{
		\Auth::guard('api')->user()->token()->revoke();
		\Auth::guard('api')->user()->token()->delete();
		return response()->json(['msg'=>'Token revoked'], 200);
	}

	public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);


        if ($request->wantsJson() && !$validator->fails()) {
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                return response()->json(['data' => trans('passwords.user')], 400);
            }

            $token = $this->broker()->createToken($user);

            try{

                $config = DB::table('config')->first();
                $mailConfigs = json_decode($config->platform_email_properties);
                config([
                   'mail.host' => $mailConfigs->host,
                   'mail.port' => $mailConfigs->port,
                   'mail.encryption' =>$mailConfigs->encryption,
                   'mail.username' => $config->platform_email,
                   'mail.password' => $mailConfigs->password
               ]);

                $app = App::getInstance();
                $app->singleton('swift.transport', function ($app) {
                    return new TransportManager($app);
                });
                $mailer = new Swift_Mailer($app['swift.transport']->driver());
                Mail::setSwiftMailer($mailer);

                Mail::to($user->email)->send(new resetlink($token, $config->platform_email));
                return response()->json(['data' => 'Email sended.']);
            }
            catch(\Exception $e){
                return response()->json(['data' => 'Problems sending email: '.$e], 400);
            }
        } else {
            return response()->json(['data' => 'Invalid request.'], 400);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required'
        ]);

        if ($request->wantsJson() && !$validator->fails()) {
            
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                return response()->json(['msg' => trans('passwords.user')], 400);
            }

            $reminder = DB::table('password_resets')->where('email', $user->email)->first();
            if (!$reminder or !Hash::check($request->input('token'), $reminder->token)) {
                return response()->json(['data' => 'Token valid.'], 400);
            }

            $user->password = Hash::make($request->input('password'));
            $user->save();

            DB::table('password_resets')->where('email', $user->email)->delete();

            return response()->json(['data' => 'Password changed.'], 200);
        } else {
            return response()->json(['data' => 'Invalid request.'], 400);
        }
    }

}

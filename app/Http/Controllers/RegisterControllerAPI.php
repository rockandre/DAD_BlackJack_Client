<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Mail\TransportManager;
use Mail;
use Swift_Mailer;
use App\Mail\MailConfirmation;

class RegisterControllerAPI extends Controller
{
	public function register(Request $request)
	{
		$data = $request->all();
		$validator = $this->validator($data);
		if($validator->passes()) {
			$user = User::create([
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => (bcrypt($data['password'])),
				'nickname' => $data['nickname'],
				'email_token' => str_random(25),
			]);

			try {
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

				Mail::to($user)->send(new MailConfirmation($user));
			} catch(Exception $e) {
				return response()->json("Error sending email!", 400);
			}

			return response()->json("Registed with success!", 200);
		}
		return response()->json($validator->errors(), 400);
	}

	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
			'nickname' => 'required|string|max:20|unique:users',
		]);
	}

	public function confirmation($id, $token)
	{
		$user = User::where('id', $id)->where('email_token', $token)->first();
		if(!is_null($user)) {
			$user->activated = 1;
			$user->email_token = 'ACTIVATED';
			$user->save();
			return redirect(url('/'))->with('status', "1");
		}
		return redirect(url('/'))->with('status', "0");
	}
}

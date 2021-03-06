<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ConfigEmailResource as ConfigEmailResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Mail\TransportManager;
use Hash;

class ConfigEmailControllerAPI extends Controller
{
    public function getSettings() {
        $config = DB::table('config')->first();
        return new ConfigEmailResource($config);
    }

    public function update(Request $request) {
		$validator = Validator::make($request->all(), [
			'driver' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'host' => 'required',
            'port' => 'required|integer|min:1',
            'encryption' => 'required',
            'passwordAdmin' => 'required'
        ]);

        if ($request->wantsJson() && !$validator->fails()) {

            try{
                if (!Hash::check($request->input('passwordAdmin'), $request->user()->password)) {
                    return response()->json(
                        ['passwordAdmin' => 'Admin Password incorrect.'], 400);
                }

                $email = $request->input('email');

                config([
                	'mail.driver' => $request->input('driver'),
                    'mail.host' => $request->input('host'),
                    'mail.port' => $request->input('port'),
                    'mail.encryption' => $request->input('encryption'),
                    'mail.username' => $email,
                    'mail.password' => $request->input('password')
                ]);

                $app = App::getInstance();
                $app->singleton('swift.transport', function ($app) {
                    return new TransportManager($app);
                });
                $mailer = new Swift_Mailer($app['swift.transport']->driver());
                Mail::setSwiftMailer($mailer);

                $html = "Test of email";
                Mail::send(array(), array(), function ($message) use ($html, $email) {
                    $message->to('fabiolage13@gmail.com')
                    ->subject('Test')
                    ->from($email)
                    ->setBody($html);
                });
                $platform_email_properties = json_encode(array_except($request->all(), ['email']));
                //MAKES UPDATE
                DB::table('config')->where('id', 1)->update(['platform_email' => $email, 'platform_email_properties' => $platform_email_properties]);

                return response()->json(['msg' => 'Changes saved.']);
            }
            catch(\Exception $e){
                return response()->json(['msg' => 'Configuration is not valid:'.$e], 400);
            }
        } else {
            return response()->json(['msg' => 'Invalid request.'.$validator->errors()], 400);
        }

    }
}

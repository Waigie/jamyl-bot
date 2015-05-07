<?php namespace JamylBot\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use JamylBot\Http\Requests;
use JamylBot\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JamylBot\User;
use JamylBot\Userbot\Userbot;
use Socialize;

class AuthController extends Controller {

    public function __construct()
    {
        $this->middleware('guest');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
//
	}

    public function redirectToProvider()
    {
        return Socialize::with('eveonline')->redirect();
    }

    public function handleProviderCallback()
    {
        $char = Socialize::with('eveonline')->user();

        try {
            $user = User::where('char_id', $char->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $user = User::createAndFill([
                'char_id'   => $char->id,
                'char_name' => $char->name,
                'password'  => Userbot::generatePassword(16),
            ]);
        }
        Auth::login($user, true);
        return redirect('home');
    }

}

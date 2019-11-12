<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Notifications\UserRegisteredSuccessfully;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {

        $this->middleware('guest:admin');
        $this->middleware('guest:applicant');
        $this->middleware('guest:viewer');
        $this->middleware('guest:referee');
    }

    /**
     * Show registration form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        $role = basename($request->url());
        if ($role === 'register')
            $role = "applicant";
        return view("auth.register", ['url' => $role]);
    }

    /**
     * Register new account.
     *
     * @param Request $request
     * @return User
     */
    protected function register(Request $request)
    {
        $role = basename($request->url());
        if ($role === 'register')
            $role = "applicant";

        $get_role = Role::where('name', '=', $role)->first();

        /** @var User $user */
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $validatedData['password'] = bcrypt(array_get($validatedData, 'password'));
            $validatedData['confirmation'] = str_random(30) . time();
            $validatedData['password_salt'] = "10";//harce
            $validatedData['requested_role_id'] = $get_role->id;
            $validatedData['state'] = "inactive";//harce

            $user = app(User::class)->create($validatedData);

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('status', 'Unable to create new user.');
        }
        $user->notify(new UserRegisteredSuccessfully($user));
        $request->session()->flash('success', getMessage('email_sent'));
        return redirect()->route('login.' . $role);
    }

    /**
     * Activate the user with given activation code.
     * @param string $activationCode
     * @return string
     */
    public function activateUser(string $activationCode)
    {

        try {
            $user = app(User::class)->where('confirmation', $activationCode)->first();
            $role = Role::where('id', '=', $user->requested_role_id)->first();
            if (!$user) {
                return redirect()->route('register.' . $role->name)->with('status',"The code does not exist for any user in our system.");
            }

            if ($user->state === "inactive") {
                if ($role->name !== "applicant")
                    $user->state = "waiting";
                else {
                    $user->state = "active";
                }
                    $user->confirmation = 1;
                    $user->role_id = $user->requested_role_id;
                    $user->requested_role_id = 0;
                    $user->save();

                if($role->name === "applicant")
                    return redirect()->route('login.' . $role->name)->with('success',getMessage('send_applicant'));

                else{
                    $objSend = new \stdClass();
                    $objSend->message = "message";
                    $objSend->sender = 'Ansef';
                    $objSend->receiver = 'collages';

                   Mail::to("webmaster@ansef.org")->send(new \App\Mail\SendToAdmin($objSend));
                    return redirect()->route('login.' . $role->name)->with('success',getMessage('email_other'));
                }
            } else {
                return redirect()->route('login.' . $role->name)->with('',"You are active " . $role->name . ".");

            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage('wrong');
        }
//        return redirect()->to('/home');
    }
}
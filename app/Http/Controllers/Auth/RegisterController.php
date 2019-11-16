<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Person;
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
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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
    public function activateUser(string $useremail, string $activationCode)
    {
        try {
            $user = app(User::class)->where('email', $useremail)->first();
            if (!$user) {
                return redirect()->route('register.' . $role->name)->with('status',"Email not registered yet.");
            }
            $role = Role::where('id', '=', $user->requested_role_id)->first();
            if($user->state === "active") {
                $role = Role::where('id', '=', $user->role_id)->first();
                return redirect()->route('login.' . $role->name)->with('status',"Your account is active. You may now log in.");
            }
            if($user->state === "waiting") {
                $role = Role::where('id', '=', $user->role_id)->first();
                return redirect()->route('login.' . $role->name)->with('status',"You account is awaiting activation by the portal administrator. Try again later, or contact admin@ansef.org.");
            }

            if ($user->confirmation != $activationCode) {
                return redirect()->route('register.' . $role->name)->with('status',"Registration code is invalid. Please contact dopplerthepom@gmail.com.");
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

                $person = new Person;
                $person->user_id = $user->id;
                $person->first_name = "";
                $person->last_name = "";
                $person->save();

                if($role->name === "applicant")
                {
                    return redirect()->route('login.' . $role->name)->with('success',getMessage('send_applicant'));
                }
                else{
                    $objSend = new \stdClass();
                    $objSend->message = "A person with email " . $user->email . " has requested access in the role of " . $role->name . ". Please log into the ANSEF portal to enable the account.";
                    $objSend->sender = 'dopplerthepom@gmail.com';
                    $objSend->receiver = 'dopplerthepom@gmail.com';

                    Mail::to('dopplerthepom@gmail.com')->send(new \App\Mail\SendToAdmin($objSend));

                    return redirect()->route('login.' . $role->name)->with('success',getMessage('email_other'));
                }
            }
            else {
                return redirect()->route('login.' . $role->name)->with('',"Your account is active. You may now log in.");
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage('wrong');
        }
//        return redirect()->to('/home');
    }
}

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
use Illuminate\Support\Facades\Validator;

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
            'g-recaptcha-response' => 'required|recaptcha'
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
        $request->session()->flash('success', messageFromTemplate('email_sent'));
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
                return redirect()->route('login.' . $role->name)->with('status',"Your account is awaiting activation by the portal administrator. Try again later, or contact admin@ansef.org.");
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
                $person->specialization = "";
                $person->type = $role->name;
                $person->save();

                if($role->name === "applicant")
                {
                    return redirect()->route('login.' . $role->name)->with('success',messageFromTemplate('send_applicant'));
                }
                else{
                    $objSend = new \stdClass();
                    $objSend->message = "A person with email " . $user->email . " has requested access in the role of " . $role->name . ". Please log into the ANSEF portal to enable the account.";
                    $objSend->sender = 'dopplerthepom@gmail.com';
                    $objSend->receiver = 'dopplerthepom@gmail.com';

                    Mail::to('dopplerthepom@gmail.com')->send(new \App\Mail\SendToAdmin($objSend));

                    return redirect()->route('login.' . $role->name)->with('success',messageFromTemplate('email_other'));
                }
            }
            else {
                return redirect()->route('login.' . $role->name)->with('',"Your account is active. You may now log in.");
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate('wrong');
        }
//        return redirect()->to('/home');
    }

    public function activateAddedUser(string $useremail, string $activationCode, string $admin)
    {
        try {
            $user = app(User::class)->where('email', $useremail)->first();
            if (!$user) {
                return redirect()->route('login.' . 'applicant')->with('status',"Email not registered.");
            }
            $role = Role::where('id', '=', $user->requested_role_id)->first();
            if($user->state === "active") {
                $role = Role::where('id', '=', $user->role_id)->first();
                return redirect()->route('login.' . $role->name)->with('status',"Your account is active. You may now log in.");
            }
            if($user->state === "waiting") {
                $role = Role::where('id', '=', $user->role_id)->first();
                return redirect()->route('login.' . $role->name)->with('status',"Your account is awaiting activation by the portal administrator. Try again later, or contact admin@ansef.org.");
            }

            if ($user->confirmation != $activationCode) {
                return redirect()->route('register.' . $role->name)->with('status',"Registration code is invalid. Please contact dopplerthepom@gmail.com.");
            }

            if ($user->state === "inactive") {
                return view("auth.passwords.set", ['token' => $user->confirmation, 'id' => $user->id, 'email' => $user->email, 'role' => $role->name, 'admin' => $admin]);
            }
            else {
                return redirect()->route('login.' . $role->name)->with('',"Your account is active. You may now log in.");
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate('wrong');
        }
//        return redirect()->to('/home');
    }

    public function setPassword(Request $request) {
        try {
            $v = Validator::make($request->all(), [
                'password' => 'required|min:8|same:password_confirmation'
            ]);

            if (!$v->fails()) {
                $user = User::find($request->id);
                if(!empty($user)) {
                    if($user->confirmation == $request->token) {
                        $user->state = "active";
                        $user->role_id = $user->requested_role_id;
                        $user->requested_role_id = 0;
                        $user->confirmation = 1;
                        $user->password = bcrypt($request->password);
                        $user->save();

                        $role = Role::find($user->role_id);
                        $person = new Person;
                        $person->user_id = $user->id;
                        $person->first_name = "";
                        $person->last_name = "";
                        $person->specialization = "";
                        $person->type = $role->name;
                        $person->save();
                    }
                }
                if($request->admin == "true")
                    return redirect()->action('Admin\AccountController@index');
                else
                    return redirect()->route('login.' . $request->role)->with('', "Your account is active. You may now log in.");
            } else return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate('wrong'));
        }
    }
}

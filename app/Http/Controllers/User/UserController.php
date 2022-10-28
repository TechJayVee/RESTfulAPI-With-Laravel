<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

use App\Models\User;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return $this->showAll($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'=> 'required|min:5|confirmed',  
        ]);

        $user = User::create([
        'name'=> $request->name,
        'email'=> $request->email,
        'password'=> bcrypt($request->password),
        'verified'=> User::UNVERIFIED_USER,
        'verification_token'=>User::generateVerificationCode(),
        'admin'=>User::REGULAR_USER,
        ]);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
   
        $request->validate([
            'email' => 'unique:users,email,'.$user->id,
            // 'password'=>'required',   
            'admin'=> 'in:'.  User::ADMIN_USER . ','. User::REGULAR_USER,  
        ]);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password =bcrypt( $request->password);
        }
        if($request->has('admin')){
            if(!$user->isVerified()){
                return $this->errorResponse('Only the verified user can modify the admin field', 409);
            }
            $user->admin = $request->admin;
        }
        if(!$user->isDirty()){
            return $this->errorResponse('You need to specify different value to update!', 422);
        }
        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
    
         $user->delete();
         return $this->showOne($user);
    }

    public function verify($token)
    {
    
         $user=User::where('verification_token', $token)->firstOrFail();
         $user->verified = User::VERIFIED_USER;
         $user->verification_token = null;
         $user->save();

         return $this->showMessage('The account has been verified Successfully');
    }


    public function resend(User $user)
    {
        if($user->isVerified()){
            return $this->errorResponse("This User is already Verified", 409);
        }

         retry(5, function() use ($user) 
            {
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
    
       
         return $this->showMessage('The verification Message has been resend.');
    }
}

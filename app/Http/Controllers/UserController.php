<?php

namespace App\Http\Controllers;

use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class UserController extends Controller
{

	   public function register(Request $request)
    {
        try {
            $rules = [
                'full_name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|min:4',
            ];
            $credential_met = request()->only('full_name', 'email', 'password');
            $validator = Validator::make($credential_met, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error' => $error], 500);
            }
            $old_user = User::where('email', '=', $credential_met['email'])->first();
            if ($old_user instanceof User) {
                return response()->json(['error' => 'email address already taken'], 500);
            } else {
                $newUser = new User();
                $newUser->full_name = $credential_met['full_name'];
                $newUser->email = $credential_met['email'];
                $newUser->password = bcrypt($credential_met['password']);
                $newUser->account_status = false;
                $newUser->role_id = isset($credential["role_id"]) ? $credential['role_id'] : 3;
                if ($newUser->save()) {
                    return response()->json(['status' => true, 'message' => 'successfully registered', 'user' => $newUser], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Whoops! failed to save the user', 'error' => 'Unable to register the user!!! Please try again'], 500);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Whoops something went wrong!', 'error' => $exception->getMessage()], 500);
        }
    }


     public function authenticate() {
        try{
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|min:4',
            ];
            $credential= request()->only('email','password');

            $validator = Validator::make($credential, $rules);

            if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error'=> $error],500);
            }
            $token = JWTAuth::attempt($credential);
            if(!$token){
                return response()->json(['error'=>'Invalid credential used!!!'],401);
            }else{
                $user= JWTAuth::toUser($token);
                if($user instanceof User){
                    if($user->account_status == true){
                        $user_info = User::with('userRole')->where('id', '=', $user->id)->first();
                        return response()->json(['status'=>true, 'message'=> 'successfully authenticated', 'token'=>$token, 'user'=>$user_info],200);
                    }else{
                        return response()->json(['status'=>false, 'message'=> 'Inactive Account', 'error'=>'Your account is not active yet!!!'],500);
                    }
                }
            }
            return response()->json(['error'=>'Something went Wrong!!!'],500);
        }catch (\Exception $exception){
            return response()->json(['status'=> false, 'message'=> 'Whoops something went wrong!', 'error'=>$exception->getMessage()],500);
        }
    }

     public function getUsers()
    {
        try {
            $token = JWTAuth::getToken();
            $this_user = JWTAuth::toUser($token);
            if ($this_user instanceof User) {
                $users = User::with('userRole')->orderBy('id', 'DESC')->paginate(10);
                return response()->json(['status' => true, 'message' => 'users fetched successfully', 'users' => $users], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'unable to fetch users', 'error' => 'unable to fetch users '], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'unable to fetch users', 'error' => $exception->getMessage()], 500);
        }
    }


      public function update() {
        try{
            $credential = request()->only('id', 'full_name','email','password', 'role_id', 'account_status');
            $rules = [
                'id' => 'required'
            ];
            $validator = Validator::make($credential, $rules);
            if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error'=> $error],500);
            }
            $oldUser = User::where('id', '=', $credential['id'])->first();
            if($oldUser instanceof User) {
                $oldUser->full_name = isset($credential['full_name'])? $credential['full_name']: $oldUser->full_name;
                $oldUser->email = isset($credential['email'])? $credential['email']: $oldUser->email;
                $oldUser->password = isset($credential['password'])? bcrypt($credential['password']): $oldUser->password;
                $oldUser->role_id = isset($credential['role_id'])? $credential['role_id']: $oldUser->role_id;
                $oldUser->account_status = isset($credential['account_status'])? $credential['account_status']: $oldUser->account_status;
                if($oldUser->update()){
                    return response()->json(['status'=> true, 'message'=> 'user successfully updated', 'user'=>$oldUser],200);
                } else {
                    return response()->json(['status'=> false, 'message'=> 'unable to update user information', 'error'=>'something went wrong! please try again'],200);
                }
            }else {
                return response()->json(['status'=>false, 'message'=> 'Whoops! this email address is already taken', 'error'=>'email duplication'],500);
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }

     public function activateUser($id) {
        try{
            $oldUser = User::where('id', '=', $id)->first();
            $this_user = parent::getUser();
            if($oldUser instanceof User && $this_user instanceof User) {
                if($oldUser->id != $this_user->id){
                    $oldUser->account_status = !$oldUser->account_status;
                    if($oldUser->update()){
                        return response()->json(['status'=> true, 'message'=> 'user successfully updated', 'user'=>$oldUser],200);
                    } else {
                        return response()->json(['status'=> false, 'message'=> 'unable to update user information', 'error'=>'something went wrong! please try again'],500);
                    }
                } else {
                    return response()->json(['status'=> false, 'message'=> 'unable to update user information', 'error'=>'updating self account is invalid'],500);
                }
            }else {
                return response()->json(['status'=>false, 'message'=> 'Whoops! user info not found', 'error'=>'user info not found'],500);
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }


     public function getRole()
    {
        $role = UserRole::all();

        $response = [
            'roles' => $role
        ];
        return response()->json($response, 200);
    }

    public function delete($id) {
        try{
            $oldUser = User::where('id', '=', $id)->first();
            $this_user = parent::getUser();
            if($oldUser instanceof User && $this_user instanceof User){
                if($oldUser->id != $this_user->id){
                    if($oldUser->delete()){
                        return response()->json(['status'=> true, 'message'=> 'user successfully deleted'],200);
                    }else {
                        return response()->json(['status'=>false, 'message'=> 'Whoops! failed to delete the user account', 'error'=>'failed to delete the user account'],500);
                    }
                }else {
                    return response()->json(['status'=>false, 'message'=> 'Whoops! self deletion is not valid', 'error'=>'self deletion is not valid'],500);
                }
            }else{
                return response()->json(['status'=>false, 'message'=> 'Whoops! unable to find the user information', 'error'=>'failed to find the user information'],500);
            }
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
        }
    }
}

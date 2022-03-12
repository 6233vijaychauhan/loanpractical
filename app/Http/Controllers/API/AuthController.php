<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ResponseController;
use Exception;

class AuthController extends ResponseController
{
    public function login(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->_sendErrorResponse("Please enter field is required.", $validator->messages());
            }

            $credentials = $request->only(['email', 'password']);

            if (Auth::attempt($credentials)) {
                $user = User::find(Auth::user()->id);
                $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
                $data['user'] = $user;
                return $this->_sendResponse("Login successfully.", $data);
            } else {
                return $this->_sendErrorResponse("Login failed! Credentials does not match our records.", $validator->messages());
            }
        } catch (Exception $e) {
            return $this->_sendErrorResponse("Unable to process.", $e->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
            if ($validator->fails()) {
                return $this->_sendErrorResponse("Please enter field is required.", $validator->messages());
            }

            $isExist = User::where('email',$request->email)->first();
            if($isExist){
                return $this->_sendErrorResponse("User is already registered");
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'role_id' => 2,
                'status' => 1,
            ]);
            
            $data['name'] = $user->name;
            $data['token'] = $user->createToken('MyApp')->accessToken;
            return $this->_sendResponse("Registration successfully.", $data);
        } catch (Exception $e) {
            return $this->_sendErrorResponse("Unable to process.", $e->getMessage(), 500);
        }
    }

    public function logout (Request $request) {
        try {
            $token = $request->user()->token();
            $token->revoke();
            return $this->_sendResponse("You have been successfully logged out!");
        } catch (\Exception $e) {
            return $this->_sendErrorResponse("Unable to process.", $e->getMessage(), 500);
        }
    }

    public function adminLogin(Request $request)
	{
		$request->validate([
            'email' => 'required|email',
    		'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);
        
		if (Auth::attempt($credentials)) {
			
			$user = Auth::user();
			$success['token'] = $user->createToken('MyApp', ['*'])->accessToken;
			return response()->json(['success' => $success], 200);
		}
		else {
			return response()->json(['error' => 'Unauthorized'], 401);
		}
	}

    public function adminRegister(Request $request)
	{
		$request->validate([
            'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
        ]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);
		$success['name'] = $user->name;
		$success['token'] = $user->createToken('MyApp', ['*'])->accessToken;
		return response()->json(['success' => $success], 200);
	}
    
    
}

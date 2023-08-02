<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
       try{
        
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|unique:users|max:255',
            'password'=>'required|string|min:6',
            'role_id'=>'required'

        ]);


        if($validator->fails()){
           
            return response()->json(['status'=>false,'message'=>'validation error','error'=>$validator->errors()]);
   
        }

        $user = User::create([
        'role_id'=>$request->role_id,
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>$request->password,
        

        ]);

        return response()->json(['message'=>'user Registered Successfully','user'=>$user]);
    }
    catch(\Throwable $th){

        return response()->json(['status'=>false,'message'=>$th->getMessage()],500);
    }
}

 // User login and return JWT token
/**
 * Undocumented function
 *
 * @param Request $request
 * @return void
 */
 public function login(Request $request){

try{    
        $validateUser = Validator::make($request->all(),[
        'email'=>'required|email',
        'password'=>'required|string'
        ]);

        if($validateUser->fails()){
          
            return response()->json(['status'=>false,'message'=>
            'validation error','error'=>$validateUser->errors()],401);
        }

    if(!Auth::attempt($request->only(['email', 'password']))){

    return response()->json(['status'=>false,'message'=>'Email & Password does not match with our record.'],401);
    
}

    $user = User::where('email',$request->email)->first();

    return response()->json(['status'=>true,
    'message'=>'User Logged In Successfully', 
    'token' => $user->createToken("API TOKEN")->plainTextToken,'user'=>$user],200);


 }

 catch(\Throwable $th){
   return response()->json(['status'=>false,'message'=>$th->getMessage()],500);
 }

}
 public function index(){
  
    try{
  
        $users = User::paginate(10);
       return response()->json([$users]);
    
    }

    catch(\Exception $e){
        return response()->json(['message'=>'Failed to Get Users. Please try again later.']);
    }
 }

 public function getUserById($id){
    try{
    $user = User::find($id);
    
    return response()->json([$user]);
 }

 catch(\Exception $e){

    return response()->json(['mssage'=>'Failed to Get Users. Please try again later.']);
 
}
 
}

public function updateUser(Request $request,int $id){
   try{
         $user = User::find($id);
        
        if(!$user){
            return response()->json(['message'=>'The User is not Exist']);
        }
        $user->update($request->all());

        return response()->json([$user],201);

    }
    catch(\Throwable $th){
        return response()->json(['message'=>$th->getMessage()]);
    }

}

public function deleteUser(int $id){
    try{
    $userExist = User::find($id);
    
    if(!$userExist){
    
        return response()->json(['message'=>'The User is Not Exist']);
    } 
   
    $userExist->delete();

    response()->json(['message'=>'The User Delete Successfully']);
}
catch(\Throwable $th){
  
    return response()->json(['message'=>$th->getMessage()]);
}
}
}

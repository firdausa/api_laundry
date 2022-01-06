<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    
    public function insert(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id_outlet' => 'required|string|max:20',
			'nama' => 'required|string|max:255',
			'username' => 'required|string|max:50|unique:Users',
			'password' => 'required|string|min:6',
		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
		}

		$user = new User();
		$user->id_outlet= $request->id_outlet;
		$user->nama 	= $request->nama;
		$user->username = $request->username;
		$user->role 	= $request->role;
		$user->password = Hash::make($request->password);
		$user->save();

        $data = User::where('username','=', $request->username)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil `menambahkan user baru!.',
            'data' => $data
        ]);
    }

    public function login(Request $request){
		$credentials = $request->only('username', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid username and password!.',
                ]);
			}
		} catch(JWTException $e){
            return response()->json([
                'success' => false,
                'message' => 'Generate token failed!.',
            ]);
		}

        $data = [
			'token' => $token,
			'user'  => JWTAuth::user()
		];

		return response()->json([
            'success' => true,
            'message' => 'Authentication success',
            'data' => $data
        ]);
        
	}

    public function loginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return $this->response->errorResponse('Invalid token!');
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return response()->json([
                'success' => false,
                'message' => 'Token Expired.',
            ]);
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return response()->json([
                'success' => false,
                'message' => 'Token invalid.',
            ]);
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return response()->json([
                'success' => false,
                'message' => 'Authorization token not found.',
            ]);
		}

        return response()->json([
            'success' => true,
            'message' => 'Authentication success',
            'data' => $user
        ]);

	}

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                'success' => true,
                'message' => 'You are logged out.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logged out failed.',
            ]);
        }
    }

    // public function insert(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
	// 		'nama' => 'required|string',
	// 		'alamat' => 'required|string',
	// 		'jenis_kelamin' => 'required|string',
	// 		'tlp' => 'required|numeric'
	// 	]);

	// 	if($validator->fails()){
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors(),
    //         ]);
	// 	}

	// 	$user = new User();
	// 	$user->nama = $request->nama;
	// 	$user->alamat = $request->alamat;
	// 	$user->jenis_kelamin = $request->jenis_kelamin;
	// 	$user->tlp = $request->tlp;
	// 	$user->save();

    //     $data = Member::where('id_user','=', $user->id_user)->first();
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data user berhasil ditambahkan!.',
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
	// 		'nama' => 'required|string',
	// 		'alamat' => 'required|string',
	// 		'jenis_kelamin' => 'required|string',
	// 		'tlp' => 'required|numeric'
	// 	]);

	// 	if($validator->fails()){
    //         return response()->json([
    //             'success' => false,
    //             'message' => $validator->errors(),
    //         ]);
	// 	}

	// 	$user = Member::where('id_user', $id)->first();
	// 	$user->nama = $request->nama;
	// 	$user->alamat = $request->alamat;
	// 	$user->jenis_kelamin = $request->jenis_kelamin;
	// 	$user->tlp = $request->tlp;
	// 	$user->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Data user berhasil diubah!.'
    //     ]);
    // }

    // public function delete($id)
    // {
    //     $delete = Member::where('id_user', $id)->delete();

    //     if($delete){
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data user berhasil dihapus!.'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data user gagal dihapus!.'
    //         ]);
    //     }
    // }

    // public function getAll()
    // {
    //     $data["count"] = Member::count();
    //     $data["user"] = Member::get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $data
    //     ]);
    // }

    // public function getById($id)
    // {   
    //     $data["user"] = Member::where('id_user', $id)->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $data
    //     ]);
    // }


}

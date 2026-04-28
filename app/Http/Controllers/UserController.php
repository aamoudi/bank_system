<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check user guard first, then admin guard
        $authUser = auth('user')->user();
        $authAdmin = auth('admin')->user();

        if ($authUser && $authUser->hasRole('User')) {
            // Logged in as a regular user — show only their children
            $users = User::with(['city', 'profession'])
                ->where('parent', $authUser->id)
                ->paginate(10);
        } else {
            // Logged in as admin — show all users
            $users = User::with(['city', 'profession'])->paginate(10);
        }

        return response()->view('cms.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cities = City::where('active', true)->get();
        $professions = Profession::where('active', true)->get();
        return response()->view('cms.users.create', ['cities' => $cities, 'professions' => $professions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUser = auth('user')->user();
        $authAdmin = auth('admin')->user();

        $validator = Validator($request->all(), [
            'city_id'       => 'required|integer|exists:cities,id',
            'profession_id' => 'nullable|integer|exists:professions,id',
            'first_name'    => 'required|string|min:2|max:30',
            'last_name'     => 'required|string|min:2|max:30',
            'email'         => 'nullable|email|unique:users,email',
            'mobile'        => 'nullable|numeric|digits:10|unique:users,mobile',
            'id_number'     => 'required|numeric|digits:9',
            'gender'        => 'required|in:M,F|string',
        ]);

        $success = 'User created successfully';
        $failed  = 'Failed to create user!';

        if (!$validator->fails()) {
            $user = new User();
            $user->first_name    = $request->get('first_name');
            $user->last_name     = $request->get('last_name');
            $user->email         = $request->get('email');
            $user->mobile        = $request->get('mobile');
            $user->id_number     = $request->get('id_number');
            $user->city_id       = $request->get('city_id');
            $user->profession_id = $request->get('profession_id');
            $user->password      = Hash::make('123123');
            $user->gender        = $request->get('gender');

            // If logged in as regular user, assign as parent
            if ($authUser && $authUser->hasRole('User')) {
                $user->parent = $authUser->id;
                $success = 'Child created successfully';
                $failed  = 'Failed to create child!';
            }

            $isSaved = $user->save();

            if ($isSaved) {
                $role = Role::findByName('User', 'user');
                if ($role) {
                    $user->assignRole($role);
                }
            }

            return response()->json([
                'message' => $isSaved ? $success : $failed
            ], $isSaved ? 201 : 400);
        } else {
            return response()->json([
                'message' => $validator->getMessageBag()->first()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        $cities = City::where('active', true)->get();
        $professions = Profession::where('active', true)->get();
        return response()->view('cms.users.edit', ['user' => $user, 'cities' => $cities, 'professions' => $professions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator($request->all(), [
            'city_id' => 'required|integer|exists:cities,id',
            'profession_id' => 'nullable|integer|exists:professions,id',
            'first_name' => 'required|string|min:2|max:30',
            'last_name' => 'required|string|min:2|max:30',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'mobile' => 'nullable|numeric|digits:10|unique:users,mobile,' . $id,
            'id_number' => 'required|numeric|digits:9',
            'gender' => 'required|in:M,F|string',
        ]);

        if (!$validator->fails()) {
            $user = User::findOrFail($id);
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->email = $request->get('email');
            $user->mobile = $request->get('mobile');
            $user->id_number = $request->get('id_number');
            $user->city_id = $request->get('city_id');
            $user->profession_id = $request->get('profession_id');
            $user->gender = $request->get('gender');
            $isSaved = $user->save();

            return response()->json(['message' => $isSaved ?  'User updated successfully' : 'Failed to update user!'], $isSaved ? 200 : 400);
        } else {
            return response()->json(['message' => $validator->getMessageBag()->first()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $isDeleted = User::destroy($id);
        if ($isDeleted) {
            // return redirect()->back();
            return response()->json(['title' => 'Deleted!', 'message' => 'User Deleted Successfully', 'icon' => 'success'], 200);
        } else {
            return response()->json(['title' => 'Failed!', 'message' => 'Delete user failed', 'icon' => 'error'], 400);
        }
    }
}

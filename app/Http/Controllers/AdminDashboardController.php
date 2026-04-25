<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\City;
use App\Models\Currency;
use App\Models\Debit;
use App\Models\Profession;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Admin-level counts (always available)
        $admins      = Admin::count();
        $users       = User::count();
        $cities      = City::count();
        $currencies  = Currency::count();
        $professions = Profession::count();

        // User-level counts (only when a user is logged in)
        $authUser = auth('user')->user();
        $childs  = $authUser ? User::where('parent', $authUser->id)->count() : 0;
        $wallets = $authUser ? Wallet::where('user_id', $authUser->id)->count() : 0;
        $debts   = $authUser ? Debit::where('user_id', $authUser->id)->count() : 0;

        return response()->view('cms.dashboard', [
            'admins'      => $admins,
            'users'       => $users,
            'cities'      => $cities,
            'currencies'  => $currencies,
            'professions' => $professions,
            'childs'      => $childs,
            'wallets'     => $wallets,
            'debts'       => $debts,
        ]);
    }
}

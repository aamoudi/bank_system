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
        //
        /**
         *Num OF Rows = 100;
         * Pagination = 10=> 100/10 = 10 Pages
         */
        // $admins = Admin::paginate(10);
        //admin
        $admins = Admin::count();
        $users = User::count();
        $cities = City::count();
        $currencies = Currency::count();
        $professions = Profession::count();

        //user
        $childs = user::where('parent', Auth::user()->id)->count();
        $wallets = Wallet::where('user_id', Auth::user()->id)->count();
        $debts = Debit::where('user_id', Auth::user()->id)->count();
        //$admin = Admin::find(1);
        // $admin->hasPermission();
        return response()->view('cms.dashboard', 
            ['admins' => $admins , 'users' => $users , 'cities' => $cities ,
            'currencies' => $currencies , 'professions' , $professions,
            'childs' => $childs , 'wallets' => $wallets,
            'debts' => $debts]);
    }
}

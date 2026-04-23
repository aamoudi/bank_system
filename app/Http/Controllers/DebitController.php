<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Debit;
use App\Models\User;
use Illuminate\Http\Request;

class DebitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $debits = Debit::where('main_user_id', '=', request()->user('user')->id)
        ->with('currency')->paginate(10);
        return response()->view('cms.debits.index', ['debits' => $debits]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $users = User::where('id', '!=', request()->user('user')->id)->get();
        $currencies = Currency::where('active', true)->get();
        return response()->view('cms.debits.create', ['users' => $users, 'currencies' => $currencies]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator($request->all(), [
            'title' => 'required|string|min:5|max:20',
            'total' => 'required|integer|min:0',
            'remain' => 'required', 
            'type' => 'required',
            'payment_type' => 'required', 
            'date' => 'required',
            'user_id' => 'required', 
            //'currecny_id' => 'required'

        ]);

        if (!$validator->fails()) {
            $debit = new Debit();
            $debit->title = $request->get('title');
            $debit->currecny_id  = $request->get('currency_id');
            $debit->total = $request->get('total');
            $debit->user_id = $request->get('user_id');
            $debit->main_user_id = $request->user('user')->id;
            $debit->remain = $request->get('remain');
            $debit->type = $request->get('type');
            $debit->payment_type = $request->get('payment_type');
            $debit->date = $request->get('date');
            $isSaved = $debit->save();
            return response()->json(['message' => $isSaved ? 'Debit created successfully' : 'Failed to create debit!'], $isSaved ? 201 : 400);
        } else {
            return response()->json(['message' => $validator->getMessageBag()->first()], 400);
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
        $debit = Debit::findOrFail($id);
        $users = User::where('id', '!=', request()->user('user')->id)->get();
        $currencies = Currency::where('active', true)->get();
        return response()->view('cms.debits.edit', ['debit' => $debit , 'users' => $users, 'currencies' => $currencies]);
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
            'title' => 'required|string|min:5|max:20',
            'total' => 'required|integer|min:0',
            'remain' => 'required', 
            'type' => 'required',
            'payment_type' => 'required', 
            'date' => 'required',
            'user_id' => 'required', 
            //'currecny_id' => 'required'

        ]);

        if (!$validator->fails()) {
            $debit = Debit::findOrFail($id);;
            $debit->title = $request->get('title');
            $debit->currecny_id  = $request->get('currency_id');
            $debit->total = $request->get('total');
            $debit->user_id = $request->get('user_id');
            $debit->main_user_id = $request->user('user')->id;
            $debit->remain = $request->get('remain');
            $debit->type = $request->get('type');
            $debit->payment_type = $request->get('payment_type');
            $debit->date = $request->get('date');
            $isSaved = $debit->save();
            return response()->json(['message' => $isSaved ? 'Debit updated successfully' : 'Failed to update debit!'], $isSaved ? 201 : 400);
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
        $isDeleted = Debit::destroy($id);
        if ($isDeleted) {
            // return redirect()->back();
            return response()->json(['title' => 'Deleted!', 'message' => 'Debit Deleted Successfully', 'icon' => 'success'], 200);
        } else {
            return response()->json(['title' => 'Failed!', 'message' => 'Delete Debit failed', 'icon' => 'error'], 400);
        }
    }
}

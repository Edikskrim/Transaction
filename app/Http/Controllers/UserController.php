<?php

namespace App\Http\Controllers;

use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
    {
    private $users;

    public function __construct(Users $users)
        {
            $this->users = $users;
        }

    public function index()
        {
            $users = Users::All();
            return view('users.index', ['users' => $users]);
        }

    public function userMenuTransaction($id)
        {
            $users = $this->users->all()->except($id);
            $user = $this->users->find($id);
            return view('users.transaction', [
                'user' => $user,
                'allUsers' => $users
            ]);
        }

    public function flashMessage($request,$message,$class)
        {
            $request->session()->flash('status', $message);
            $request->session()->flash('status-class', $class);
        }

    public function update(Request $request, $id)
        {
            $userTransactionId = $request['user_transaction_id'];
            $userMoney = $this->users->find($id)->money;
            if ($this->users->find($userTransactionId) === false)
                {
                    $message = 'Неизвезтный пользователь';
                    $this->flashMessage($request,$message,'alert-danger');
                    Log::error($message, [
                        'user_id' => $id,
                        'user_balance' => $userMoney
                    ]);
                }
            if ($request['money'] <= 0)
                {
                    $message = 'Не корректная сумма';
                    $this->flashMessage($request,$message,'alert-danger');
                    Log::error($message, [
                        'user_id' => $id,
                        'user_balance' => $userMoney
                    ]);
                }
            if ($request['money'] > $userMoney)
                {
                    $message = 'Вам не хватает средств для перевода';
                    $this->flashMessage($request,$message,'alert-danger');
                    Log::error($message, [
                        'user_id' => $id,
                        'user_balance' => $userMoney
                    ]);
                }
            else
                {
                    try
                        {

                            DB::beginTransaction();
                            $userMoney = $userMoney - $request['money'];
                            $userTransactionMoney = $this->users->find($userTransactionId)->money;
                            $userTransactionMoney += $request['money'];
                            $this->users->where('id', $id)->update([
                                'money' => $userMoney,
                            ]);
                            $this->users->where('id', $userTransactionId)->update([
                                'money' => $userTransactionMoney,
                            ]);
                            DB::commit();

                            $message = 'Операция выполнена';
                            $this->flashMessage($request,$message,'alert-success');
                            Log::info($message, [
                                'user_id' => $id,
                                'user_balance' => $userMoney,
                                'transaction' => $request['money'],
                                'user_transaction_id' => $userTransactionId
                            ]);
                        }
                    catch (\Exception $e)
                        {
                            DB::rollback();
                            $this->flashMessage($request,$e->getMessage(),'alert-danger');
                        }
                }
            return redirect()->route('user.transaction', $id);
        }
    }

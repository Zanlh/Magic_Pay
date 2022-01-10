<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\UUIDGenerate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferFormValidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function home(){
        $user = Auth::guard('web')->user();
        return view('frontend.home', compact('user'));
    }

    public function profile(){
        $user = Auth::guard('web')->user();
        return view('frontend.profile', compact('user'));
    }

    public function updatePassword(){
        return view('frontend.update_password');
    }

    public function updatePasswordStore(UpdatePassword $request){

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $user =Auth::guard('web')->user();

        if (Hash::check($old_password, $user->password)){
            $user->password = Hash::make($new_password);
            $user->update();

            return redirect()->route('profile')->with('update','Successfully Updated.');
        }

        return back()->withErrors(['old_password'=>'The old password is not correct!'])->withInput();
    }

    public function wallet(){
        $authUser = auth()->guard('web')->user();
        return view('frontend.wallet',compact('authUser'));
    }

    public function transfer(){
        $user = auth()->guard('web')->user();
        return view('frontend.transfer',compact('user'));
    }

    public function transferConfirm(TransferFormValidate $request){
        $user = auth()->guard('web')->user();

        if($request ->amount<1000){
            return back()->withErrors(['amount' =>'The amount must be at least 1000 MMK'])->withInput();
        }

        if($request->amount > $user->wallet->amount){
            return back()->withErrors(['amount' =>'Insufficient Balance. Please check your wallet.'])->withInput();
        }

        if($user->phone == $request->to_phone){
            return back()->withErrors(['to_phone'=>'To account is invalid'])->withInput();
        }

        $to_account= User::where('phone', $request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To account is invalid'])->withInput();
        }

        $from_account = $user;
        $amount = $request->amount; 
        $description = $request->description; 
        return view('frontend.transfer_confirm',compact('from_account','to_account','amount','description'));
    }

    public function transferComplete(TransferFormValidate $request){
        // return $request->all();
        if($request ->amount<1000){
            return back()->withErrors(['amount' =>'The amount must be at least 1000 MMK'])->withInput();
        }

        $from_account = auth()->guard('web')->user();

        if($from_account->phone == $request->to_phone){
            return back()->withErrors(['to_phone'=>'To account is invalid'])->withInput();
        }

        $to_account= User::where('phone', $request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To account is invalid'])->withInput();
        }
        $amount = $request->amount; 
        $description = $request->description; 

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail'=>'Something went wrong! The given data is invalid.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount',$amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount',$amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id ;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount ;
            $from_account_transaction->source_id = $to_account->id ;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id ;
            $to_account_transaction->type =1 ;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id =$from_account->id ;
            $to_account_transaction->description =$description ;
            $to_account_transaction->save();

            

            DB::commit();
            return redirect('/')->with('transfer-success', 'Successfully transferred.');

        } catch (\Exception $error) {
            DB::rollBack();
            return back()->withErrors(['fail' =>'Something Wrong' . $error->getMessage()])->withInput();
        }
    }

    public function toAccountVerify(Request $request){

        $authUser = auth()->guard('web')->user();
        if($authUser->phone != $request->phone){
            $user = User::where('phone', $request->phone)->first();
            if($user){
                return response()->json([
                    'status' => 'success',
                    'message'=>'success',
                    'data' => $user
                ]);
            }
        }
        return response()->json([
            'status' => 'fail',
            'message' =>'Invalid data'

        ]);

    }

    public function passwordCheck(Request $request){

        if(!$request->password){
            return response()->json([
                'status' => 'fail',
                'message' =>'The Password is Empty!',
            ]);
        }
        $authUser = auth()->guard('web')->user();
        if (Hash::check($request->password, $authUser->password)) {
            return response()->json([
                'status' => 'success',
                'message'=>'The Password is Correct',
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' =>'The Password is Incorrect!',
        ]);
    }
}

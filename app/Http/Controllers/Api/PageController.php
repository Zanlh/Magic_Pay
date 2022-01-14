<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Requests\TransferFormValidate;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TransactionDetailResource;

class PageController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $data = new ProfileResource($user);
        return success('success', $data);
    }

    public function transaction(Request $request)
    {
        $authUser = auth()->user();
        $transactions = Transaction::with('user', 'source')
            ->orderBy('created_at', 'DESC')
            ->where('user_id', $authUser->id);

        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        $transactions = $transactions->paginate(5);
        $data = TransactionResource::collection($transactions)->additional(['result' => 1, 'message' => 'succcess']);
        return $data;
    }

    public function transactionDetail($trx_id)
    {
        $authUser = auth()->user();
        $transactions = Transaction::with('user', 'source')
            ->where('user_id', $authUser->id)
            ->where('trx_id', $trx_id)
            ->firstOrFail();
        $data = new TransactionDetailResource($transactions);
        return success('Success', $data);
    }

    public function toAccountVerify(Request $request)
    {

        if ($request->phone) {
            $authUser = auth()->user();
            if ($authUser->phone !=  $request->phone) {
                $user = User::where('phone', $request->phone)->first();
                if ($user) {
                    return success('success', ['name' => $user->name, 'phone' => $user->phone]);
                }
            }
        }
        return fail('Invalid Data.', null);
    }

    public function transferConfirm(TransferFormValidate $request)
    {
        $user = auth()->user();

        $from_account = $user;
        $amount =  $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;

        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');

        if ($request->hash_value !== $hash_value2) {
            return fail('The given data is invalid.', null);
        }

        if ($request->amount < 1000) {
            return fail('The amount must be at least 1000 MMK.', null);
        }

        if ($request->amount > $user->wallet->amount) {
            return fail('Insufficient balance. Please check your wallet.', null);
        }

        if ($user->phone == $request->to_phone) {
            return fail('The account is invalid.', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return fail('The account is invalid.', null);
        }

        return success('success', [
            'from_account_name' => $from_account->name,
            'from_account_phone' => $from_account->phone,

            'to_account_name' => $to_account->name,
            'to_account_phone' => $to_account->phone,

            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value,

        ]);
    }

    public function transferComplete(TransferFormValidate $request)
    {
        // Password Check
        if (!$request->password) {
            return fail('Please fill your passwords', null);
        }
        $authUser = auth()->user();
        if (!Hash::check($request->password, $authUser->password)) {
            return fail('Password incorrect.', null);
        }





        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        $from_account = auth()->user();

        if ($request->hash_value !== $hash_value2) {
            return fail('The given data is invalid.', null);
        }

        if ($request->amount < 1000) {
            return fail('The amount must be at least 1000 MMK.', null);
        }
        if ($request->amount > $from_account->wallet->amount) {
            return fail('Insufficient balance. Please check your wallet.', null);
        }

        if ($from_account->phone == $request->to_phone) {
            return fail('The account is invalid.', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return fail('The account is invalid.', null);
        }
        $amount = $request->amount;
        $description = $request->description;

        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('The account is invalid.', null);
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            //From noti
            $title = 'E-Money Transferred!';
            $message = 'Your e-money transferred ' . $amount . ' MMK to ' . $to_account->name . '( ' . $to_account->phone . ' )';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $from_account_transaction->trx_id);

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));

            //To Noti
            $title = 'E-Money Received!';
            $message = 'Your e-money received ' . $amount . ' MMK from ' . $from_account->name . '( ' . $from_account->phone . ' )';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));


            DB::commit();
            return success('Successfully transferred.', ['trx_id' => $from_account_transaction->trx_id,]);
        } catch (\Exception $error) {
            DB::rollBack();
            return fail('Something Wrong' . $error->getMessage(), null);
        }
    }

    public function scanAndPayForm(Request $request)
    {
        //return $request->all();
        $from_account = auth()->user();
        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return fail('QR cose is invalid.', null);
        }

        return success('success', [
            'from_name'=>$from_account->name,
            'from_phone'=>$from_account->phone,
            'to_name'=>$to_account->name,
            'to_phone'=>$to_account->phone,
        ]);
    }
    public function scanAndPayConfirm(TransferFormValidate $request)
    {
        $user = auth()->user();

        $from_account = $user;
        $amount =  $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;

        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');

        if ($request->hash_value !== $hash_value2) {
            return fail('The given data is invalid.', null);
        }

        if ($request->amount < 1000) {
            return fail('The amount must be at least 1000 MMK.', null);
        }

        if ($request->amount > $user->wallet->amount) {
            return fail('Insufficient balance. Please check your wallet.', null);
        }

        if ($user->phone == $request->to_phone) {
            return fail('The account is invalid.', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return fail('The account is invalid.', null);

        }
        return success('success', [
            'from_account_name' => $from_account->name,
            'from_account_phone' => $from_account->phone,

            'to_account_name' => $to_account->name,
            'to_account_phone' => $to_account->phone,

            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value,

        ]);
}

    public function scanAndPayComplete(TransferFormValidate $request)
    {
        // Password Check
        if (!$request->password) {
            return fail('Please fill your passwords', null);
        }
        $authUser = auth()->user();
        if (!Hash::check($request->password, $authUser->password)) {
            return fail('Password incorrect.', null);
        }





        $str = $request->to_phone . $request->amount . $request->description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        $from_account = auth()->user();

        if ($request->hash_value !== $hash_value2) {
            return fail('The given data is invalid.', null);
        }

        if ($request->amount < 1000) {
            return fail('The amount must be at least 1000 MMK.', null);
        }
        if ($request->amount > $from_account->wallet->amount) {
            return fail('Insufficient balance. Please check your wallet.', null);
        }

        if ($from_account->phone == $request->to_phone) {
            return fail('The account is invalid.', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();
        if (!$to_account) {
            return fail('The account is invalid.', null);
        }
        $amount = $request->amount;
        $description = $request->description;

        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('The account is invalid.', null);
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();

            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            //From noti
            $title = 'E-Money Transferred!';
            $message = 'Your e-money transferred ' . $amount . ' MMK to ' . $to_account->name . '( ' . $to_account->phone . ' )';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $from_account_transaction->trx_id);

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));

            //To Noti
            $title = 'E-Money Received!';
            $message = 'Your e-money received ' . $amount . ' MMK from ' . $from_account->name . '( ' . $from_account->phone . ' )';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link));


            DB::commit();
            return success('Successfully transferred.', ['trx_id' => $from_account_transaction->trx_id,]);
        } catch (\Exception $error) {
            DB::rollBack();
            return fail('Something Wrong' . $error->getMessage(), null);
        }
    }
}

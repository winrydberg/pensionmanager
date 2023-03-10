<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchemeAdminController extends Controller
{
    public function enterChequeNo(Request $request){
        try{
            $customer = Customer::where('id', $request->cus_id)->where('claim_id', $request->claim_id)->first();
            if($customer){
                $customer->update([
                    'name_on_cheque' => $request->name_on_cheque,
                    'cheque_number' => $request->chequeno,
                    'payment_status' => true
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cheque No successfully updated'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Customer/Staff data not found. Please refresh page and try again'
                ]);
            }
        }catch(Exception $e){
            Log::error('CHEQUE_NO_ENTRY => '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'SYSTEM_ERROR: Unable to add cheque no. Please try again later'
            ]);
        }
    }



    public function transferTobank(Request $request){
        try{
            $claim = Customer::find();
            if($claim){
                $claim->update([
                    'state' => 'Transfered To Bank'
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Payment status successfully updated'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Claim data not found. Please refresh page and try again'
                ]);
            }
        } catch(Exception $e){
            Log::error('TRANSFER TO BANK ERROR => '.$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'SYSTEM_ERROR: Unable to add cheque no. Please try again later'
            ]);
        }
    }
}
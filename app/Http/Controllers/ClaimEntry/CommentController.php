<?php

namespace App\Http\Controllers\ClaimEntry;

use App\Events\NewPushNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function updateComment(Request $request) {
        try{
            $claim = Claim::find($request->claimid);
            if($claim){
                $claim->update([
                    'comment' => $request->comment
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Comment updated successfully'
                ]);
            }else{
                return response()->json([
                'status' => 'error',
                'message' => 'Unable to update comment now. Claim NOT Found'
            ]);
            }
        }catch(Exception $e){
            Log::error("EDIT_COMMENT_ERROR => ".$e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'SERVICE ERROR: Unable to update comment now.'
            ]);
        }
    }


    public function updateClaimValidityStatus(Request $request){
        try{
            $claim = Claim::find($request->claimid);
            if ($claim) {
                if($request->valid ==0){
                    $claim->update([
                        'active' => $request->valid,
                        'comment' => $request->comment,
                        'invalid_reason' => $request->comment
                    ]);

                    //send push notification to users
                    event(new NewPushNotificationEvent("Invalid Claim", "The claim with Claim ID: " . $claim->claimid." has been marked as invalid", $claim->id));

                    $company = Company::select('id', 'name')->where('id', $claim->company_id)->first();
                    activity()
                        ->causedBy(Auth::user())
                        ->performedOn($claim)
                        ->withProperties(['company' => $company])
                        ->log(config('enums.CLAIM_SET_TO_INVALID'), config('enums.CLAIM_SET_TO_INVALID'));
                }else{
                    $claim->update([
                        'active' => $request->valid,
                    ]);
                    $company = Company::select('id', 'name')->where('id', $claim->company_id)->first();
                    activity()
                        ->causedBy(Auth::user())
                        ->performedOn($claim)
                        ->withProperties(['company' => $company])
                        ->log(config('enums.CLAIM_VALIDATED'), config('enums.CLAIM_VALIDATED'));
                }
                return response()->json([
                    'status' => 'success',
                    'message' => $request->valid == 1 ? 'Claim successfully validated': 'Claim has be successfully set to invalid'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Claim'
                ]);
            }
        }catch (Exception $e) {
            Log::error("CLAIM_VALIDITY_ERROR => " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'SERVICE ERROR: Unable to update comment now.'
            ]);
        }
    }
}
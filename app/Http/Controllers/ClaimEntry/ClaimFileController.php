<?php

namespace App\Http\Controllers\ClaimEntry;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\ClaimFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClaimFileController extends Controller
{
    

    public function deleteClaimFile(Request $request){
        $claimFile = ClaimFile::find($request->id);
        if($claimFile){

            $claim = Claim::find($claimFile->claim_id);
            // if(in_array(strtolower($claimFile->extension), ['xlsx', 'xls']))
            // {
            //     $claim->processed = false;
            //     $claim->save();
            // }

           
            $company = $claim->company;
            $user = Auth::user();
            activity()
                ->causedBy($user)
                ->performedOn($claim)
                ->withProperties(['company' => $company])
                ->log(config('enums.CLAIM_FILE_DELETED'), config('enums.CLAIM_FILE_DELETED'));
               
                
            if($claimFile->is_processed_file){
                $claim->update([
                    'processed' => false
                ]);
            }
            $claimFile->delete();

            Storage::disk('local')->delete($claimFile->filename);

            return response()->json([
                'status' => 'success',
                'message' => 'Claim file has been deleted.'
            ]);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Ooops, Unable to delete file. File record not found.'
            ]);
        }
    }
}
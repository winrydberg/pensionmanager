<?php

namespace App\Listeners;

use App\Events\ClaimDeleted;
use App\Models\ClaimFile;
use App\Models\Customer;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClaimDeleteListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ClaimDeleted  $event
     * @return void
     */
    public function handle(ClaimDeleted $event)
    {
        $claim = $event->claim;

        // $files = Storage::allFiles($claim->claim_directory);

        try{
             $claimfilesIds = ClaimFile::where('claim_id', $claim->id)->pluck('id');
             ClaimFile::destroy($claimfilesIds);
             $customersIds = Customer::where('claim_id', $claim->id)->pluck('id');
             Customer::destroy($customersIds); 
            //  File::deleteDirectory(storage_path('app/'.$claim->claim_directory));
             $claim->delete();
        }catch(Exception $e){
            Log::error($e);
        }
       
        // Log::info($files);

    }
}
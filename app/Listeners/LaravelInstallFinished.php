<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;

class LaravelInstallFinished
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //Artisan::call('storage:link');

        
        $response = 'success';

        if ($response == success) {
            //remove the installed file
            unlink(storage_path('installed'));
            //undo the migrations
            Artisan::call('migrate:rollback');
            //redirect to the purchase page
            return redirect()->route('install.purchase')->with('error', $response['message']);

        }

    }
}

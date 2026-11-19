<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DatabaseController;
use App\Models\Application;
use App\Services\UserRightService;
use Illuminate\Http\Request;

class RouteScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routeschedule:custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run sync base on custom schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      
            $request = Request::create('/sync_auto', 'POST');
            $response = app()->handle($request);
            
            $this->info($request);   
        //  return 'this';
    }
}

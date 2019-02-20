<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AgentMonitorCheck;
use App\Agent;

class AgentRunner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:run {--frequency=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs Agent Jobs';

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
     * @return mixed
     */
    public function handle()
    {
        $freq = (int) $this->option('frequency');

        $agents = Agent::whereFrequency($freq)->get();

        foreach($agents as $agent) {
            AgentMonitorCheck::dispatchNow($agent);
        }
    }
}

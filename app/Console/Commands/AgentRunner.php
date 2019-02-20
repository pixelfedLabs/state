<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $freq = $this->option('frequency');
    }
}

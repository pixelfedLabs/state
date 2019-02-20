<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;
use App\{
    Agent,
    AgentCheck
};
use \Zttp\Zttp;

class AgentMonitorCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $agent;
    protected $response;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->performChecks();
    }
    
    protected function clientHeaders()
    {
        return [
            'User-Agent' => 'PixelfedStateBot - https://github.com/dansup/state'
        ];
    }

    protected function performChecks()
    {
        $agent = $this->agent;
        $url = $agent->check_url;
        $this->response = Zttp::withHeaders($this->clientHeaders())->get($url);
        $this->handleResponseCode();
    }

    protected function handleResponseCode()
    {
        switch ($this->response->status()) {
            case 200:
                $this->verifyCheckText();
                break;
            
            default:
                $this->markUnavailable();
                break;
        }
    }

    protected function verifyCheckText()
    {
        $response = $this->response;
        $agent = $this->agent;

        if($agent->check_text) {
            Str::contains((string) $response, $agent->check_text) ?
            $this->markAvailable() :
            $this->markUnavailable();

        } else {
            $this->markAvailable();
        }
    }

    protected function markUnavailable()
    {
        $response = $this->response;
        $agent = $this->agent;

        $check = new AgentCheck;
        $check->agent_id = $agent->id;
        $check->response_code = $response->status();
        $check->headers = json_encode($response->headers());
        $check->online = false;
        $check->save();
    }

    protected function markAvailable()
    {
        $response = $this->response;
        $agent = $this->agent;

        $check = new AgentCheck;
        $check->agent_id = $agent->id;
        $check->response_code = $response->status();
        $check->headers = json_encode($response->headers());
        $check->online = true;
        $check->save(); 
    }
}

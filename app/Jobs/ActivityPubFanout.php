<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class ActivityPubFanout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $actor;
    protected $incident;
    protected $guzzleConcurrency = 10;
    protected $guzzleTimeout = 5;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Actor $actor, $incident)
    {
        $this->actor = $actor;
        $this->incident = $incident;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return;
        $payload = $this->buildActivity();
        
        $client = new Client([
            'timeout'  => $this->guzzleTimeout
        ]);

        $requests = function($audience) use ($client, $activity, $profile, $payload) {
            foreach($audience as $url) {
                $headers = $this->signHeaders($profile, $url, $activity);
                yield function() use ($client, $url, $headers, $payload) {
                    return $client->postAsync($url, [
                        'curl' => [
                            CURLOPT_HTTPHEADER => $headers, 
                            CURLOPT_POSTFIELDS => $payload,
                            CURLOPT_HEADER => true
                        ]
                    ]);
                };
            }
        };

        $pool = new Pool($client, $requests($audience), [
            'concurrency' => $this->guzzleConcurrency,
            'fulfilled' => function ($response, $index) {
            },
            'rejected' => function ($reason, $index) {
            }
        ]);
        
        $promise = $pool->promise();

        $promise->wait();
    }

    protected function buildActivity()
    {
        $activity = [];
        return json_encode($activity);
    }

    protected function signHeaders($profile, $url, $activity)
    {
        $headers = [];
        return $headers;
    }
}

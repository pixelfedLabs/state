<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use App\Agent;
use App\Follower;
use App\Util\ActivityPub\ActivityPubHelpers as AP;

class InboxWorker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $headers;
    protected $body;
    protected $agent;
    protected $actor;
    protected $signature;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agent, $headers, $body, $signature)
    {
        $this->agent = $agent;
        $this->headers = $headers;
        $this->body = $body;
        $this->signature = $signature;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->validateVerb();
    }

    protected function validateVerb()
    {
        $body = json_decode($this->body, true, 8);
        if($body['type'] == 'Follow') {
            $this->verifySignature();
        } else {
            Log::info('Invalid AP Verb.');
            exit;
        }
    }

    protected function verifySignature()
    {
        $body = json_decode($this->body, true, 8);
        $url = AP::validateUrl($body['actor']);
        $actor = AP::fetchFromUrl($url);
        if(!$actor) {
            Log::info('Invalid Actor');
            exit;
        }
        $publicKey = openssl_pkey_get_public($actor['publicKey']['publicKeyPem']);
        $signatureData = AP::parseSignatureHeader($this->signature);
        $inputHeaders = $this->headers;
        $inboxPath = "/account/{$this->agent->username}/inbox";
        list($verified, $headers) = AP::verify($publicKey, $signatureData, $inputHeaders, $inboxPath, $this->body);
        if($verified == 1) {
            $this->actor = $actor;
            $this->handleVerb();
        } else {
            Log::info('Invalid Signature');
            exit;
        }
    }

    protected function handleVerb()
    {
        $agent = $this->agent;
        $actor = $this->actor;
        $exists = Follower::whereActorId($agent->id)
            ->whereProfileUrl($actor['url'])
            ->exists();
        if($exists == false) {
            $this->createNewFollower();
        } else {
            exit;
        }
    }

    protected function createNewFollower()
    {
        $agent = $this->agent;
        $actor = $this->actor;

        $follower = new Follower;
        $follower->system_id = $agent->system_id;
        $follower->actor_id = $agent->id;
        $follower->profile_url = $actor['url'];
        $follower->inbox_url = $actor['inbox'];
        $follower->shared_inbox_url = isset($actor['endpoints']) && 
            isset($actor['endpoints']['sharedInbox']) ?
            $actor['endpoints']['sharedInbox'] : null;
        $follower->public_key = $actor['publicKey']['publicKeyPem'];
        $follower->key_id = $actor['publicKey']['id'];
        $follower->save();

        $this->sendFollowAccept($follower);
    }

    protected function sendFollowAccept($follower)
    {
        $target = $this->agent;
        $actor = $follower;
        $accept = [
            '@context' => 'https://www.w3.org/ns/activitystreams',
            'id'       => $target->permalink().'#accepts/follows/' . $follower->id,
            'type'     => 'Accept',
            'actor'    => $target->permalink(),
            'object'   => [
                'id' => $actor->profile_url,
                'type'  => 'Follow',
                'actor' => $actor->profile_url,
                'object' => $target->permalink()
            ]
        ];
        $to = $actor->sharedInboxUrl ?? $actor->inboxUrl;
        $res = AP::sendSignedObject($target, $to, $accept);
        Log::info($res);
    }
}

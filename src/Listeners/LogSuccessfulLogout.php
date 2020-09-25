<?php

namespace Yadahan\AuthenticationLog\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;
use Yadahan\AuthenticationLog\AuthenticationLog;

class LogSuccessfulLogout
{
    /**
     * The request.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * Create the event listener.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if (!$event->user)
            return;

        $user = $event->user;
        $ip = $this->request->ip();

        $agent = new Agent();

        $authenticationLog = $user
            ->authentications()
            ->whereIpAddress($ip)
            ->whereRawUserAgent($agent->getUserAgent())
            ->first();

        if (!$authenticationLog) {
            $authenticationLog = new AuthenticationLog([
                'ip_address' => $ip,
                'raw_user_agent' => $agent->getUserAgent(),
                'device' => $agent->deviceType(),
                'browser' => $agent->browser(),
                'platform' => $agent->platform()
            ]);
        }

        $authenticationLog->logout_at = Carbon::now();

        $user->authentications()->save($authenticationLog);
    }
}

<?php

namespace Yadahan\AuthenticationLog\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;
use Yadahan\AuthenticationLog\AuthenticationLog;
use Yadahan\AuthenticationLog\Notifications\NewDevice;

class LogSuccessfulLogin
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
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $ip = $this->request->ip();

        $agent = new Agent();

        $known = $user
            ->authentications()
            ->whereIpAddress($ip)
            ->whereRawUserAgent($agent->getUserAgent())
            ->exists();

        $authenticationLog = new AuthenticationLog([
            'ip_address' => $ip,
            'raw_user_agent' => $agent->getUserAgent(),
            'device' => $agent->deviceType(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'login_at' => Carbon::now(),
        ]);

        $user->authentications()->save($authenticationLog);

        if (!$known && config('authentication-log.notify')) {
            $user->notify(new NewDevice($authenticationLog));
        }
    }
}

@component('mail::message')
# @lang('authentication-log::messages.hello')

{{ __('authentication-log::messages.content', ['app' => config('app.name')]) }}

> **@lang('authentication-log::messages.account'):** {{ $account->email }}<br>
> **@lang('authentication-log::messages.time'):** {{ $time->isoFormat('LLLL') }}<br>
> **@lang('authentication-log::messages.ipaddress'):** {{ $ipAddress }}<br>
> **@lang('authentication-log::messages.browser'):** {{ $browser }}

@lang('authentication-log::messages.warning')<br>

@lang('authentication-log::messages.regards')<br>{{ config('app.name') }}
@endcomponent

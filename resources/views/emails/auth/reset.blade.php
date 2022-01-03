@component('mail::message')
# Introduction
<h1>Welcome {{$model->name}} from {{config('app.name')}}</h1>
<p>{{config('app.name')}} Reset Password</p>
<p>your Reset Code:{{$model->pin_code}}</p>

@component('mail::button', ['url' => 'google.com'])
Button Reset
@endcomponent

Thanks,{{config('app.name')}}<br>
{{ config('app.name') }}
@endcomponent

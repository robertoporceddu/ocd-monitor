<h1>Hi, {!! $user->name !!}</h1>
<p>
    Your access credential are:<br />

    <strong>email</strong>: {!! $user->email !!}<br />
    <strong>password</strong>: {!! $user->password !!}<br />

    Go to <a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>
</p>
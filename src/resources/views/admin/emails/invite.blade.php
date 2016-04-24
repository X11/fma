Hey {{ $user->name }}!<br>
<br>
An account have been created for you @ <a href="{{ url('/') }}" target="_blank">FMA</a>.<br> 
<br>
Click here to reset your password!<br>
<a href="{{ $link = url('password/reset').'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>

Hello <i>{{ $member->receiver }}</i>,
<p>This is a demo email for testing purposes! Also, it's the HTML version.</p>

<p><u>Send values:</u></p>

<div>
    <p><b>Demo One:</b>&nbsp;{{ $member->message }}</p>

</div>

<p><u>Values passed by With method:</u></p>

Thank You,
<br/>
<i>{{ $member->sender }}</i>
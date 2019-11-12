Hello <i>{{ $support->receiver }}</i>,
<p>This is a demo email for SupportPerson! Also, it's the HTML version.</p>

<p><u>Send values:</u></p>

<div>
    <p><b>Demo One:</b>&nbsp;{{ $support->message }}</p>

</div>

<p><u>Values passed by With method:</u></p>

Thank You,
<br/>
<i>{{ $support->sender }}</i>
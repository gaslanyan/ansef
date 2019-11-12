Hello <i>{{ $invent->receiver }}</i>,
<p>This is a demo email for testing purposes! Also, it's the HTML version.</p>

<p><u>Send values:</u></p>

<div>
    <p><b>Demo One:</b>&nbsp;{{ $invent->message }}</p>

</div>

<p><u>Values passed by With method:</u></p>

Thank You,
<br/>
<i>{{ $invent->sender }}</i>
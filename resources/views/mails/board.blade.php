Hello <i>{{ $board->receiver }}</i>,
<p>This is a demo email for Reasearch board! Also, it's the HTML version.</p>

<p><u>Send values:</u></p>

<div>
    <p><b>Demo One:</b>&nbsp;{{ $board->message }}</p>

</div>

<p><u>Values passed by With method:</u></p>

Thank You,
<br/>
<i>{{ $board->sender }}</i>
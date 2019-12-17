<div class="box-body col-md-12">

    <div class="box-header with-border">
       <h4>  Biographical sketches </h4>
    </div>
    @foreach($reports as $report)
        <p>{{$report->id}}</p>
    @endforeach

</div>

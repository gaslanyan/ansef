<div class="box-body col-md-12">

    @if(!empty($reports) && count($reports) > 0)
    @php
        $scoretypes = $reports[0]->competition->score;
    @endphp
    <div class="box-header with-border">
       <h4>  Referee reports </h4>
    </div>
    @foreach($reports as $index => $report)
        <h5 class="row col-12">
        @if($private)
        Report from referee:&nbsp; <b>{{$report->person->first_name}} {{$report->person->last_name}}</b>
        @else
        Report from referee #{{$index+1}}
        @endif
        </h5><br/>
        <table>
            @foreach($scoretypes as $scoretype)
            <tr>
                <td width="200px">
                <b>{{$scoretype->name}}</b>
                </td>
                <td width="100px">
                @php
                    $score = \App\Models\Score::where('report_id','=',$report->id)
                                    ->where('score_type_id','=',$scoretype->id)
                                    ->first();
                @endphp
                {{$score->value}}/{{$scoretype->max}} ({{$scoretype->weight}})
                </td>
            </tr>
            @endforeach
        </table><br/>
        <div class="row col-12">
            <p><b>Public comments: </b></p>
            <p>{{$report->public_comment}}</p>
        </div>
        @if($private)
        <div class="row col-12">
            <p><b>Private comments: </b></p>
            <p>{{$report->private_comment}}</p>
        </div>
        @endif
        <hr>
    @endforeach
    @endif
</div>

@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Result of execution

                        <a href="{{action('Admin\RankingRuleController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add a ranking rule</a>
                        <a href="{{action('Admin\RankingRuleController@execute')}}"
                           class="display float-lg-right btn-primary mx-2 px-2">Execute ranking rules</a>
                        <a href="{{URL::previous()}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        @include('partials.status_bar')


                        @if(!empty($tables))
                            @foreach($tables as $index=> $t)
                                <table class="table table-responsive-md table-sm table-bordered display"
                                       style="width:20%; float: left; margin-right: 10px; background-color: yellow">
                                    <caption>Summary</caption>
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            Proposal ID
                                        </th>
                                        <th>Rank</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $step =1;

                                    @endphp

                                    @foreach($t as $i=> $table)
                                        @if(!empty($table))
                                            <tbody>
                                            <tr>
                                                <td>{{$step}}</td>
                                                <td>
                                                    {{$table->id}}
                                                </td>
                                                <td>
                                                    {{$table->rank}}

                                                </td>
                                            </tr>
                                            </tbody>
                                            @php
                                                $step ++;
                                            @endphp
                                        @endif
                                    @endforeach

                                </table>
                            @endforeach

                        @endif
                        @if(!empty($results))
                            @foreach($results as $index=> $r)

                                <table class="table table-responsive-md table-sm table-bordered display"
                                       style="width:20%; float: left; margin-right: 10px">
                                    <caption>{{$results[$index]['summary']}}</caption>
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            Proposal ID
                                        </th>
                                        <th>Value</th>
                                    </tr>
                                    </thead>
                                    @foreach($r['ids'] as $i=> $result)
                                        <tbody>
                                        <tr>
                                            <td>{{$i +1}}</td>
                                            <td>
                                                {{$result}}
                                            </td>
                                            <td>
                                                {{$r['value']}}

                                            </td>
                                        </tr>
                                        </tbody>
                                    @endforeach

                                </table>
                            @endforeach

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

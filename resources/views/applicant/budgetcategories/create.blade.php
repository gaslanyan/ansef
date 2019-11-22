@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Create Person
                        <a href="{{action('Applicant\ProposalController@activeProposal')}}"
                           class="display float-lg-right btn-primary px-2">Back</a>
                    </div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        <form method="post" action="{{ action('Applicant\BudgetCategoriesController@store') }}" class="row">
                            {{ csrf_field() }}
                            <div class="form-group col-lg-6">
                                <label for="birthplace">Competition Title:</label>
                                <span>{{$ct}}</span>
                            </div>

                            <div class="col-lg-12 ">
                                <label>Budget:</label>
                                <i class="fa fa-plus pull-right add text-blue"
                                   style="cursor: pointer"></i>

                                <div class="row institution">
                                    <div class="form-group col-lg-4">
                                        <label for="inst">Budget Categories:</label>
                                        <select id="inst" class="form-control" name="bc[]">
                                            <option value="0">Select Budget Categories</option>
                                            @if(!empty($bc))
                                                @foreach($bc as $item)
                                                    <option class="text-capitalize" value="{{$item['id']."**". $item['min']."-". $item['max']}}">{{$item['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="title">Description:</label>
                                        <input type="text" class="form-control" name="b_description[]" id="b_description">
                                    </div>
                                  <div class="form-group col-lg-4">
                                  <label for="start">Amount:(Choose Between)</label>
                                  <input type="text" class="form-control" name="amount[]" id="amount" >


                                  </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="prop_id" id="prop_id" value="{{$proposal_id}}" >
                            <div class="form-group col-lg-1">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            <div class="form-group col-lg-1">
                                <input type="reset" class="btn btn-primary" value ="Cancel">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        $(document).ready(function(){
               $('#inst').on('change',function(){
                   $('#amount').attr('placeholder',"");
                var budget = ($(this).val()).split('**');
               $('#amount').attr('placeholder',budget[1]);
            });
        });
    </script>
@endsection


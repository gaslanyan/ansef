@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Update Budget
                        <a href="{{action('Applicant\ProposalController@activeProposal')}}"
                           class="display float-lg-right btn-primary px-2">Go Back</a>
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
                        @if(!empty($bi) && count($bi)>0)
                            <form method="post" action="{{action('Applicant\BudgetCategoriesController@update', $id) }}">
                                <div class="form-group">
                                    @csrf
                                    <input name="_method" type="hidden" value="PATCH">
                                    <label for="email"><b>Current budget items:</b></label>
                                    @foreach($bi as $el)
                                    <div class="row">
                                        <div class="form-group col-lg-3">
                                            <label>Category *:</label>
                                            <select class="form-control" name="bc_list[]" id="bc_list[]">
                                                <option value="0">Select Budget Category</option>
                                                @if(!empty($bc))
                                                    @foreach($bc as $item)
                                                    <option class="text-capitalize" value="{{$item['id']}}" {{$el['budget_cat_id'] == $item['id'] ? 'selected' : ''}}>{{$item['name']}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>Description *:</label>
                                            <input type="text" class="form-control" name="description_list[]" value="{{$el['description']}}" id="description_list[]">
                                        </div>
                                        <div class="col-lg-2">
                                            <label>Amount *:</label>
                                            <input type="text" class="form-control province" name="amount_list[]" value="{{$el['amount']}}" id="amount_list[]">
                                        </div>
                                    <div class="col-lg-1">
                                        <a href="{{action('Applicant\BudgetCategoriesController@destroy', $el['id'])}}" class="btn-link col-lg-2"> <i class="fa fa-trash"></i></a>
                                        <input type="hidden" class="form-control" name="bi_list_hidden[]" value="{{$el['id']}}" id="address">
                                    </div>
                                </div>
                                    <br/>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        @endif
                    </div>
                    <hr>
                    <div class="card-body card_body">
                        <label><b>Add New Budget Item:</b></label>
                        <form method="post" action="{{ action('Applicant\BudgetCategoriesController@store') }}" class="row">
                            {{ csrf_field() }}

                            <div class="col-lg-12 ">
                                <div class="row institution">
                                    <div class="form-group col-lg-3">
                                        <label for="bcc">Category:</label>
                                        <select id="bcc" class="form-control" name="bc">
                                            <option value="0">Select Budget Category</option>
                                            @if(!empty($bc))
                                                @foreach($bc as $item)
                                                    <option class="text-capitalize" value="{{$item['id']}}">{{$item['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-7">
                                        <label for="title">Details:</label>
                                        <input type="text" class="form-control" name="b_description" id="b_description">
                                    </div>
                                  <div class="form-group col-lg-2">
                                  <label for="start">Amount ($):</label>
                                  <input type="text" class="form-control" name="amount" id="amount" >
                                  </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="prop_id" id="prop_id" value="{{$id}}" >
                            <div class="form-group col-lg-1">
                                <button type="submit" class="btn btn-primary">Add Budget Item</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
// ."**". $item['min']."-". $item['max']
        // $(document).ready(function(){
        //        $('#inst').on('change',function(){
        //            $('#amount').attr('placeholder',"");
        //         var budget = ($(this).val()).split('**');
        //        $('#amount').attr('placeholder',budget[1]);
        //     });
        // });
    </script>
@endsection


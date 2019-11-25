@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Update Budget
                        <a href="{{action('Applicant\ProposalController@activeProposal')}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body card_body">
                    <div class="card-body card_body">
                        <label><b>Add New Budget Item:</b></label>
                        <form method="post" action="{{ action('Applicant\BudgetCategoriesController@store') }}" class="row">
                            {{ csrf_field() }}

                            <div class="col-lg-12 ">
                                <div class="row institution">
                                    <div class="form-group col-lg-3">
                                        <label for="bcc">Category:</label>
                                        <select id="bcc" class="form-control budgetcategory" name="bc">
                                            <option value="0">Select Budget Category</option>
                                            @if(!empty($bc))
                                                @foreach($bc as $item)
                                                    <option class="text-capitalize" min="{{$item['min']}}" max="{{$item['max']}}" value="{{$item['id']}}" {{old('bc') == $item['id'] ? 'selected' : ''}}>{{$item['name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="vmessage" style="color:#999;"></span>
                                    <input type="hidden" class="minamount" name="minamount" id="minamount" value="{{old('minamount')}}">
                                        <input type="hidden" class="maxamount" name="maxamount" id="maxamount" value="{{old('maxamount')}}">
                                    </div>
                                    <div class="form-group col-lg-7">
                                        <label for="title">Details:</label>
                                        <input type="text" class="form-control" name="b_description" id="b_description" value="{{old('b_description')}}">
                                    </div>
                                  <div class="form-group col-lg-2">
                                  <label for="start">Amount ($):</label>
                                  <input type="text" class="form-control" name="amount" id="amount" value="{{old('amount')}}">
                                  </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="prop_id" id="prop_id" value="{{$id}}" >
                            <div class="form-group col-lg-1">
                                <button type="submit" class="btn btn-primary">Add Budget Item</button>
                            </div>
                        </form>

                    </div>
                        @include('partials.status_bar')
<hr>
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
                                            <select class="form-control budgetcategory" name="bc_list[]" id="bc_list[]">
                                                <option value="0">Select Budget Category</option>
                                                @if(!empty($bc))
                                                    @foreach($bc as $item)
                                                    <option class="text-capitalize" value="{{$item['id']}}" min="{{$item['min']}}" max="{{$item['max']}}" {{$el['budget_cat_id'] == $item['id'] ? 'selected' : ''}}>{{$item['name']}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="vmessage" style="color:#999;"></span>
                                            <input type="hidden" class="minamount" name="minamount_list[]" value="">
                                            <input type="hidden" class="maxamount" name="maxamount_list[]" value="">
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
                                <div style="font-size: 20px; color:#555;">
                                    {!! $additional_message !!}<br/>
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body card_body">

                        <div style="color:#a00;">{!! $validation_message !!}</div>

                    </div>
                                    </div>
            </div>
        </div>
    </div>
    <script>
        $(window).on('load', function() {
                $('select.budgetcategory').each(function() {
                   var max = $(this).children("option:selected").attr('max');
                   var min = $(this).children("option:selected").attr('min');
                //    alert('hello: ' + max + " ," + min);
                   if(min != undefined) $(this).siblings(".vmessage").html("Min. is <b>$" + min + "</b>; max. is <b>$" + max + '</b>');
                });
        });

        $(document).ready(function(){
                $('select.budgetcategory').on('change',function(){
                   var max = $(this).children("option:selected").attr('max');
                   var min = $(this).children("option:selected").attr('min');
                   $(this).siblings(".vmessage").html("Min. is <b>$" + min + "</b>; max. is <b>$" + max + '</b>');
                   $(this).siblings(".minamount").val(min);
                   $(this).siblings(".maxamount").val(max);
                });
        });
    </script>
@endsection


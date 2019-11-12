@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                <div class="card">
                    <div class="card-header">Add a competition
                        <br>
                        <i class="fa fa-info text-blue all"> * {{Lang::get('messages.required_all')}}</i>
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
                        <form method="post" action="{{ action('Admin\CompetitionController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="title">Competition Title *:</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{old("title")}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">Competition State *:</label>
                                <select id="type" class="form-control" name="state">
                                    <?php $enum = getEnumValues('competitions', 'state');?>
                                    <option value="0">Select state</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize" @if($item == old('state')){{'selected'}} @endif
                                                    value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="category">Categories *:</label>
                                <select multiple class="form-control cat" name="category[]" id="category">
                                    <option value="0">Select Category</option>
                                    @php
                                        $old_category = old('category');
                                    @endphp
                                    @if(!empty($categories))
                                        @foreach($categories as $item)
                                            <option class="text-capitalize" @if(isset($old_category) && in_array($item->id,$old_category)) {{'selected'}} @endif
                                                    value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                @php
                                  $old =  old('description');
                                @endphp
                                <label for="description">Competition Description *:</label>
                                <textarea rows="4" class="form-control" name="description" id="description" >@if(isset($old)){{$old}} @endif</textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                @php
                                    $old =  old('comments');
                                @endphp
                                <label for="comments">Competition Comments:</label>
                                <textarea rows="4" class="form-control" name="comments" id="comments">@if(isset($old)){{$old}} @endif</textarea>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="announcement_date">Announcement Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="announcement_date" id="announcement_date" value="{{old('announcement_date')}}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="submission_start_date">Submission Start Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="submission_start_date" id="submission_start_date" value="{{old('submission_start_date')}}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="submission_end_date">Submission End Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="submission_end_date" id="submission_end_date" value="{{old('submission_end_date')}}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="project_start_date">Project Start Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="project_start_date" id="project_start_date" value="{{old('project_start_date')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="duration">Duration *:</label>
                                <input type="number" class="form-control" name="duration"
                                       id="duration" value="{{old('duration')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="min_budget">Min Budget *:</label>
                                <input type="number" class="form-control" name="min_budget"
                                       id="min_budget" value="{{old('min_budget')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="max_budget">Max Budget *:</label>
                                <input type="number" class="form-control" name="max_budget"
                                       id="max_budget" value="{{old('max_budget')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="min_age">Min Age *:</label>
                                <input type="number" class="form-control" name="min_age"
                                       id="min_age" value="{{old('min_age')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="max_age">Max Age *:</label>
                                <input type="number" class="form-control" name="max_age"
                                       id="max_age" value="{{old('max_age')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="allow_foreign" class="label">Allow Foreign PI?
                                    <input type="checkbox" class="form-control" name="allow_foreign"
                                           id="allow_foreign" value="{{old('allow_foreign')}}">
                                    <span class="checkmark"></span>
                                </label>
                            </div>


                            <div class="form-group col-lg-6">
                                <label for="min_lavel_deg_id">Min required degree:</label>
                                <select class="form-control" name="min_lavel_deg_id" id="min_lavel_deg_id">
                                    <option value="0">Select Degree</option>
                                    @if(!empty($degrees))
                                        @foreach($degrees as $item)
                                            <option class="text-capitalize" @if(old('min_lavel_deg_id') == $item->id){{'selected'}} @endif
                                                    value="{{$item->id}}">{{$item->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="max_lavel_deg_id">Max required degree:</label>
                                <select class="form-control" name="max_lavel_deg_id" id="max_lavel_deg_id">
                                    <option value="0">Select Degree</option>
                                    @if(!empty($degrees))
                                        @foreach($degrees as $item)
                                            <option class="text-capitalize" @if(old('max_lavel_deg_id') == $item->id){{'selected'}} @endif
                                                    value="{{$item->id}}">{{$item->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-5">
                                <label for="first_report">First Report Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="first_report" id="first_report" value="{{old('first_report')}}">
                            </div>
                            <div class="form-group col-lg-5">
                                <label for="second_report">Second Report Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="second_report" id="second_report" value="{{old('second_report')}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="recommendations" class="label">Require recommendations?
                                    <input type="checkbox" class="form-control" name="recommendations"
                                           id="recommendations" value="{{old('recommendations')}}">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_charge_name">Additional Charge Name:</label>
                                <input type="text" class="form-control" name="additional_charge_name"
                                       id="additional_charge_name" value="{{old('additional_charge_name')}}">
                            </div>
                            <div class="form-group col-lg-3">
                                @php
                                    $old = old('additional_charge');
                                @endphp
                                <label for="additional_charge">Additional Charge:</label>
                                <input type="number" class="form-control" name="additional_charge" id="additional_charge" value="{{(isset($old))? $old: 0}}" min="0">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_percentage_name">Additional Percentage Name:</label>
                                <input type="text" class="form-control" name="additional_percentage_name"
                                       id="additional_percentage_name">
                            </div>
                            <div class="form-group col-lg-3">
                                @php
                                    $old = old('additional_percentage');
                                @endphp
                                <label for="additional_percentage">Additional Percentage:</label>
                                <input type="number" class="form-control" name="additional_percentage"
                                       id="additional_percentage" value="{{(isset($old))? $old: 0}}" min="0">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{ action('Admin\CompetitionController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


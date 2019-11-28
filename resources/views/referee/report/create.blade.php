@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Create Competitions</div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\CompetitionController@store') }}" class="row">
                            @csrf
                            <div class="form-group col-lg-6">
                                <label for="title">Competition Title:</label>
                                <input type="text" class="form-control" name="title" id="title">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">Competition State:</label>
                                <select id="type" class="form-control" name="state">
                                    <?php $enum = getEnumValues('competitions', 'state');?>
                                    <option value="0">Select state</option>
                                    @if(!empty($enum))
                                        @foreach($enum as $item)
                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="category0">Categories:</label>
                                <select class="form-control cat" name="category[]" id="category0">
                                    <option value="0">Select Category</option>
                                    @if(!empty($categories))
                                        @foreach($categories as $item)
                                            <option class="text-capitalize"
                                                    value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="sub_category0">Subcategories:</label>
                                <select class="form-control" name="category[]" id="sub_category0">
                                    <option value="0">Select Subcategory</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="category1">Categories:</label>
                                <select class="form-control cat" name="category[]" id="category1">
                                    <option value="0">Select Category</option>
                                    @if(!empty($categories))
                                        @foreach($categories as $item)
                                            <option class="text-capitalize"
                                                    value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="sub_category1">Subcategories:</label>
                                <select class="form-control" name="category[]" id="sub_category1">
                                    <option value="0">Select subcategory</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="description">Competition Description:</label>
                                <textarea rows="4" class="form-control" name="description" id="description"></textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="comments">Competition Comments:</label>
                                <textarea rows="4" class="form-control" name="comments" id="comments"></textarea>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="submission_start_date">Submission Start Date:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="submission_start_date" id="submission_start_date">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="submission_end_date">Submission End Date:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="submission_end_date" id="submission_end_date">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="announcement_date">Announcement Date:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="announcement_date" id="announcement_date">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="project_start_date">Project Start Date:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="project_start_date" id="project_start_date">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="duration">Duration</label>
                                <input type="number" class="form-control" name="duration"
                                       id="duration">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="min_budget">Min Budget</label>
                                <input type="number" class="form-control" name="min_budget"
                                       id="min_budget">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="max_budget">Max Budget</label>
                                <input type="number" class="form-control" name="max_budget"
                                       id="max_budget">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="min_age">Min Age</label>
                                <input type="number" class="form-control" name="min_age"
                                       id="min_age">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="max_age">Max Age</label>
                                <input type="number" class="form-control" name="max_age"
                                       id="max_age">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="allow_foreign" class="label">Allow Foreign
                                    <input type="checkbox" class="form-control" name="allow_foreign"
                                           id="allow_foreign">
                                    <span class="checkmark"></span>
                                </label>
                            </div>


                            <div class="form-group col-lg-6">
                                <label for="min_level_deg_id">Min Level Degree:</label>
                                <select class="form-control" name="min_level_deg_id" id="min_level_deg_id">
                                    <option value="0">Select Degree</option>
                                    @if(!empty($degrees))
                                        @foreach($degrees as $item)
                                            <option class="text-capitalize"
                                                    value="{{$item->id}}">{{$item->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="max_level_deg_id">Max Level Degree:</label>
                                <select class="form-control" name="max_level_deg_id" id="max_level_deg_id">
                                    <option value="0">Select Degree</option>
                                    @if(!empty($degrees))
                                        @foreach($degrees as $item)
                                            <option class="text-capitalize"
                                                    value="{{$item->id}}">{{$item->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-5">
                                <label for="first_report">First Report Date:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="first_report" id="first_report">
                            </div>
                            <div class="form-group col-lg-5">
                                <label for="second_report">Second Report Date:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="second_report" id="second_report">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="recommendations" class="label">Recommendations
                                    <input type="checkbox" class="form-control" name="recommendations"
                                           id="recommendations">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_charge_name">Additional Charge Name:</label>
                                <input type="text" class="form-control" name="additional_charge_name"
                                       id="additional_charge_name">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_charge">Additional Charge:</label>
                                <input type="text" class="form-control" name="additional_charge" id="additional_charge">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_percentage_name">Additional Percentage Name:</label>
                                <input type="text" class="form-control" name="additional_percentage_name"
                                       id="additional_percentage_name">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_percentage">Additional Percentage:</label>
                                <input type="text" class="form-control" name="additional_percentage"
                                       id="additional_percentage">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Competition</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


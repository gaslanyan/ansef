@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Edit Competition <a href = "{{ action('Admin\CompetitionController@index') }}" class="display float-lg-right btn-box-tool"> Back</a>
                        <br>
                        <i class="fas fa-question-circle text-red all"> {{Lang::get('messages.required_all')}}</i>
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
                        <form method="post"
                              action="{{ action('Admin\CompetitionController@update', $com->id) }}" class="row">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="title">Competition Title *:</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{$com->title}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="type">Competition State *:</label>
                                <select id="type" class="form-control" name="state">
                                    <?php $enum = getEnumValues('competitions', 'state');?>
                                    <option >Select state</option>
                                    <?php if(!empty($enum)):?>
                                    <?php foreach($enum as $item):?>
                                    <option class="text-capitalize"
                                            <?php if ($com->state == $item): echo "selected"; endif?>
                                            value="{{$item}}">{{$item}}</option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <?php foreach ($cats as $index => $c) : ?>
                            <div class="form-group col-lg-6">
                                <label for="category{{$index}}">Categories *:</label>
                                <select class="form-control cat" name="category[]" id="category{{$index}}">
                                    <option value="0">Select Category</option>
                                    @if(!empty($cat_s))
                                        {{$parent_id = 0}}
                                        @foreach($cat_s as $item)
                                            {{$parent_id = $index}}
                                            <option class="text-capitalize"
                                                    <?php if ($item->title == $c['parent']): echo "selected"; endif?>
                                                    value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <?php endforeach;?>
                            <div class="form-group col-lg-6">
                                <label for="description">Competition Description *:</label>
                                <textarea rows="4" class="form-control" name="description"
                                          id="description">{{$com->description}}</textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="comments">Competition Comments:</label>
                                <textarea rows="4" class="form-control"
                                          name="comments" id="comments">{{$com->comments}}</textarea>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="submission_start_date">Submission Start Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       value="{{$com->submission_start_date}}"
                                       name="submission_start_date" id="submission_start_date">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="submission_end_date">Submission End Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="submission_end_date" id="submission_end_date"
                                       value="{{$com->submission_end_date}}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="announcement_date">Announcement Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       name="announcement_date" id="announcement_date"
                                       value="{{$com->announcement_date}}">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="project_start_date">Project Start Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       value="{{$com->project_start_date}}" name="project_start_date"
                                       id="project_start_date">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="duration">Duration *</label>
                                <input type="number" class="form-control" name="duration"
                                       id="duration" value="{{$com->duration}}">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="min_budget">Min Budget *</label>
                                <input type="number" class="form-control" name="min_budget"
                                       value="{{$com->min_budget}}" id="min_budget">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="max_budget">Max Budget *</label>
                                <input type="number" class="form-control" name="max_budget"
                                       value="{{$com->max_budget}}" id="max_budget">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="min_age">Min Age *:</label>
                                <input type="number" class="form-control" name="min_age" min="5" max="120"
                                       value="{{$com->min_age}}" id="min_age">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="max_age">Max Age *:</label>
                                <input type="number" class="form-control" name="max_age" min="5" max="120"
                                       value="{{$com->max_age}}" id="max_age">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="allow_foreign" class="label">Allow Foreign
                                    <input type="checkbox" class="form-control" name="allow_foreign"
                                           <?php if ($com->allow_foreign == 1) echo "checked"?>  id="allow_foreign">
                                    <span class="checkmark"></span>
                                </label>
                            </div>


                            <div class="form-group col-lg-6">
                                <label for="min_lavel_deg_id">Min Level Degree:</label>
                                <select class="form-control" name="min_lavel_deg_id" id="min_lavel_deg_id">
                                    <option value="0">Select Degree</option>
                                    @if(!empty($degrees))
                                        @foreach($degrees as $item)
                                            <option class="text-capitalize"
                                                    <?php if ($item->id == $com->min_degree['id']) echo 'selected'?> value="{{$item->id}}">{{$item->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="max_lavel_deg_id">Max Level Degree:</label>
                                <select class="form-control" name="max_lavel_deg_id" id="max_lavel_deg_id">
                                    <option value="0">Select Degree</option>
                                    @if(!empty($degrees))
                                        @foreach($degrees as $item)
                                            <option class="text-capitalize"
                                                    <?php  if ($item->id == $com->max_degree['id']) echo "selected" ?>       value="{{$item->id}}">{{$item->text}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-5">
                                <label for="first_report">First Report Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       value="{{$com->first_report}}" name="first_report" id="first_report">
                            </div>
                            <div class="form-group col-lg-5">
                                <label for="second_report">Second Report Date *:</label>
                                <input type="text" class="form-control date datepicker"
                                       value="{{$com->second_report}}" name="second_report" id="second_report">
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="recommendations" class="label">Recommendations
                                    <input type="checkbox" class="form-control" name="recommendations_id"
                                           <?php if ($com->recommendations_id == 1) echo 'checked'?> id="recommendations">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <?php $a = json_decode($com->additional) ?>
                            <div class="form-group col-lg-3">
                                <label for="additional_charge_name">Additional Charge Name *:</label>
                                <input type="text" class="form-control" name="additional_charge_name"
                                       value="{{$a->additional_charge_name}}" id="additional_charge_name">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_charge">Additional Charge *:</label>
                                <input type="text" class="form-control" name="additional_charge"
                                       value="{{$a->additional_charge}}" id="additional_charge">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_percentage_name">Additional Percentage Name *:</label>
                                <input type="text" class="form-control" name="additional_percentage_name"
                                       value="{{$a->additional_percentage_name}}" id="additional_percentage_name">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="additional_percentage">Additional Percentage *:</label>
                                <input type="text" class="form-control" name="additional_percentage"
                                       value="{{$a->additional_percentage}}" id="additional_percentage">
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


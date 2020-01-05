@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Add A New Proposal
                </div>
                <div class="card-body">
                    <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i
                        class="text-blue">{{Lang::get('messages.required_all')}}</i>
                    @include('partials.status_bar')

                    @if(count($competitions) == 0)
                    <h5>There are no competitions that you can apply to at this time. Check back again later.</h5>
                    <br />
                    @else
                    <h5>Choose a competition first</h5>
                    <form method="post" action="{{action('Applicant\ProposalController@store')}}" class="row"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group col-lg-12 align-items-center" id="comp_container">
                            <select class="form-control -align-center comp_prop" name="comp_prop" id="comp_prop">
                                <option value="0">Choose the competition</option>
                                @foreach($competitions as $competition)
                                <option value="{{$competition->id}}"
                                    {{$competition->id == old('comp_prop') ? 'selected' : ''}}>{{$competition->title}}
                                </option>
                                @endforeach
                            </select>
                            <input type="hidden" value="" name="domesticorforeign" class="domesticorforeign" />
                            <input type="hidden" value="" name="recommendation" class="recommendation" />
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="category0">Category *:</label>
                            <select class="form-control cat" name="category" id="category0">
                                <option value="0" {{$competition->id == old('comp_prop') ? 'selected' : ''}}>Select
                                    Category</option>
                            </select>

                        </div>
                        <div class="form-group col-lg-6">
                            <label for="sub_category0">Subcategory *:</label>
                            <select type="text" class="form-control" name="sub_category" id="sub_category0">
                                <option value="0">Select Sub Category</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="category1">Secondary Category (optional):</label>
                            <select class="form-control cat" name="sec_category" id="category1">
                                <option value="0">Select Secondary Category</option>
                            </select>

                        </div>
                        <div class="form-group col-lg-6">
                            <label for="sub_category1"> Secondary Subcategory (optional):</label>
                            <select class="form-control" name="sec_sub_category" id="sub_category1">
                                <option value="0">Select Sub Category</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="title">Proposal Title * (<span id="titlecharleft">25</span> words left):</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="title">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="abstract">Abstract * (<span id="abstractcharleft">250</span> words
                                left):</label>
                            <textarea rows="4" class="form-control" name="abstract"
                                id="abstract">{{ old('abstract') }}</textarea>
                        </div>
                        <hr>

                        <div class="col-lg-12">
                            <h4>Primary Institution for Proposal (if any):</h4>
                            <div class="row institution">
                                <div class="form-group col-lg-12">
                                    <label for="inst"></label>
                                    <select id="inst" class="form-control" name="institution" id="institution">
                                        <option value="0">Select institution</option>
                                        @if(!empty($institutions))
                                        @foreach($institutions as $val=>$item)
                                        <option class="text-capitalize" value="{{$val}}"
                                            <?php $val == old('institution') ? 'selected' : '' ?>>{{$item}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <input type="text" id="institutionname" class="form-control" name="institutionname"
                                        value="{{old('institutionname')}}"
                                        placeholder="If your institution is not in the list, type instead the name here">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ action('Applicant\ProposalController@activeProposal') }}"
                                class="btn btn-secondary"> Cancel</a>
                        </div>
                </div>
                </form>
                <br /><br /><br />
                @endif
            </div>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        compselect();
    });

    function count($field, $span) {
        var txtVal = jQuery($field).val();
        var words = txtVal.trim().replace(/\s+/gi, ' ').split(' ').length;
        return words;
    }

    jQuery('#title').on('keyup propertychange paste', function () {
        jQuery('#titlecharleft').html((25 - count('#title')) + '');
    });


    jQuery('#abstract').on('keyup propertychange paste', function () {
        jQuery('#abstractcharleft').html((250 - count('#abstract')) + '');
    });

    function compselect() {
        $comp_prop = $('#comp_prop').val();
        if ($comp_prop == 0) return;
        $('#category0').find('option').remove();
        $('#category1').find('option').remove();
        $.ajax({
            url: '/applicant/selectedcompetition',
            type: 'POST',
            context: {
                element: $('#comp_prop')
            },
            data: {
                _token: CSRF_TOKEN,
                id: $comp_prop
            },
            dataType: 'JSON',
            success: function (data) {
                for (var i in data) {
                    if (data.hasOwnProperty(i)) {
                        if (i === 'cats') {
                            $step = 0;
                            $('#category0').append("<option>Select Category</option>");
                            $('#category1').append("<option>Select Secondary Category</option>");
                            for (var j in data[i]) {
                                if (data[i].hasOwnProperty(j)) {
                                    $('#category0').append("<option value='" + j + "'>" + data[i][j]
                                        .parent + "</option>");
                                    $('#category1').append("<option value='" + j + "'>" + data[i][j]
                                        .parent + "</option>");
                                    if (data[i][j].parent.length > 0)
                                        $('#sub_category' + $step).val(data[i][j].sub);
                                    $step++;
                                }
                            }
                        }
                        if (i === 'recommendation') {
                            if (data[i] >= 1) {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires ' +
                                    data[i] +
                                    ' recommendation letter(s). Make sure you add the names of the recommendors to the project as support people.</i>'
                                    );
                            }
                        }
                        if (i === 'min_age') {
                            if (data[i] > 0) {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires that the PI is at least ' +
                                    data[i] + ' years old.</i>');
                            }
                        }
                        if (i === 'max_age') {
                            if (data[i] < 100) {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires that the PI is less than ' +
                                    data[i] + ' years old.</i>');
                            }
                        }
                        if (i === 'min_level_deg_id') {
                            if (data[i] != "") {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires that the PI has at least a ' +
                                    data[i] + ' degree.</i>');
                            }
                        }
                        if (i === 'max_level_deg_id') {
                            if (data[i] != "") {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires that the PI does not have a degree higher than a ' +
                                    data[i] + '.</i>');
                            }
                        }
                        if (i === 'min_budget') {
                            if (data[i] > 0) {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires a minimum budget of $' +
                                    data[i] + '.</i>');
                            }
                        }
                        if (i === 'max_budget') {
                            if (data[i] < 1000000) {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires a maximum budget of $' +
                                    data[i] + '.</i>');
                            }
                        }
                        if (i === 'allowforeign') {
                            if (data[i] != 1) {
                                $('#comp_container').append(
                                    '<i class="fas fa-question-circle text-blue all"></i><i class="text-blue">This competition requires that the PI resides in Armenia.</i>'
                                    );
                            }
                        }
                    }
                }
            },
            error: function (data) {
                console.error(data);
            }
        });
    }

    $(document).on("change", '.comp_prop', function () {
        compselect();
    });

    $(document).on("change", '#institution', function () {
        if ($('#institution').val() != 0)
            $('#institutionname').val('');
    });

</script>
@endsection

@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" style="margin-top:20px;">
                    <div class="card-header">Update Education for {{$persons_name->first_name}} {{$persons_name->last_name}}
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
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
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if(!empty($degreesperson))
                            <form method="post" action="{{ action('Applicant\DegreePersonController@update', $id) }}" class="row">
                                    @csrf
                                <label for="email"><b>Current degrees:</b></label>
                                @foreach($degreesperson  as $degree)
                                <div class="row col-12">
                                    <input name="_method" type="hidden" value="PATCH">
                                    <div class="form-group col-lg-4">
                                        <label for="description">Degree:</label>
                                        @if(!empty($degrees_list))
                                        <select class="form-control" name="description[]" id="description">
                                            <option value="0">Select Degree</option>
                                            @foreach($degrees_list as $dl)
                                                <option <?php if($degree->degree_id == $dl->id) echo 'selected'; ?> value="{{$dl->id}}">{{$dl->text}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" class="form-check-inline" name="degrees_add_hidden_id[]"
                                               value="{{$id}}"
                                               id="title">
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="year">Year:</label>
                                        <input type="text" class="form-control" name="year[]" id="year" value="{{$degree->year}}">
                                    </div>

                                    <div class="col-lg-4">
                                    <label for="inst"></label>
                                    <select id="inst" class="form-control" name="institution_id[]">
                                        <option value="0">Select institution</option>
                                        @if(!empty($institutions))
                                            @foreach($institutions as $val=>$item)
                                                <option class="text-capitalize" value="{{$val}}" {{$val == $degree->institution_id ? 'selected' : ''}}>{{$item}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="text" id="institution" class="form-control" name="institution[]" value="{{$degree->institution}}" placeholder="If your institution is not in the list, type instead the name here">
                                    </div>

                                    <div class="col-lg-2 align-self-center">
                                        <a href="{{action('Applicant\DegreePersonController@destroy', $degree->id)}}"
                                        class="btn-link col-lg-2">
                                        <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                    <input type="hidden" class="form-check-inline" name="degree_hidden_id[]"
                                            value="{{$degree->id}}"
                                            id="title">
                                </div>
                                @endforeach
                                <div class="form-group col-lg-6">
                                <button type="submit" class="btn btn-primary">Saves Changes</button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <hr>
                    <div class="card-body card_body">
                        <p><b>Add New Degree</b></p>
                        <form method="post" action="{{ action('Applicant\DegreePersonController@store') }}">
                        @csrf
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group col-8">
                                <label for="description">Degree:</label>
                                @if(!empty($degrees_list))
                                <select class="form-control" name="description" id="description">
                                    <option value="0">Select Degree</option>
                                    @foreach($degrees_list as $dl)
                                        <option <?php if(old('description') == $dl->text) echo 'selected'; ?> value="{{$dl->id}}">{{$dl->text}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" class="form-check-inline" name="degrees_add_hidden_id"
                                        value="{{$id}}"
                                        id="title">
                                @endif
                                </div>
                                <div class="form-group col-4">
                                <label for="year">Year:</label>
                                <input type="text" class="form-control" name="year" id="year" value="{{old('year')}}">
                                </div>
                                <div class="col-lg-12">
                                    <label for="inst"></label>
                                    <select id="inst" class="form-control" name="institution_id">
                                        <option value="0">Select Institution</option>
                                        @if(!empty($institutions))
                                            @foreach($institutions as $val=>$item)
                                                <option class="text-capitalize" value="{{$val}}" <?php $val == old('institution_id') ? 'selected' : '' ?>>{{$item}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="text" id="insttext" class="form-control" name="institution" value="{{old('institution')}}" placeholder="If your institution is not in the list, type instead the name here">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12" style="margin-top:20px;">
                        <button type="submit" class="btn btn-primary">Add Degree</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

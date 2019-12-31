@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">Update Education for {{$persons_name->first_name}} {{$persons_name->last_name}}
                        <a href="{{action('Applicant\AccountController@index')}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body" style="overflow:auto;">
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
                        <div class="col-lg-12" >
                        <button type="submit" class="btn btn-primary">Add Degree</button>
                        </div>
                    </form>
                </div>
                        @include('partials.status_bar')
 <hr>
                        @if(!empty($degreesperson) && count($degreesperson)>0)
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
                               </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Create Degree
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
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

                                 @foreach($degreesperson  as $degree)
                                <div class="form-group col-lg-5">
                                    <input name="_method" type="hidden" value="PATCH">
                                    <label for="description">Degree Description:</label>
                                    <input type="text" class="form-control" name="description[]" id="description" value="{{$degree->text}}" disabled>

                                </div>
                                <div class="form-group col-lg-5">
                                    <label for="year">Degree Year:</label>
                                    <input type="text" class="form-control" name="year[]" value="{{$degree->year}}"
                                           id="year">
                                </div>
                                        <div class="form-group col-lg-2">
                                            <label>Remove Degree
                                                <a href="{{action('Applicant\DegreePersonController@destroy', $degree->id)}}"
                                                   class="btn-link col-lg-2">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </label>
                                            <input type="hidden" class="form-check-inline" name="degree_hidden_id[]"
                                                   value="{{$degree->id}}"
                                                   id="title">
                                        </div>
                                @endforeach
                                     <div class="form-group col-lg-6">
                                <button type="submit" class="btn btn-primary">Edit Degree</button>
                                     </div>
                            </form>
                            @endif


                        <form method="post" action="{{ action('Applicant\DegreePersonController@store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="description">Degree Description:</label>
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
                            <div class="form-group">
                                <label for="year">Degree Year:</label>
                                <input type="text" class="form-control" name="year" id="year">
                            </div>
                           <button type="submit" class="btn btn-primary">Save Degree</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

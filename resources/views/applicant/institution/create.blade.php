@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Employment history
                        for {{$person[0]['first_name']." ".$person[0]['last_name']}}
                        <a href="{{action('Applicant\AccountController@index')}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>

                    <div class="card-body" style="overflow:auto;">
                        <p><b>Add New Employment</b></p>

                        <form method="post" action="{{ action('Applicant\InstitutionController@store')}}" class="row">
                            @csrf
                            <div class="col-lg-12 ">
                                <div class="row institution">
                                    <div class="form-group col-lg-4">
                                        <label for="inst">Institution:</label>
                                        <select id="inst" class="form-control" name="institution_id">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions_list) && count($institutions_list))
                                                @foreach($institutions_list as $val => $item)
                                                    <option class="text-capitalize"
                                                            value="{{$item['id']}}" <?php echo (old('institution_id') == $item['id'] ? 'selected' : '');?>>
                                                            {{$item['content']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <input type="text" id="institution" class="form-control" value="{{old('institution')}}" name="institution" placeholder="If your institution is not in the list, type instead the name here">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="title">Title:</label>
                                        <input type="text" class="form-control" value="{{old('i_title')}}" name="i_title" id="title">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="i_type">Type:</label>
                                        <select id="i_type" class="form-control" name="i_type">
                                            <option value="0">Select type</option>
                                            <option value="affiliation" {{old('i_type') == 'affiliation' ? 'selected' : ''}}>Affiliation</option>
                                            <option value="employment" {{old('i_type') == 'employment' ? 'selected' : ''}}>Employment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="start">Start:</label>
                                        <input type="date" class="form-control date datepicker" value="{{old('start')}}" name="start" id="start">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="end">End:</label>
                                        <input type="date" class="form-control date datepicker" value="{{old('end')}}" name="end" id="end">
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="institution_create_hidden" value="{{$id}}" id="institution_create_hidden">
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Employment</button>
                            </div>
                        </form>
                    </div>
                        @include('partials.status_bar')
<hr>

                        @if(!empty($institution_person) && count($institution_person)>0)
                            <form method="post" action="{{action('Applicant\InstitutionController@update', $id) }}"  class="row">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <div class="col-lg-12 ">
                                    <label><b>Current employment history:</b></label>
                                    <div class="row institution">
                                        @foreach($institution_person as $ins)
                                            <div class="form-group col-lg-3">
                                                <input name="_method" type="hidden" value="PATCH">
                                                <label for="institution">Institution:</label>
                                                <select id="institution_id" class="form-control" name="institution_id[]">
                                                    <option value="0">Select institution</option>
                                                    @if(!empty($institutions_list) && count($institutions_list)>0)
                                                        @foreach($institutions_list as $val => $item)
                                                            <option class="text-capitalize" value="{{$item['id']}}"
                                                            <?php if ($item['id'] == $ins->institution_id) { echo 'selected'; }?>>{{$item['content']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <input type="text" id="institution" class="form-control" name="institution[]" value="{{$ins->institution}}" placeholder="If your institution is not in the list, type instead the name here">
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="i_title">Title:</label>
                                                <input type="text" class="form-control" name="i_title[]" id="i_title"
                                                       value="{{$ins->title}}">
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="i_type">Institution type: </label>
                                                <select id="i_type" class="form-control" name="i_type[]">

                                                    @if($ins->type == 'affiliation')
                                                        <option value="0">Select type</option>
                                                        <option value="affiliation" selected>Affiliation</option>
                                                        <option value="employment">Employment</option>
                                                    @elseif($ins->type == 'employment')
                                                        <option value="0">Select type</option>
                                                        <option value="affiliation">Affiliation</option>
                                                        <option value="employment" selected>Employment</option>
                                                    @else
                                                        <option value="0" selected>Select type</option>
                                                        <option value="affiliation">Affiliation</option>
                                                        <option value="employment">Employment</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="start">Start:</label>
                                                <input type="date" class="form-control date datepicker" name="start[]" id="start"
                                                       value="{{$ins->start}}">
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="end">End:</label>
                                                <input type="date" class="form-control date datepicker" name="end[]" id="end"
                                                       value="{{$ins->end}}">
                                            </div>
                                            <div class="col-lg-1 align-self-center">
                                                <a href="{{action('Applicant\InstitutionController@destroyemployment', $ins->id)}}"
                                                class="btn-link">
                                                <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                            <input type="hidden" class="form-check-inline" name="inst_hidden_id[]"
                                               value="{{$ins->id}}"
                                               id="inst_hidden_id">
                                        @endforeach
                                    </div>
                                </div>

                                <input type="hidden" class="form-control" name="institution_creare_hidden"
                                       value="{{$id}}"
                                       id="institution">
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        @endif
                        </div>
                                        </div>
            </div>
            </div>
        </div>
    </div>
@endsection


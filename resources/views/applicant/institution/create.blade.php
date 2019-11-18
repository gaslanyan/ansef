@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Employment history
                        for {{$person[0]['first_name']." ".$person[0]['last_name']}}
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

                        @if(!empty($institution_person))
                            <form method="post" action="{{action('Base\InstitutionController@update', $id) }}"  class="row">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <div class="col-lg-12 ">
                                    <label><b>Current employment history:</b></label>
                                    <div class="row institution">
                                        @foreach($institution_person as $ins)
                                            <div class="form-group col-lg-4">
                                                <input name="_method" type="hidden" value="PATCH">
                                                <label for="institution">Institution:</label>
                                                <select id="institution" class="form-control" name="institution[]">
                                                    <option value="0">Select institution</option>
                                                    @if(!empty($institutions_list))
                                                        @foreach($institutions_list as $val => $item)
                                                            <option class="text-capitalize"
                                                                    value="{{$item['id']}}" <?php if ($item['content'] == $ins['content']) {
                                                                echo 'selected';
                                                            }?>>{{$item['content']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="i_title">Title:</label>
                                                <input type="text" class="form-control" name="i_title[]" id="i_title"
                                                       value="{{$ins['title']}}">
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="i_type">Institution type: </label>
                                                <select id="i_type" class="form-control" name="i_type[]">
                                                    <option value="0">Select type</option>

                                                    @if($ins['type'] == 'affiliation')
                                                        <option value="affiliation" selected>Affiliation</option>
                                                        <option value="employment">Employment</option>
                                                    @else
                                                        <option value="affiliation">Affiliation</option>
                                                        <option value="employment" selected>Employment</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="start">Start:</label>
                                                <input type="date" class="form-control date datepicker" name="start[]" id="start"
                                                       value="{{$ins['start']}}">
                                            </div>
                                            <div class="form-group col-lg-2">
                                                <label for="end">End:</label>
                                                <input type="date" class="form-control date datepicker" name="end[]" id="end"
                                                       value="{{$ins['end']}}">
                                            </div>
                                        <input type="hidden" class="form-check-inline" name="inst_hidden_id[]"
                                               value="{{$id}}"
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
                        <hr>
                        <div class="card-body card_body">
                        <p><b>Add New Employment</b></p>

                        <form method="post" action="{{ action('Base\InstitutionController@store')}}" class="row">
                            @csrf
                            <div class="col-lg-12 ">

                                <i class="fa fa-plus pull-right add text-blue"
                                   style="cursor: pointer"></i>
                                <div class="row institution">
                                    <div class="form-group col-lg-4">
                                        <label for="inst">Institution:</label>
                                        <select id="inst" class="form-control" name="institution[]">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions_list))
                                                @foreach($institutions_list as $val => $item)
                                                    <option class="text-capitalize"
                                                            value="{{$item['id']}}">{{$item['content']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="title">Title:</label>
                                        <input type="text" class="form-control" name="i_title[]" id="title">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="i_type">Type:</label>
                                        <select id="i_type" class="form-control" name="i_type[]">
                                            <option value="0">Select type</option>
                                            <option value="affiliation">Affiliation</option>
                                            <option value="employment">Employment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="start">Start:</label>
                                        <input type="date" class="form-control date datepicker" name="start[]" id="start">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="end">End:</label>
                                        <input type="date" class="form-control date datepicker" name="end[]" id="end">
                                    </div>

                                </div>
                            </div>
                            <input type="hidden" class="form-control" name="institution_creare_hidden"
                                   value="{{$id}}"
                                   id="institution">
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Add Employment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection


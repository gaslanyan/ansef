@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Edit a score type
                        <br>
                        <i class="fas fa-question-circle text-red all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <form method="post" class="row"
                              action="{{action('Admin\ScoreTypeController@update',
                              $scoreType->id) }}">
                            @csrf
                            <div class="form-group col-lg-6">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="name">Score Type Name *:</label>
                                <input type="text" class="form-control" name="name" id="name"
                                       value="{{$scoreType->name }}">

                            </div>
                            <div class="form-group col-lg-6">
                                <label for="text">Competition title:</label>
                                <select id="text" class="form-control" name="competition_id"
                                        id="competition">
                                    <option value="0">Select Competition</option>
                                    <?php if(!empty($competition)):?>
                                    <?php foreach($competition as $key=>$item):?>
                                    <option class="text-capitalize"
                                            <?php if ($key == $scoreType->competition_id):
                                                echo "selected"; endif?>
                                            value="{{$key}}">{{$item}}</option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="description">Description:</label>
                                <textarea class="col-lg-12" name="description"
                                          id="description">{{$scoreType->description}}</textarea>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="min">Score Type Min value:</label>
                                <input type="number" class="form-control"
                                       id="min" name="min" value="{{$scoreType->min}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="max">Score Type Max value:</label>
                                <input type="number" class="form-control"
                                       id="max" name="max" value="{{$scoreType->max}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="weight">Score Type Weight:</label>
                                <input type="number" class="form-control"
                                       id="weight" name="weight" value="{{$scoreType->weight}}">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{ action('Admin\ScoreTypeController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

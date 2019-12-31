@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">Edit a budget category
                    <br>
                        <i class="fas fa-question-circle text-blue all">{{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <form method="post" class="row"
                              action="{{action('Admin\BudgetCategoryController@update', $budget->id) }}">
                            @csrf
                            <div class="form-group col-lg-6">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="name">Budget Name * :</label>
                                <input type="text" class="form-control" name="name"
                                       value="{{$budget->name}}"
                                       id="name">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="text">Competition title * :</label>
                                <select id="text" class="form-control" name="competition_id"
                                        id="competition">
                                    <option value="0">Select Competition</option>
                                    <?php if(!empty($competition)):?>
                                    <?php foreach($competition as $key=>$item):?>
                                    <option class="text-capitalize"
                                            <?php if ($key == $budget->competition_id):
                                                echo "selected"; endif?>
                                            value="{{$key}}">{{$item}}</option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="min">Budget Min value * :</label>
                                <input type="number" class="form-control"
                                       id="min" name="min" value="{{$budget->min}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="max">Budget Max value * :</label>
                                <input type="number" class="form-control"
                                       id="max" name="max" value="{{$budget->max}}">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="weight">Budget Weight value :</label>
                                <input type="number" class="form-control"
                                       id="weight" name="weight" value="{{$budget->weight}}">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="weight">Comments:</label>
                                <input type="text" class="form-control"
                                       id="comments" name="comments" value="{{old('comments')}}">
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{ action('Admin\BudgetCategoryController@index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

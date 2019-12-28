@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Edit a ranking rule
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')


                        <form method="post" class="row"
                              action="{{action('Admin\RankingRuleController@update',
                              $rank->id) }}">
                            @csrf
                            <div class="form-group col-lg-12">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="sql">SQL * :</label>
                                <textarea rows="10" class="form-control" name="sql" id="sql">{{$rank->sql}}</textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="value">Value * :</label>
                                <input type="number" class="form-control"
                                       id="value" name="value" value="{{$rank->value}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="text">Competition title *:</label>
                                <select id="text" class="form-control" name="competition_id"
                                        id="competition">
                                    <option value="">Select Competition * :</option>
                                    <?php if(!empty($competition)):?>
                                    <?php foreach($competition as $key=>$item):?>
                                    <option class="text-capitalize"
                                            <?php if ($key == $rank->competition_id):
                                                echo "selected"; endif?>
                                            value="{{$key}}">{{$item}}</option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="text" id="user">Owner (admin) * :</label>
                                <select id="text" class="form-control" name="user_id"
                                        id="user">
                                    <option value="">Select an admin</option>
                                    <?php if(!empty($competition)):?>
                                    <?php foreach($users as $item):?>
                                    <option class="text-capitalize"
                                            <?php if ($item->user->id == $rank->user_id):
                                                echo "selected"; endif?>
                                            value="{{$item->user->id}}">{{$item->first_name." ".$item->last_name." ".$item->user->email}}</option>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{URL::previous()}}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

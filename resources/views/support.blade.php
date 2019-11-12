@extends('layouts.auth')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Recommendation Letter </div>

                    <div class="card-body ">
                        @if (session('status'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif

                             @if($rec->text == "")
                        <form method="POST" action="{{action('Applicant\SupportController@save',$person_id)}}">
                            @csrf
                            <div class="form-group row">
                                <div class="form-group col-lg-12">
                                    <label for="abstract">Please write recommindations letter about this proposal</label>
                                    <textarea rows="4" class="form-control" name="support_text"
                                              id="abstract" ></textarea>
                                    <input type = "hidden"  value = "{{$person_id}}" name="supp_person_id" />
                                    <input type = "hidden"  value = "{{$rec->proposal_id}}"  name="supp_prop_id" />
                                </div>

                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                    Write Recommendation Letter
                                    </button>
                                </div>
                            </div>
                        </form>
                                 @else
                                <div class="form-group row">
                                    <div class="form-group col-lg-12">
                                       You already write recommendation
                                    </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

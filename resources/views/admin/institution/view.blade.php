@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header">Institution list</div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <table class="table table-striped col-12">

                            <tr>
                                <th>Name</th>
                                @if(!empty($institution['content']))
                                    <td>{{$institution['content']}}</td>
                                @endif
                            </tr>
                            <tr>
                                @if(!empty($address->country_name))
                                    <th>Country</th>
                                    <td>{{$address->country_name}}</td>
                            @endif
                            <tr>
                                <th>Province</th>
                                <td>{{$institution->address->province}}</td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{$city->name}}</td>
                            </tr>
                            <tr>
                                <th>Street</th>
                                <td>{{$institution->address->street}}</td>
                            </tr>
                            <tr>

                                <td class="text-right" colspan="2"><a
                                            href="{{action('Admin\InstitutionController@edit', $institution['id'])}}"
                                            class="btn btn-warning">Edit</a>
                                    <form action="{{action('Admin\InstitutionController@destroy', $institution['id'])}}"
                                          method="post" class="d-inline">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

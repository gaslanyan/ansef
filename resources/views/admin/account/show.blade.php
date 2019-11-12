@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">About Account</div>

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

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Account information</h3>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-user margin-r-5"></i> First Name:</strong>

                                        <p>{{$person['first_name']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-user margin-r-5"> </i>Last Name:</strong>
                                        <p>{{$person['last_name']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-calendar margin-r-5"></i> Birth date:</strong>
                                        <p>{{$person['birthdate']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-location-arrow margin-r-5"></i>Birth Place:</strong>
                                        <p>{{$person['birthplace']}}</p>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-bullseye margin-r-5"></i> Nationality:</strong>
                                        <p>{{$person['nationality']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-mars-double margin-r-5"></i>Sex:</strong>
                                        <p>{{$person['sex']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-bookmark margin-r-5"></i> Account State:</strong>
                                        <p>{{$person['state']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-bookmark margin-r-5"></i>Account role:</strong>
                                        <p>{{$person['type']}}</p>
                                    </div>

                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            @if(!empty($institution))
                                <div class="box-body col-md-12">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <strong><i class="fa fa-university margin-r-5"></i>Institution/Affiliation:</strong>
                                            <table class="col-md-12">
                                                <tr>
                                                    <?php $columns = getTableColumnsName('institutions_persons');?>
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach($institution['ip'] as $key=>$value)
                                                        @foreach($value as $val)
                                                            <td>{{$val}}</td>
                                                        @endforeach
                                                    @endforeach
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- /.box-body -->
                                    </div>
                                </div>
                                <hr>
                            @endif
                            @if(!empty($phones) || !empty($email))

                                <div class="box-body col-md-12">
                                    <div class="row">
                                        @if(!empty($phones))
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-phone margin-r-5"></i> Phones:</strong>
                                                @foreach($phones as $phone)
                                                    <p>{{$phone["country_code"]." ".$phone["number"]}}</p>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if(!empty($emails))
                                            <div class="col-md-6">
                                                <strong><i class="fa fa-phone margin-r-5"></i> Emails:</strong>
                                                @foreach($emails as $email)
                                                    <p>{{$email['email']}}</p>
                                                @endforeach
                                            </div>
                                            <hr>
                                        @endif

                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <hr>
                            @endif
                            @if(!empty($address_array))
                                <div class="box-body col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong><i class="fa fa-address-book margin-r-5"></i> Address:</strong>
                                            <?php $i = 0;?>
                                            <table class="col-md-12">
                                                <tr>
                                                    @foreach($address_array as $address)
                                                        @foreach($address as $key=>$addr)
                                                            @if ($i < 1)
                                                                <th class="text-capitalize">{{$key}}
                                                                </th>
                                                @endif
                                                @endforeach
                                                @php
                                                    $i++;
                                                @endphp
                                                @endforeach
                                                @foreach($address_array as $key=>$address)
                                                    <tr>
                                                        @foreach($address as $addr)
                                                            <td>{{$addr}}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach

                                            </table>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <hr>
                            @endif
                            @if(!empty($books))
                                <div class="box-body col-md-12">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <strong><i class="fa fa-book margin-r-5"></i> Books:</strong>
                                            <?php $i = 0;?>
                                            <table class="col-md-12">
                                                <tr>
                                                    @php $columns = getTableColumnsName('books');@endphp
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                @php getTableColumns($books);@endphp
                                            </table>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <hr>
                            @endif

                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if(!empty($degrees))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-graduation-cap margin-r-5"></i>
                                                Degrees:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    <th class="text-capitalize">Description</th>
                                                    <th class="text-capitalize">year</th>

                                                </tr>
                                                <tr>
                                                    <td>{{$degrees['degree']['text']}}</td>
                                                    <td>{{$degrees['year']}}</td>
                                                </tr>
                                            </table>
                                        </div>

                                    @endif
                                    @if(!empty($honors))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-trophy margin-r-5"></i> Honors:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    @php $columns = getTableColumnsName('honors');@endphp
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                @php getTableColumns($honors);@endphp
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <!-- /.box-body -->
                            </div>


                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if(!empty($meetings))
                                        <div class="col-md-12">
                                            <strong><i class="fa fa-meetup margin-r-5"></i> Meetings:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    @php $columns = getTableColumnsName('meetings');@endphp
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                @php getTableColumns($meetings);@endphp
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- /.box-body -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


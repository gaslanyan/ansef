@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Profile
                        <a href="{{ action('Applicant\AccountController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <div class="box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Account information</h3>
                            </div>

                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-user margin-r-5"></i> First Name:</strong>
                                        <p>{{$person[0]['first_name']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-user margin-r-5"> </i>Last Name:</strong>
                                        <p>{{$person[0]['last_name']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-calendar margin-r-5"></i> Birth date:</strong>
                                        <p>{{$person[0]['birthdate']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-location-arrow margin-r-5"></i>Birth Place:</strong>
                                        <p>{{$person[0]['birthplace']}}</p>
                                    </div>
                                </div>
                                <hr>
                                <!-- /.box-body -->
                            </div>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-bullseye margin-r-5"></i> Nationality:</strong>
                                        <p>{{$person[0]['nationality']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-mars-double margin-r-5"></i>Sex:</strong>
                                        <p>{{$person[0]['sex']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-bookmark margin-r-5"></i> Account State:</strong>
                                        <p>{{$person[0]['state']}}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <strong><i class="fa fa-bookmark margin-r-5"></i>Account type:</strong>
                                        <p>{{$person[0]['type']}}</p>
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
                                                            <th class="text-capitalize">{{$key}}</th>
                                                @endforeach
                                                <?php
                                                $i++;
                                                if ($i === 1)
                                                    break;
                                                ?>
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

                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if(!empty($books))
                                        <div class="col-md-12">
                                            <strong><i class="fa fa-book margin-r-5"></i> Books:</strong>
                                            <?php $i = 0;?>
                                            <table class="col-md-12">
                                                <tr>
                                                    <?php $columns = getTableColumnsName('books');?>
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                <?php getTableColumns($books);?>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <hr>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if(!empty($degrees))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-graduation-cap margin-r-5"></i>
                                                Degrees:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    <?php $columns = getTableColumnsName('degrees');?>
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                <?php getTableColumns($degrees);?>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                                </div>
                            <hr>
                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if(!empty($honors))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-trophy margin-r-5"></i> Honors:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    <?php $columns = getTableColumnsName('honors');?>
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                <?php getTableColumns($honors);?>
                                            </table>
                                        </div>
                                    @endif
                                        @if(!empty($disciplines))
                                        <div class="col-md-6">
                                            <strong><i class="fa fa-sticky-note margin-r-5"></i> Disciplines:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    <?php $columns = getTableColumnsName('disciplines');?>
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                <?php getTableColumns($disciplines);?>
                                            </table>
                                        </div>
                                    @endif


                                </div>
                                <!-- /.box-body -->
                            </div>
                            <hr>

                            <div class="box-body col-md-12">
                                <div class="row">
                                    @if(!empty($meetings))
                                        <div class="col-md-12">
                                            <strong><i class="fa fa-sticky-note margin-r-5"></i> Meetings:</strong>

                                            <table class="col-md-12">
                                                <tr>
                                                    <?php $columns = getTableColumnsName('meetings');?>
                                                    @foreach($columns as $col)
                                                        <th class="text-capitalize">{{$col}}</th>
                                                    @endforeach
                                                </tr>
                                                <?php getTableColumns($meetings);?>
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


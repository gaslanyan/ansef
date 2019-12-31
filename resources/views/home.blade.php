@extends('layouts.auth')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                $content = \Illuminate\Support\Facades\Storage::disk('local')->get('lock.json');
                $content = json_decode($content);

                if(!empty($content) && $content->lock == "off"):?>
                <img src="{{asset('img/off.png')}}" class="off">
                <?php else: ?>
                 <div class="card" >
                    <div class="card-header" style="font-size:20px;">Welcome to the ANSEF portal</div>
                    <div class="row" style="color:#999;font-size:16px;margin-left:10px;">
                        <p>If you want to apply to an ANSEF competition, click on <b>APPLICANT</b>.</p>
                        <p>If you are an ANSEF referee, click on <b>REFEREE</b>.</p>
                    </div>
                    <div class="card-body row">
                        {{-- <div class="col-md-12">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/superadmin">SUPER-ADMIN</a>
                                </h3>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/applicant">APPLICANT</a>
                                </h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/referee">REFEREE</a>
                                </h3>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/admin">ADMIN</a>
                                </h3>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-6">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/viewer">VIEWER</a>
                                </h3>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <?php endif ?>

            </div>
        </div>
    </div>
@endsection

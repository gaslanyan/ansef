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
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Welcome to ANSEF!!</div>

                    <div class="card-body row">
                        <div class="col-md-12">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/superadmin">SUPER-ADMIN</a>
                                </h3>
                            </div>
                        </div>
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
                                    <a href="login/admin">ADMIN</a>
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
                        <div class="col-md-6">
                            <div class="card-header">
                                <h3 class="text-center">
                                    <a href="login/viewer">VIEWER</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif ?>

            </div>
        </div>
    </div>
@endsection

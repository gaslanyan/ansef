                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                    </div><br />
                    @elseif (\Session::has('wrong'))
                    <div class="alert alert-success">
                        <p>{{ \Session::get('wrong') }}</p>
                    </div><br />
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
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div><br />
                    @endif

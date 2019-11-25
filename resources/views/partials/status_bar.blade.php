                    @if (\Session::has('success'))
                    <div class="alert alert-success">
                        @php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp
                    </div>
                    @endif
                    @if (\Session::has('wrong'))
                    <div class="alert alert-info">
                        @php echo html_entity_decode(\Session::get('wrong'), ENT_HTML5) @endphp
                    </div>
                    @endif
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            @php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp
                        </div>
                    @endif
                    @if (\Session::has('delete'))
                        <div class="alert alert-info">
                            @php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp
                        </div>
                    @endif
                    @if (\Session::has('status'))
                        <div class="alert alert-danger">
                            @php echo html_entity_decode(\Session::get('status'), ENT_HTML5) @endphp
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

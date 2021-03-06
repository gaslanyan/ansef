<div class="box-body col-md-12">

    @if(!empty($persons) && count($persons) > 0)
    <div class="box-header with-border">
       <h4>  Biographical sketches </h4>
    </div>
    @foreach($persons as $person)
        @php
            $emails = $person->emails;
            $phones = $person->phones;
            $addresses = $person->addresses;
            $institutions = \App\Models\InstitutionPerson::where('person_id','=',$person->id)
                            ->get()->sortBy('start');
            $institutionslist = \App\Models\Institution::all()->sortBy('content')->keyBy('id');
            $degrees = \App\Models\DegreePerson::where('person_id','=',$person->id)
                        ->join('degrees', 'degree_id', '=', 'degrees.id')->get()->sortBy('year');
            $honors = $person->honors->sortBy('year');
            $books = $person->books->sortBy('year');
            $meetings = $person->meetings->sortBy('year');
            $publications = $person->publications->sortBy('year');
        @endphp
        @include('partials.person',
            [
                'person' => $person,
                'phones' => $phones,
                'emails' => $emails,
                'addresses' => $addresses,
                'institutions' => $institutions,
                'degrees' => $degrees,
                'honors' => $honors,
                'books' => $books,
                'meetings' => $meetings,
                'publications' => $publications,
                'showdownloads' => $showdownloads
            ])
    @endforeach
    @endif
    <br/><br/><br/>
    @if(!empty($recommendations))
    @if(count($recommendations) > 0 && $showdownloads)
    <div class="box-header with-border">
        <h4 class="row">  Recommendation letters </h4>
    </div>
    @foreach($recommendations as $recommendation)
            <strong><i class="fa fa-file margin-r-5"></i> Recommendation letter from {{$recommendation->person->first_name . ' ' . $recommendation->person->last_name}}:</strong><br/>
            <a href="{{route('downloadletter', $recommendation->document)}}" target="_blank" class="btn-link">
                <i class="fa fa-download"></i> Download letter</a>
                <hr>
    @endforeach
    @endif
    @endif

</div>

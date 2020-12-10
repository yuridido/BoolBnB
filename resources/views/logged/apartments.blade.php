@extends('layouts.app')

@section('content')
    {{-- @dd($messages) --}}
    <div class="container-center">
        @if (session('status'))
            <p class="status-msg">{{ session('status') }}</p>
        @endif

        @if (count($apartments) > 0)
            <div class="head">
                <h2 class="title">I tuoi appartamenti</h2>
                <a class="create-apt-link" href="{{ route('host.create') }}">Crea un nuovo annuncio!</a>
            </div>
            <div class="apartments-list">
                @foreach ($apartments as $apartment)
                    <div class="apt-info-general">
                        {{-- faccio un ternario per vedere se l'appartamento è inattivo ed uno
                        per vedere se sponsorizzato
                        e nel caso assegno caratteristiche --}}
                        <div class="overlay {{ $apartment->attivo == 1 ? 'active-apt' : 'inactive-apt' }}"></div>




                        <div class="apt-info-sx">

                            <div class=" {{ $apartment->attivo == 1 ? 'hidden' : 'inactive-msg' }}">
                                {{ $apartment->attivo == 1 ? '' : 'Annuncio inattivo' }}</div>
                            @if (isset($apartment->images[0]->path))
                                <img class=apt-img-small src="{{ asset('storage/' . $apartment->images[0]->path) }}"
                                    alt="{{ $apartment->title }}">
                            @endif
                        </div>

                        <div class="apt-info-dx">
                            <div class="apt-title">

                                <p>{{ strlen($apartment->title) <= 25 ? $apartment->title : substr($apartment->title, 0, 18) . '...' }}
                                </p>
                            </div>
                            <div class="apt-description">
                                <p class="apt-address">{{ $apartment->city }}, {{ $apartment->country }}</p>
                            </div>
                            <p class="apt-details"> Caratteristiche: nr. stanze: {{ $apartment->rooms }}, nr. letti:
                                {{ $apartment->beds }} - nr. bagni: {{ $apartment->bathrooms }} - mq: {{ $apartment->sm }}
                            </p>

                            <p class="apt-description-text">
                                {{ strlen($apartment->description) <= 50 ? $apartment->description : substr($apartment->description, 0, 50) . '...' }}
                            </p>
                            <ul class="apt-services-show">
                                @foreach ($apartment->services as $service)
                                    <li class="service">
                                        <i class="service-icon-appartments fas {{ $service->icon }}"></i>
                                    </li>
                                @endforeach
                            </ul>
                            @for ($i = 0; $i < count($spons); $i++)
                                @if ($apartment->id == $spons[$i]->apartment_id)
                                    <div class="apt-info-sponsor">
                                        <i class="fas fa-star"></i>
                                        <div>sponsorizzato fino al: {{ $spons[$i]->end_sponsor }}</div>
                                    </div>
                                @endif
                            @endfor
                            <div class="apartments__go-to">
                                <a href="{{ route('host.show', $apartment->id) }}" class="apartment-button">Vai
                                    all'appartamento</a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="head">

                <h2 class="title">Non hai ancora registrato un appartamento</h2>
                <a class="create-apt-link" href="{{ route('host.create') }}">Crea un nuovo annuncio!</a>

            </div>
        @endif


    </div>

@endsection

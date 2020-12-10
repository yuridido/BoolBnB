@extends('layouts.app')
@section('content')
    <div class="container-center">
        <section class="top-section">
            <div class="title-apt">

                <p class="title">{{ strlen($apartment->title) <= 60 ? $apartment->title : substr($apartment->title,0,18).'...' }}</p>
                <a class="address-apt" href="#">{{ $apartment->address }}, {{ $apartment->city }},
                    {{ $apartment->country }}</a>
            </div>
        </section>
        <section class="slider-section">
            <div class="apt-images">
                <div class="apt-images">
                    <div class="apt-image-icon apt-image-icon-left  arrow-slider-sx {{$apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}">
                        <i class="fas fa-chevron-left {{$apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}"></i>
                    </div>
                @for ($i = 0; $i < $apartment->images->count('id'); $i++)

                    <img class="apt-image {{ $i == 0 ? 'active first' : ($i == $apartment->images->count('id') - 1 ? 'hidden last' : 'hidden') }}"
                        src="{{ asset('storage/' . $apartment->images[$i]->path) }}" alt="{{ $apartment->title }}">
                @endfor
                <div class="dots__carousel-container">
                    @if ($apartment->images->count('id') > 1)
                    @for ($i = 0; $i < $apartment->images->count('id'); $i++)
                    <div class="dots__carousel {{$i == 0 ? 'dots__carousel-active first' : ($i == $apartment->images->count('id') - 1 ? ' last' : '') }}"></div>
                    @endfor
                    @endif
                   </div>
                <div class="apt-image-icon apt-image-icon-right arrow-slider-dx {{$apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}">
                    <i class="fas fa-chevron-right {{$apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}"></i>
                </div>
            </div>
        </section>

        <section class="info-apt-section">
            <div class="info-box-sx">
                <div class="info-apt-summary">
                    <div class="apt-summary">
                        <p class="info-host">Host: {{ $apartment->user->name }} - Prezzo:
                            €{{ $apartment->daily_price }}/giorno</p>
                        <p class="info-apt"> nr. stanze: {{ $apartment->rooms }}, nr. letti: {{ $apartment->beds }} - nr.
                            bagni: {{ $apartment->bathrooms }} - mq: {{ $apartment->sm }}</p>
                    </div>
                    <div class="host-logo">
                        @if(isset($$apartment->images[0]->path))
                        <img class="img-apartment-host" src="{{asset('storage/'.$apartment->images[0]->path)}}" alt=" {{ $apartment->user->name }}">
                        @endif
                    </div>
                </div>

                <div class="apt-description">
                    <p>{{ $apartment->description }}</p>
                </div>

                <div class="services-box">
                    <p class="services-title">Servizi</p>
                    <ul class="services">
                        @foreach ($apartment->services as $service)
                            <li class="service-list">
                                <div class="service-head">
                                    <i class="service-icon {{ $service->icon }}"></i>
                                    <p class="service-title">{{ $service->service }}</p>
                                </div>
                                {{-- <span id="service-descr">{{ $service->description }}</span> --}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="info-box-dx">
                <div class="send-message-box">
                    <p class="message-title">Host Menù </p>
                    <div class="message-form">
                        <button type="button" class="btn-show services-all visualizza-appartamneto"><a
                                href="{{ route('logged.sponsor', $apartment->id) }}">Sponsorizza il tuo
                                appartamento!</a></button>
                        <button type="button" class="btn-show services-all visualizza-appartamneto"><a href="{{route('host.edit', $apartment->id)}}">Modifica
                                annuncio</a></button>
                        {{-- <button type="button"
                            class="btn-show services-all visualizza-appartamneto"><a href="#">Modifica
                                disponibilità</a></button> --}}
                        <form action="{{ route('logged.visibility', $apartment->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-show services-all visualizza-appartamneto" style="font-size:1.6rem">
                                @if ($apartment->attivo == 0)
                                    Attiva annuncio!
                                @else
                                    Disattiva annuncio!
                                @endif
                            </button>
                        </form>
                        <button type="button" class="btn-show services-all visualizza-appartamneto"><a
                                href="{{route('messages.index')}}">MAILBOX!</a></button>
                        {{-- <a
                            href="{{ route('logged.sponsor', $apartment->id) }}">Sponsorizza il tuo appartamento!</a>
                        --}}
                    </div>
                </div>
            </div>
        </section>

        <section class="chart-section">
            <canvas id="chart-views"></canvas>
            <canvas id="chart-messages"></canvas>
        </section>
        <input type="hidden" id="stats-check" style="display:none" value="{{$apartment->id}}">
    </div>
    <script src="{{ asset('js/stats.js') }}"></script>
@endsection

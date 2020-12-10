@extends('layouts.app')
@section('content')
    <div class="container-center">
        @if (session('status'))
            <div class="status-msg">
                <p>{{ session('status') }}</p>
                {{-- <p>Messaggio cancellato correttamente.</p>
                --}}
            </div>
        @endif
        <section class="top-section">
            <span class="hidden" id="app-id">{{ $apartment->id }}</span>
            <div class="title-apt">
                <p class="title">
                    {{ strlen($apartment->title) <= 60 ? $apartment->title : substr($apartment->title, 0, 18) . '...' }}</p>
                <a class="address-apt" href="#">{{ $apartment->address }}, {{ $apartment->city }},
                    {{ $apartment->country }}</a>
            </div>
        </section>
        <div class="container-slider-app">
            <section class="slider-section">
                <div class="apt-images">
                    <div
                        class="apt-image-icon apt-image-icon-left  arrow-slider-sx {{ $apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}">
                        <i class="fas fa-chevron-left {{ $apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}"></i>
                    </div>


                    @for ($i = 0; $i < $apartment->images->count('id'); $i++)
                        <img class="apt-image {{ $i == 0 ? 'active first' : ($i == $apartment->images->count('id') - 1 ? 'hidden last' : 'hidden') }}"
                            src="{{ asset('storage/' . $apartment->images[$i]->path) }}" alt="{{ $apartment->title }}">

                    @endfor
                    <div class="dots__carousel-container">
                        @if ($apartment->images->count('id') > 1)
                            @for ($i = 0; $i < $apartment->images->count('id'); $i++)
                                <div
                                    class="dots__carousel {{ $i == 0 ? 'dots__carousel-active first' : ($i == $apartment->images->count('id') - 1 ? ' last' : '') }}">
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div
                        class="apt-image-icon apt-image-icon-right arrow-slider-dx {{ $apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}">
                        <i class="fas fa-chevron-right {{ $apartment->images->count('id') == 1 ? 'hidden' : 'pippo' }}"></i>
                    </div>
                </div>
            </section>
        </div>

        <section class="info-apt-section">
            <div class="info-box-sx">
                <div class="info-apt-summary">
                    <div class="apt-summary">
                        <p class="info-host">Host: {{ $apartment->user->name }} - Prezzo:
                            €{{ $apartment->daily_price }}/giorno</p>
                        <p class="info-apt"> nr. stanze: {{ $apartment->rooms }}, nr. letti: {{ $apartment->beds }} - nr.
                            bagni: {{ $apartment->bathrooms }} - mq: {{ $apartment->sm }}</p>
                        {{-- <p class="price">Prezzo giornaliero:
                            €{{ $apartment->daily_price }}</p> --}}
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
                                    <p>{{ $service->service }}</p>
                                </div>
                                {{-- <span
                                    id="service-descr">{{ $service->description }}</span> --}}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="info-box-dx">
                <div class="send-message-box">
                    <p class="message-title">Contatta l'Host!</p>
                    <div class="message-form">
                        <form class="" action="{{ route('send.message') }}" method="POST">
                            @method('POST')
                            @csrf
                            <div class="input-aps-group">
                                <p class="firstname-message"></p>
                                <label for="fname">Nome:</label>
                                <input type="text" id="firstnameM" name="name"
                                    value="{{ Auth::check() ? Auth::user()->name : '' }}" name="firstname">
                                <p class="message-E">error</p>
                            </div>
                            <div class="input-aps-group">
                                <p class="lastname-message"></p>
                                <label for="lname">Cognome:</label>
                                <input type="text" id="lastnameM" name="lastname"
                                    value="{{ Auth::check() ? Auth::user()->lastname : '' }}" name="lastname">
                                <p class="message-E">error</p>
                            </div>
                            <div class="input-aps-group">
                                <p class="email-message"> </p>
                                <label for="email">Email:</label>
                                <input type="email" id="emailM" value="{{ Auth::check() ? Auth::user()->email : '' }}"
                                    name="email">
                                <p class="message-E">error</p>
                                </p>

                                <label for="message">Messaggio</label>
                                <textarea name="message" id="messageM"
                                    rows="10">{{ Auth::check() ? 'Buongiorno sono ' . Auth::user()->name : '' }}</textarea>
                                <p class="message-E">error</p>
                                <div class="input-aps-group">
                                    <input type="hidden" value="{{ $apartment->id }}" name="apartment_id">
                                    <p class="send-message">
                                        <input id="send-message" type="submit" value="Invia messaggio"></input>
                                    </p>
                        </form>
                    </div>

                </div>
            </div>
        </section>


        <section class="map-section">

            <div class="map-apartment" id="map"></div>

            {{-- <div class="message-box">

            </div> --}}
        </section>
    </div>
    <script src="{{ asset('js/apt.js') }}"></script>
@endsection

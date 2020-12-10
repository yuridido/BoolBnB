@extends('layouts.app')

@section('content')

<div class="container-center  create-update">
    <div class="card-container">
        <h2>Modifica il tuo appartamento</h2>


        <form id="editing" action="{{ route('host.update', $apartment->id) }}" name="editing" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method("PATCH")

            <!-- TITOLO -->
            <div class="input-row">
                <label for="title">Titolo</label>
                <input type="text" id="title" name="title" class="" value="{{ $apartment->title }}" />
                <p class="message-E"></p>
            </div>

            <!-- INDIRIZZO -->
            <div class="input-row">
                <label for="address">Indirizzo</label>
                <input type="text" id="address" name="address" class="" value="{{ $apartment->address }}" />
                <p class="message-E"></p>
            </div>

            <div class="input-row">
                <div class="input-group city-info">
                    <div class="label-input">
                        <label for="city">Città</label>
                        <input id="city" type="text" name="city" value="{{ $apartment->city }}">
                        <p class="message-E">errore</p>
                    </div>
                    <div class="label-input">
                        <label for="postal_code">Codice Postale</label>
                        <input id="postal-code" type="text" name="postal_code" value="{{ $apartment->postal_code }}">
                        <p class="message-E">errore</p>
                    </div>
                    <div class="label-input">
                        <label for="country">Nazione</label>
                        <input id="country" type="text" name="country" value="{{ $apartment->country }}">
                        <p class="message-E">errore</p>
                    </div>
                </div>
            </div>

            <div class="input-row">
                <label for="description">Descrizione dell'appartamento</label>
                <textarea id="description" name="description" rows="10">{{ $apartment->description }}</textarea>
                <p class="message-E">error</p>
            </div>

            <!-- CARATTERISTICHE-->
            <div class="input row">
                <div class="input-group caratteristiche">
                    <div class="label-input">
                        <label for="daily_price">Prezzo/notte</label>
                        <input id="daily-price" type="number" name="daily_price" value="{{ $apartment->daily_price }}">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="sm">Mq</label>
                        <input id="sm" type="number" name="sm" value="{{ $apartment->sm }}">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="rooms">Stanze</label>
                        <input id="rooms" type="number" name="rooms" value="{{ $apartment->rooms }}">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="beds">Posti letto</label>
                        <input id="beds" type="number" name="beds" value="{{ $apartment->beds }}">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="bathrooms">Bagni</label>
                        <input id="bathrooms" type="number" name="bathrooms" value="{{ $apartment->bathrooms }}">
                        <p class="message-E"></p>
                    </div>
                </div>

                <!-- SERVIZI -->
                <div class="input-group all-services">
                    @foreach($services as $service)
                    <div class="label-input service">
                        <span><i class="{{$service->icon}}"></i></span>
                        <label for="services">{{ $service->service }}</label>
                        <input type="checkbox" name="services[]" value="{{ $service->id }}"
                            {{ ($apartment->services->contains($service->id)) ? "checked" : "" }}>
                    </div>
                    @endforeach

                </div>

                <!-- IMMAGINI -->
                <h3>Aggiungi immagini</h3>
                <div class="container-upload">
                    <input type="file" name="img[]" id="img" accept="image/*" multiple>

                </div>



                <!-- CAMPI HIDDEN -->

                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <input id="latitude" type="hidden" name="latitude" value="{{$apartment->latitude}}">
                <input id="longitude" type="hidden" name="longitude" value="{{$apartment->longitude}}">





                <input type="submit" id="crea">



            </div>
        </form>

        {{-- status di avvenuta cancellazione immagine --}}
        @if(session('status'))
        <p>{{session('status')}}</p>
        @endif
        <p style="font-size:1.8rem">elimina le immagini</p>
    <div class="eleimina-immagini">
        
        {{-- ciclo per visualizzazione ed eliminazione delle immagini dell'appartamento --}}
        @foreach($apartment->images as $image)
        <form action="{{route('images.destroy', $image)}}" method="post" class="img-apt-box img-edit">
            @csrf
            @method('DELETE')
            <img src="{{ asset('storage/'. $image->path) }}" alt="foto appartamento">
            <button type="submit" class="img-delete"><i class="fas fa-times x"></i></button>
        </form>
        @endforeach
    </div>

    </div>


</div>
@endsection

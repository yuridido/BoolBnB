@extends('layouts.app')

@section('content')

<div class="container-center create-update">
    <div class="card-container">
        <h2>Registra il tuo appartamento</h2>



        <form id="creazione" action="{{ route('host.store') }}" name="creazione" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method("POST")

            <!-- TITOLO -->
            <div class="input-row">
                <label for="title">Titolo</label>
                <input type="text" id="title" name="title" class="" />
                <p class="message-E"></p>
            </div>

            <!-- INDIRIZZO -->
            <div class="input-row">
                <label for="address">Indirizzo</label>
                <input type="text" id="address" name="address" class="" autocomplete="off" />
                <p class="message-E"></p>
            </div>

            <div class="input-row">
                <div class="input-group city-info">
                    <div class="label-input">
                        <label for="city">Città</label>
                        <input id="city" type="text" name="city" autocomplete="off">
                        <p class="message-E">errore</p>
                    </div>
                    <div class="label-input">
                        <label for="postal_code">Codice Postale</label>
                        <input id="postal-code" type="text" name="postal_code" autocomplete="off">
                        <p class="message-E">errore</p>
                    </div>
                    <div class="label-input">
                        <label for="country">Nazione</label>
                        <input id="country" type="text" name="country">
                        <p class="message-E">errore</p>
                    </div>
                </div>
            </div>

            <div class="input-row">
                <label for="description">Descrizione dell'appartamento</label>
                <textarea id="description" name="description" rows="10"></textarea>
                <p class="message-E">Errore</p>
            </div>

            <!-- CARATTERISTICHE-->
            <div class="input row">
                <div class="input-group caratteristiche">
                    <div class="label-input">
                        <label for="daily_price">Prezzo/notte</label>
                        <input id="daily-price" type="number" name="daily_price">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="sm">Mq</label>
                        <input id="sm" type="number" name="sm">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="rooms">Stanze</label>
                        <input id="rooms" type="number" name="rooms">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="beds">Posti letto</label>
                        <input id="beds" type="number" name="beds">
                        <p class="message-E"></p>
                    </div>
                    <div class="label-input">
                        <label for="bathrooms">Bagni</label>
                        <input id="bathrooms" type="number" name="bathrooms">
                        <p class="message-E"></p>
                    </div>
                </div>

                <!-- SERVIZI -->
                <div class="input-group all-services">
                    @foreach ($services as $service)
                    <div class="label-input service">
                        <span><i class="{{ $service->icon }}"></i></span>
                        <label for="services">{{ $service->service }}</label>
                        <input type="checkbox" name="services[]" value="{{ $service->id }}">
                    </div>
                    @endforeach

                </div>



                <!-- IMMAGINI -->
                <h3>Aggiungi le tue immagini</h3>
                <div class="container-upload">
                    <input type="file" name="img[]" id="img" accept="image/*" multiple required>
                </div>



                <!-- CAMPI HIDDEN -->
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <input id="latitude" type="hidden" name="latitude" value="">
                <input id="longitude" type="hidden" name="longitude" value="">


                <input type="submit" id="crea" value="Registra">



            </div>
        </form>

    </div>






</div>
@endsection

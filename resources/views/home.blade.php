@extends('layouts.app')
@section('content')
    <header class="jumbotron">
        <div class="container-center container-fullheight">
            <div class="jumbotron__text">
                <p class="jumbotron__text-banner">Vicino è bello</p>
                <form action="{{ route('search.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit" class="jumbotron__text-button">Esplora i soggiorni nei dintorni</button>
                    <input type="hidden" name="address" id="ip-home-search" value="">
                    <input type="hidden" name="range" id="ip-home-search" value="100">
                </form>
            </div>
        </div>
    </header>

<div class="container-center">
    <section class="highlited">
        <p class="sponsor__home-title">In evidenza</p>
        <div class="sponsor__home">
            @if (count($apartment) > 0)
                @for ($i = 0; $i < 4 && $i < count($apartment); $i++)
                    @if($apartment[$i]->user_id == Auth::id())
                        <a href="{{ route('host.show', $apartment[$i]->id) }}" class="sponsor__home-card">
                    @else
                        <a href="{{ route('search.show', $apartment[$i]->id) }}" class="sponsor__home-card">
                    @endif    
                            <div class="sponsor__home-card-img">
                                @if (isset($apartment[$i]->images[0]->path))
                                    <img src="{{ asset('storage/'.$apartment[$i]->images[0]->path) }}" alt="{{ $apartment[$i]->title }}" alt="">
                                @endif
                            </div>
                            <div class="sponsor__home-card-text">
                                
                                <p>{{ strlen($apartment[$i]->title) <= 25 ? $apartment[$i]->title : substr($apartment[$i]->title, 0, 18) . '...' }}</p>
                            </div>
                        </a>
                @endfor
            @endif
    </section>
</div>
@endsection

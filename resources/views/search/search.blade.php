@extends('layouts.app')
@section('content')
    <div class="container__search">
        <div class="container__search-left">
        <div class="container__search-left__top">
            <div class="container__search-left__top__text">
                <span class="container__search-left__top__text-heading">I tuoi risultati per</span>
                <span id="address-inst">{{$address ?? ''}}</span>
                <span id="range-form" class="hidden">{{$range ?? ''}}</span>
            </div>
            <div class="filter-toggle"><span class="filter-toggle-text">Filtri</span> <i class="fas fa-chevron-down chevron-filter"></i></div>
            <div class="container__search-left__top__filters hidden">
              
                <div class="services">
                   
                </div>
                {{-- <div class="filter__chars">
                    <div class="filter__chars-item filter__chars-stanze">
                       
                        <p class="filter__chars-name">Numero stanze</p>
                        <div class="filter__chars-range">
                        <div class="minus">
                       <i class="fas fa-minus" id="filter__minus"></i>
                       </div>
                       <p class="filter__chars__numbers room-numbers">2</p>
                       <div class="plus">
                       <i class="fas fa-plus" id="filter__plus"></i>
                    </div>
                    </div>
                    </div>
                    <div class=" filter__chars-item filter__chars__letti">
                        <p class="filter__chars-name beds-number">Posti letto</p>
                      <div class="filter__chars-range">
                         <div class="minus">
                            <i class="fas fa-minus" id="filter__minus"></i>
                            </div>
                            <p class="filter__chars__numbers beds">2</p>
                            <div class="plus">
                            <i class="fas fa-plus" id="filter__plus"></i>
                          </div>
                       </div>
                    </div>
                    <div class=" filter__chars-item filter__chars-posti-bagni">
                        <p class="filter__chars-name">Bagni</p>
                        <div class="filter__chars-range">
                        <div class="minus">
                            <i class="fas fa-minus" id="filter__minus"></i>
                        </div>
                        <p class="filter__chars__numbers toilets">2</p>
                        <div class="plus">
                            <i class="fas fa-plus" id="filter__plus"></i>
                         </div>
                       </div>
                    </div>
                   
                </div> --}}
                <div class="filter-search"> <p id="cerca-filtri" class="service-cerca">Filtra ricerca</p></div>
            </div>
        </div>
            <div class="search__resoults__apartment">
                <div class="search__resoults__apartment-cards" id="sponsor">
                   
                </div>
                <div class="search__resoults__apartment-cards" id="no-sponsor">
                    
                </div>
            </div>
        </div>
        {{-- MAPPA --}}
        <div class="container__search-right" id="map"></div>
        
    </div>

        {{-- SCRIPT HANDLEBARS --}}
        <script id="handlebars_cards" type="text/x-handlebars-template">
        <div class="search__resoults__apartment-cards-content sponsor-@{{sponsor}}">
        <div class="search__resoults__apartment-cards-content-slider" data-id="@{{dataId}}">
                    <div class="search__resoults__apartment-cards-content-slider-icons search__resoults__apartment-cards-content-slider-icons-left arrow-slider-dx">
                        <i class="fas fa-chevron-left"></i>
                    </div>
                    <div class="search__resoults__apartment-cards-content-slider-icons search__resoults__apartment-cards-content-slider-icons-right arrow-slider-sx">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                   

                </div>    
                  
             <div class="search__resoults__apartment-cards-content__text">
                <p class="search__resoults__apartment-cards-content-city">
                    @{{ city }}
                </p>
                <p class="search__resoults__apartment-cards-content-description">
                    @{{ title }}
                </p>
                <ul class="details-recap"><li> Prezzo: @{{ price }}</li> <li> Mq: @{{ mq }}</li> <li> Stanze: @{{ rooms }}</li> <li> Posti letto: @{{ beds }}</li> <li> Bagni: @{{ bathrooms }}</li></ul>
                <div class="services-icons" serv-id="@{{dataId}}">
                   
                </div>
                <form action="{{route('view.store')}}" class="search__resoults__apartment-cards-content-form" method="POST">
                    @csrf
                    @method('POST')
                    @{{{id}}}
                    <button type="submit" class="visualizza-appartamneto">Vai all'appartamento</button>
                </form> 
              </div>
              
            </div>
        </script>
    <script src="{{ asset('js/map.js')}}"></script>
@endsection

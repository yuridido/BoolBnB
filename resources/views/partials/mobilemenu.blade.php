<div class="mobile-menu hidden">
    <ul class="mobile-menu-list">
       
            @guest
                <li class="mobile-menu-list-item">
                    <a class="mobile-menu-list-item-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="mobile-menu-list-item">
                        <a class="mobile-menu-list-item-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                 <li class="mobile-menu-list-item">
                    <input id="nav_user-id" type="hidden" value="{{ Auth::user()->id }}">
                    <a href="{{ route('messages.index') }}" class="mobile-menu-list-item-link"><span id="msg-mobile" class="msg-msg">Messaggi</span></a>
                </li>
                <li class="mobile-menu-list-item">
                    <a href="{{ route('host.index') }}" class="mobile-menu-list-item-link">I tuoi appartamenti</a>
                </li>
                <li class="mobile-menu-list-item">
                    <a href="{{ route('host.create') }}" class="mobile-menu-list-item-link">Nuovo appartamento</a>
                </li>
                <li class="mobile-menu-list-item">

                    <a class=mobile-menu-list-item-link href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                                                                 document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none mobile-menu-list-item-link">
                        @csrf
                    </form>

                </li>
            @endguest
        </ul>
</div>

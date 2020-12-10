<div class="footer__menu-mobile">
    <div class="footer__menu-mobile__home-icon">
       <a href="{{route('home')}}" class="link-mobile"> <i class="fas fa-home {{url()->current() == route('home') ? 'pink-mobile' : ''}}"></i></a>
    </div>
    <div class="hamburger-menu">
        <div class="hamburger-menu-bars">
           <div class="hamburger-menu-bars-top"></div>
           <div class="hamburger-menu-bars-bottom"></div>
        </div>
    </div>
</div>
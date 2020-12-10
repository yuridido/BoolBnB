@extends('layouts.app')

@section('content')
@if (session('status'))
<p class="status-msg">{{ session('status') }}</p>
@endif

    <div class="container-center">
        <h2 class="messaggi">
            @if (count($messages) == 0)
                Non hai messaggi da visualizzare
            @else
                Messaggi
            @endif
        </h2>
        <div class="masssages__wrapper">
            <ul class="message-list">
                @foreach ($messages as $message)
                    <li class="comment">
                        <div class="message-wrap">
                            <div class="apartment-info">
                                <a class="apt-link link_mess" href="{{ route('host.show', $message->apartment->id) }}">
                                    <img class="message-info" src="{{ asset('storage/' . $message->apartment->images[0]['path']) }}"
                                        loading="lazy" class="lazyload-gravatar" alt="Apartment ">
                                </a>
                            </div>
                            <div class="message-body">
                                <div class="message-author-wrap">

                                    <div class="message-author">{{ $message->name . ' ' . $message->lastname }}</div>
                                    <span class="message-time">
                                        <i class="fas fa-envelope"></i> {{ $message->email }}
                                    </span>
                                    <div class="message-time">
                                        <i class="fas fa-clock"></i> {{ $message->created_at }}
                                    </div>

                                </div>
                                <div class="message-content">
                                    <i class="fas fa-comment-alt"></i>
                                    <p class="message-user">{{ $message->message }}</p>
                                </div>
                            </div>
                            <div class="message-actions">
                                <div class="delete">
                                    <form action="{{ route('messages.update', $message->id_msg) }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn-delete" type="submit">
                                            @if ($message->read == 0)
                                                <i class="fas fa-envelope"></i>
                                            @else
                                                <i class=" fas fa-envelope-open"></i>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('messages.destroy', $message->id_msg) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-delete" type="submit"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

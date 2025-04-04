@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard de {{ $client->name }}</h1>
        <p><a href="{{ $client->instagram_url }}" target="_blank">Voir le compte Instagram</a></p>
    </div>
@endsection

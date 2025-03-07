<x-layout>
<h1>{{$api->name}}</h1>
<p>{{$api->description}}</p>
<h1>{{$api->token}}<</h1>
<!--<a href="{{ route('APIs.edit', $api->id) }}">Edit</a>-->
<form method="post" action="{{ route('APIs.destroy', $api)}}">
    @csrf
    @method('DELETE')
    <button>Delete</button>
</form>

</x-layout>

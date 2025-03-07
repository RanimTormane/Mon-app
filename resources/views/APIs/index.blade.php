
<x-layout>


<a href="{{route('APIs.create')}}">New API</a>
<!--display the data-->
<!--@foreach ($apis as $api )


    <h2><a href="{{route('APIs.show' ,$api->id)}}">{{($api->name) }}</a> </h2>
    <p>{{($api->description)}}</p>
    <h2>{{($api->token)}}</h2>

@endforeach-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List des APIs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Token</th>
                    <th>status</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($apis as $api)
                    <tr>
                        <td>{{ $api->id }}</td>
                        <td>{{($api->name) }}</td>
                        <td>{{ $api->description }}</td>
                        <td>{{ $api->token }}</td>
                        <td> <a href="{{ route('APIs.updateStatus', $api->id) }}" 
                            class="btn {{ $api->status ? 'btn-success' : 'btn-danger' }}">
                                {{ $api->status ? 'Active' : 'Inactive' }}
                            </a>
                        </td> 
                        <td><a href="{{ route('APIs.edit', $api->id) }}">Edit</a></td>
                        <td><a href="{{ route('APIs.destroy', $api->id) }}">Delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

@if($apis->isEmpty())
<div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
    <p class="text-muted fs-3">
   No API found!</p>
@endif

</x-layout>

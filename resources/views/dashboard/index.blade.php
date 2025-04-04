<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List des APIs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body>


    <div class="main-container">
       <x-menu/>

        <!-- Contenu Principal -->
        <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

            

          
        <form >
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nom du Client</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="instagram_url" class="form-label">URL Instagram</label>
        <input type="url" id="instagram_url" name="instagram_url" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Client</button>
</form>

            <!-- Message si aucune API n'est trouvée -->
             @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        </div>
        <ul class="list-group">
            @foreach($clients as $client)
                <li class="list-group-item">
                    <a href="{{ route('dashboard.client', $client->id) }}">{{ $client->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div id="app"></div>
    <!-- Scripts -->
   



   
</body>
</html>


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
            <a href="{{ route('APIs.create') }}" class="btn btn-primary mb-3">New API</a>

            <!-- Tableau des APIs -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Token</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apis as $api)
                        <tr>
                            <td>{{ $api->id }}</td>
                            <td>{{ $api->name }}</td>
                            <td>{{ $api->description }}</td>
                            <td>{{ $api->token }}</td>
                            <td>
                                <a href="{{ route('APIs.updateStatus', $api->id) }}" 
                                   class="btn {{ $api->status ? 'btn-success' : 'btn-danger' }}">
                                    {{ $api->status ? 'Active' : 'Inactive' }}
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('APIs.edit', $api->id) }}" class="btn btn-primary me-2">Edit</a>
                                    <button onclick="confirmDelete({{ $api->id }})" class="btn btn-danger">Delete</button>
                                </div>
                                <form id="delete-form-{{ $api->id }}" action="{{ route('APIs.destroy', $api->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Message si aucune API n'est trouvée -->
            @if($apis->isEmpty())
                <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
                    <p class="text-muted fs-3">No API found!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                icon: 'error',
                title: 'Are you sure?',
                text: 'Do you really want to delete this API? This action is irreversible and may affect connected services.',
                showCancelButton: true,
                confirmButtonColor: '#ff3e6c',
                cancelButtonColor: '#b1b1b1',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    @if(session('alert'))
        <script>
            Swal.fire({
                icon: '{{ session('alert-type') }}',
                title: '{{ session('alert') }}',
                showConfirmButton: false,
                timer: 1000
            });
        </script>
    @endif
</body>
</html>
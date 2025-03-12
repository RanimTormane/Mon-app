<x-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        
    </head>
    <body>
        
 
    <h1>Google Analytics Data</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sessions</th>
                <th>Users</th>
                <th>Page Views</th>
            </tr>
        </thead>
        <tbody>
            <!-- if there are a fool data -->
        @if(is_array($data) && count($data) > 0)
    @foreach($data as $row)
        <tr>
            @foreach($row as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>
    @endforeach
@else
    <tr><td colspan="4">Aucune donnée disponible.</td></tr>
@endif

        </tbody>
    </table>
 </body>
</html>
</x-layout>

<x-layout>
<h1>Google Analytics Data</h1>

<table>
    <tr>
        <th>Date</th>
        <th>Sessions</th>
        <th>Users</th>
        <th>Page Views</th>
    </tr>
    @foreach($data as $row)
        <tr>
            @foreach($row as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
</x-layout>


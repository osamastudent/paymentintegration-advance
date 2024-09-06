<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<table class="table">
    <thead>
    <th>Name</th>
    <th>Email</th>
    <th>Name</th>
    </thead>
<tbody>
@foreach($userValues as $values)
            <tr>
                <td>{{ $values['name'] }}</td>
                <td>{{ $values['email'] }}</td>
                <td>{{ $values['name'] }}</td>
            </tr>
        @endforeach
</tbody>
</table>
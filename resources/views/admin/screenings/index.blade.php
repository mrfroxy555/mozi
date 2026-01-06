@extends('layouts.app')

@section('title', 'Vetítések kezelése')

@section('content')
<h1>📅 Vetítések kezelése</h1>

<a href="{{ route('admin.screenings.create') }}" class="btn">+ Új vetítés</a>

<table style="width: 100%; margin-top: 2rem;">
    <thead>
        <tr>
            <th>Film</th>
            <th>Terem</th>
            <th>Kezdés</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach($screenings as $screening)
            <tr>
                <td>{{ $screening->movie->title }}</td>
                <td>{{ $screening->cinema->name }}</td>
                <td>{{ $screening->start_time->format('Y.m.d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.screenings.edit', $screening) }}" class="btn btn-small btn-secondary">Szerkesztés</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $screenings->links() }}
@endsection
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Status</th>
            <th>Booked By</th>
        </tr>
    </thead>

    <tbody>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->title }}</td>
            <td>{{ $b->description }}</td>
            <td>{{ $b->booking_date }}</td>
            <td>{{ ucfirst($b->status) }}</td>
            <td>{{ $b->user->name ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

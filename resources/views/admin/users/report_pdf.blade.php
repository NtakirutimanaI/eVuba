@extends('layouts.pdf')

@section('content')
<div style="margin-bottom: 20px;">
    <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 5px;">User Intelligence Report</h3>
    <div style="text-align: center; font-size: 11px; color: #64748b;">
        <strong>Generated:</strong> {{ now()->format('M d, Y, h:i A') }} | 
        <strong>Total Users:</strong> {{ count($users ?? []) }}
    </div>
</div>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th style="width: 25%;">User Name</th>
            <th style="width: 30%;">Email Address</th>
            <th style="width: 15%;">Role</th>
            <th style="width: 10%; text-align: center;">Status</th>
            <th style="width: 15%;">Registered Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $index => $user)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span style="text-transform: capitalize;">{{ $user->role ?? 'User' }}</span>
                </td>
                <td style="text-align: center;">
                    @if($user->is_active ?? true)
                        <span style="color: green; font-weight: bold;">Active</span>
                    @else
                        <span style="color: red; font-weight: bold;">Inactive</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center; padding: 20px;">No users found for this period.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="summary-box">
    <div class="summary-row" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: bold; text-align: center;">REPORT SUMMARY</div>
    <div class="summary-row">
        <span class="summary-label">Total Users:</span>
        <span class="summary-val">{{ count($users ?? []) }}</span>
    </div>
    <div class="summary-row">
        <span class="summary-label">Active Users:</span>
        <span class="summary-val">{{ $users->where('is_active', true)->count() }}</span>
    </div>
</div>
@endsection

@include('layouts.header')
@include('layouts.sidebar')

<style>
/* ================= YOUR CSS ================= */
body {
    background: #f4f6f8;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    font-size: 12px;
}

.inventory-container {
    width: 500px; /* slightly wider for table */
    margin: 50px auto; /* centers horizontally with top margin */
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0,0,0,0.1);
    font-size: 12px;
    position: relative;
}

.header-title {
    font-size: 16px;
    margin-top: 0;
    margin-bottom: 15px;
    color: #2c3e50;
    font-weight: 600;
    text-align: center; /* center the title */
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.table th, .table td {
    border: 1px solid #ccc;
    padding: 8px 12px;
    text-align: left;
    font-size: 12px;
}

.table th {
    background-color: #f2f2f2;
    width: 35%;
}

.add-btn {
    background: #007bff;
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: inline-block;
    font-size: 12px;
    text-decoration: none;
    text-align: center;
}

.add-btn:hover {
    background: #0069d9;
}

/* Close Button (optional if needed) */
.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 14px;
    line-height: 24px;
    text-align: center;
    font-weight: bold;
}
.close-btn:hover {
    background: #c82333;
}
</style>

<div class="inventory-container" style="margin-top:90px;">
    <!-- Optional Close Button -->
    <button type="button" class="close-btn" onclick="window.history.back();">&times;</button>

    <h4 class="header-title">User Details: {{ $user->name }}</h4>

    <table class="table">
        <tr><th>Name</th><td>{{ $user->name }}</td></tr>
        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
        <tr><th>Role(s)</th><td>{{ $user->roles->pluck('name')->implode(', ') }}</td></tr>
        <tr><th>Status</th><td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td></tr>
        <tr><th>Created At</th><td>{{ $user->created_at->format('Y-m-d H:i') }}</td></tr>
        <tr><th>Updated At</th><td>{{ $user->updated_at->format('Y-m-d H:i') }}</td></tr>
    </table>

    <a href="{{ route('admin.users.index') }}" class="add-btn">Back to Users List</a>
</div>



@include('layouts.header')
@include('layouts.sidebar')

<div class="page-center-container">
    <div class="crm-table-container">
        <h1>Book a Service</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('customer.bookings.store') }}" method="POST">
            @csrf

            <!-- Select Service -->
            <div class="mb-3">
                <label for="service_id">Select Service</label>
                <select name="service_id" id="service_id" required>
                    <option value="">-- Select Service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} (Employee: {{ $service->employee->name }})
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="alert alert-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Booking Date -->
            <div class="mb-3">
                <label for="booking_date">Booking Date</label>
                <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}" required>
                @error('booking_date')
                    <div class="alert alert-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-success">Book Now</button>
        </form>
    </div>
</div>

<style>
.page-center-container { display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; background-color: #f0f2f5; padding: 20px; }
.crm-table-container { width: 90%; margin-left: 220px; max-width: 700px; background-color: #fff; padding: 20px 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
.crm-table-container h1 { text-align: center; margin-bottom: 20px; color: #333; font-size: 24px; }
.alert { margin-bottom: 15px; padding: 10px 12px; border-radius: 6px; font-size: 13px; }
.alert-success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; }
.alert-error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
.mb-3 { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; font-weight: bold; }
input[type="date"], select { width: 100%; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; font-size: 13px; }
.btn { padding: 6px 12px; border-radius: 4px; border: none; background-color: #28a745; color: white; cursor: pointer; font-size: 13px; }
.btn:hover { background-color: #218838; }
</style>

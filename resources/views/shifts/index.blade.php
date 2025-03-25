@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Shifts</h1>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

   

    <div class="table-responsive">
        <table id="shiftsTable" class="table table-striped table-bordered display" style="width:100%">
            <thead class="bg-blue-200">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Time Open</th>
                    <th>Time Close</th>
                    <th>Cash In Hand</th>
                    <th>Cash Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shifts as $shift)
                <tr>
                    <td>{{ $shift->id }}</td>
                    <td>{{ $shift->user->name }}</td>
                    <td>{{ $shift->time_open }}</td>
                    <td>{{ $shift->time_close ?? 'N/A' }}</td>
                    <td>{{ $shift->cash_in_hand }}</td>
                    <td>{{ $shift->cash_submitted }}</td>
                    <td>
                       
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#shiftsTable').DataTable({
            "order": [[0, "asc"]], // Default sorting by ID ascending
            "paging": true,
            "searching": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10
        });
    });
</script>
@endsection
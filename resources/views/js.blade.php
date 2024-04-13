@if($view == "user-list")
        <script>
        var table = $('#listTable').DataTable({

        processing:true,
        serverSide:true,
        ajax: "{{ route('user.list') }}",
        order: [[ 1, "desc" ]],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {data:'action', name: 'action', orderable: false, searchable: false},
            {data:'name', name: 'name'},
            {data:'username', name: 'username'},
            {data:'email', name: 'email'},
            {data:'no_hp', name: 'no_hp'},
            {data:'level', name: 'level'},
            {data:'is_active', name: 'is_active'}
        ]
        });
    </script>
@endif
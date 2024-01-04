@extends('adminlte::page')

@section('content')
    <div class="mb-3 pt-4">
        <button class="btn-sm btn-primary" data-toggle="collapse" data-target="#createCollapse"><i class="fas fa-plus"></i>
            Create New Certificate</button>
    </div>

    <div class="collapse multi-collapse" id="createCollapse">
        <div class="container">
            <div id="create_success"></div>
            <div id="create_failed"></div>
            <x-adminlte-card class="mt-4" title="Generate New Certificate for User">
                <x-adminlte-select id="generate" name="generate" label="Select User" igroup-size="sm">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->id }} - {{ $user->name }}</option>
                    @endforeach
                </x-adminlte-select>
                <x-adminlte-button type="submit" label="Generate" theme="primary" onclick="generate()" />
            </x-adminlte-card>
        </div>
    </div>

    <x-adminlte-card title="User Certificate">
        <table class="table table-striped table-hover responsive" id="certificates">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Certificate</th>
                    <th>#</th>
                </tr>
            </thead>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script>
        const token = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            $('#certificates').DataTable({
                responsive: true,
                paginate: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('datatable.certificates') }}',
                order: [
                    [0, 'asc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'public_certificate_path',
                        name: 'public_certificate_path',
                        render: function(data, type, row) {
                            var certificate = data.split('/').pop();
                            return '<p class="text-info">' + certificate + '</p>';
                        }
                    },
                    {
                        data: 'id',
                        name: 'id',
                        render: function(data, full) {
                            return '<button class="btn btn-xs btn-success" onclick="downloadCertificate(' +
                                data + ')">Download</button>';
                        }
                    },
                ]
            });
        });

        function downloadCertificate(id) {
            var downloadUrl = '/certificates/download/' + id;
            window.open(downloadUrl);
        }

        function generate() {
            var userId = $('#generate').val();
            $.ajax({
                url: '/certificates/generate/' + userId,
                type: 'POST',
                data: {
                    _token: token
                },
                success: function(response) {
                    var successMessage = document.createElement(
                        "div");
                    successMessage.className =
                        "alert alert-success";
                    successMessage.textContent = response.success;
                    $('#create_success').html(successMessage);
                    $('#certificates').DataTable().ajax.reload();
                    setTimeout(function() {
                        successMessage.remove();
                    }, 5000);
                },
                error: function(xhr, error, status) {
                    var errorMessage = document.createElement(
                        "div");
                    errorMessage.className = "alert alert-danger";
                    errorMessage.textContent = xhr.responseJSON.error;
                    $('#create_failed').html(errorMessage);
                    setTimeout(function() {
                        errorMessage.remove();
                    }, 5000);
                }
            });
        }
    </script>
@endsection

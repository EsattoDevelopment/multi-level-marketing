@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista para aprovar documentação
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Aprovação documento</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="notifications top-right"></div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_index" class="table table-striped responsive">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>E-mail</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{!! route('user.index.json.aprovacao.doc') !!}',
                columns: [
                    {data: 'id', name: 'u.id'},
                    {data: 'name', name: 'u.name'},
                    {data: 'cpf', name: 'u.cpf'},
                    {data: 'email', name: 'u.email'},
                    {data: 'status_cpf', name: 'status_cpf'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, "desc"]]
            });

            $("body").on('click', '.aprovaDoc, .recusaDoc', function(){
                var id = $(this).data('id');
                var action = $(this).attr('class').indexOf('aprovaDoc');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('user.aprovar.doc') }}',
                    data: {"_token": "{{ csrf_token() }}", "action": (action > 0 ? "aprovaDoc" : 'recusaDoc'), "id": id},
                    dataType: 'json',
                    success: function(response) {
                        $(".top-right").notify({
                            message: { html: '<i class="fa fa-remove"></i> Documentação <b>'+ response.action +'</b> com sucesso!' },
                            type: 'success'
                        }).show();
                        table.ajax.reload();
                    },
                    error: function(){
                        $(".top-right").notify({
                            message: { html: '<i class="fa fa-remove"></i> Nao foi possivel completar sua requisicao.' },
                            type: 'error'
                        }).show();
                        table.ajax.reload();
                    }
                });

                return false;
            });
        })
    </script>
@endsection
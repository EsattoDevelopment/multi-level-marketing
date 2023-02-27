@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>Configurações de empréstimo</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Configurações de empréstimo</li>
        </ol>
    </section>
    <section class="content">
        @include('default.errors.errors')
        <div class="box box-primary col-md-12">
            <table id="tabela" class="table table-bordered table-striped dataTable no-footer dtr-inline">
                @if(sizeof($emprestimos) === 0)
                    <p class="text-center" style="padding: 32px 0">
                        Nenhum empréstimo encontrado.
                    </p>
                @else
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Valor</th>
                            <th>Chave Pix</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emprestimos as $key => $emprestimo)
                            <tr class="emprestimo" id="emprestimo-{{ $emprestimo->id }}" data-id="{{ $emprestimo->id }}">
                                <td>{{ $emprestimo->id }}</td>
                                <td>{{ mascaraMoeda($sistema->moeda, $emprestimo->valor, 2, true) }}</td>
                                <td>{{ $emprestimo->chave_pix }}</td>
                                <td>
                                    <select class="form-control emprestimo-select" name="status" id="status-emprestimo-{{ $emprestimo->id }}">
                                        <option value="PEDIDO_REALIZADO" @if($emprestimo->status === 'PEDIDO_REALIZADO') selected @endif>Pedido Realizado</option>
                                        <option value="PAGO" @if($emprestimo->status === 'PAGO') selected @endif>Pago</option>
                                        <option value="CANCELADO" @if($emprestimo->status === 'CANCELADO') selected @endif>Cancelado</option>
                                        <option value="ESTORNADO" @if($emprestimo->status === 'ESTORNADO') selected @endif>Estornado</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}">

    <style>
        .emprestimo {
            width: 100%;
            border-top: 1px solid #ccc;
            padding: 0 16px;
            transition: all .2s ease;
        }
        .emprestimo > td, th {
            padding: 16px;
        }
        .emprestimo > td:first-child, th:first-child {
            width: 100px;
        }
        .emprestimo > td:nth-last-child(1) > button {
            opacity: 0;
            margin: 0 4px;
            transition: opacity .2s ease;
        }
        .emprestimo > td:nth-last-child(1):focus-within > button {
            opacity: 1;
        }
        .emprestimo:hover > td:nth-last-child(1) > button {
            opacity: 1;
        }
        .emprestimo:hover {
            background-color: #c0c0c0 !important;
        }
    </style>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>

    <script src="{{ asset('plugins/sweetalert/sweetalert2.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        const setup = function () {
            const tabela = $('#tabela').DataTable({ order: [[1, 'desc']] })

            $('.emprestimo').on('change', '.emprestimo-select', function (event) {
                const id = event.target.id.replace(/\D/g, '')
                const status = event.target.value
                $.ajax({
                    url: '{{ route('emprestimos.atualizar-status') }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id,
                        status,
                    },
                    success: () => {
                        swal('Atualizado com sucesso.')
                    },
                    error: (error) => {
                        console.error(error)
                        swal('Erro!', 'Não foi possível alterar o status do empréstimo', 'error')
                    }
                })
            })
        }
        $(setup)
    </script>
@endsection

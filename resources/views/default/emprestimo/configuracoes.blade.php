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
        <div class="row">
            <ul class="col-md-12" style="list-style: none;">
                @foreach($configuracoes_emprestimos as $grupo => $lista_parcelas)
                    <li class="box box-primary">
                        <h3 style="margin: 16px;">{{ $grupo }}</h3>
                        <table id="tabela-{{ $grupo }}" class="table table-bordered table-striped dataTable no-footer dtr-inline" data-without-paging="true">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Acréscimo %</th>
                                    <th>Acréscimo fixo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($lista_parcelas as $parcela)
                                <tr class="parcela" id="parcela-{{ $parcela['id'] }}" data-id="{{ $parcela['id'] }}">
                                    <td>{{ $parcela['id'] }}</td>
                                    <td>
                                        <span id="label_nome_{{ $parcela['id'] }}" style="display: block;">{{ $parcela['nome'] }}</span>
                                        <input type="text" value="{{ $parcela['nome'] }}" id="input_nome_{{ $parcela['id'] }}" style="display: none;" />
                                    </td>
                                    <td>
                                        <span id="label_valor_porcentagem_{{ $parcela['id'] }}" style="display: block;">{{ $parcela['valor_porcentagem'] }} %</span>
                                        <input type="number" value="{{ $parcela['valor_porcentagem'] }}" id="input_valor_porcentagem_{{ $parcela['id'] }}" style="display: none;" />
                                    </td>
                                    <td>
                                        <span id="label_valor_fixo_{{ $parcela['id'] }}" style="display: block;">{{ mascaraMoeda($sistema->moeda, $parcela['valor_fixo'], 2, true) }}</span>
                                        <input type="number" value="{{ $parcela['valor_fixo'] }}" id="input_valor_fixo_{{ $parcela['id'] }}" style="display: none;" />
                                    </td>
                                    <td>
                                        <button class="btn btn-info" data-is-editing="false"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        <button class="btn btn-success"><i class="fa fa-plus"></i></button>
                                    </td>
                            @endforeach
                            </tbody>
                        </table>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}">
    <style>
        .parcela {
            width: 100%;
            border-top: 1px solid #ccc;
            padding: 0 16px;
            transition: all .2s ease;
        }
        .parcela:hover {
            background-color: #c0c0c0 !important;
        }
        .parcela > td, th {
            padding: 16px;
        }
        .parcela > td:first-child, th:first-child {
            width: 100px;
        }
        .parcela > td:nth-last-child(1) > button {
            opacity: 0;
            margin: 0 4px;
            transition: opacity .2s ease;
        }
        .parcela:hover > td:nth-last-child(1) > button {
            opacity: 1;
        }
        .parcela > td:nth-last-child(1):focus-within > button {
            opacity: 1;
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
        const setup = () => {
            const parcelas = document.querySelectorAll('.parcela')
            const getParcelaElements = (parcela) => {
                const parcelaId = parcela.getAttribute('data-id')
                const elements = {
                    parcelaId,
                    editButton: parcela.querySelector('.btn-info'),
                    deleteButton: parcela.querySelector('.btn-danger'),
                    addButton: parcela.querySelector('.btn-success'),
                    nomeLabel: parcela.querySelector(`#label_nome_${parcelaId}`),
                    nomeInput: parcela.querySelector(`#input_nome_${parcelaId}`),
                    valorPorcentagemLabel: parcela.querySelector(`#label_valor_porcentagem_${parcelaId}`),
                    valorPorcentagemInput: parcela.querySelector(`#input_valor_porcentagem_${parcelaId}`),
                    valorFixoLabel: parcela.querySelector(`#label_valor_fixo_${parcelaId}`),
                    valorFixoInput: parcela.querySelector(`#input_valor_fixo_${parcelaId}`),
                }
                if (elements.editButton === null || elements.deleteButton === null || elements.addButton === null) return null
                return elements
            }
            const endEditMode = (parcelaElements) => {
                const buttonIcon = document.createElement('i')
                buttonIcon.classList.add('fa')
                buttonIcon.classList.add('fa-edit')
                parcelaElements.editButton.setAttribute('data-is-editing', 'false')
                parcelaElements.editButton.replaceChildren(buttonIcon)
                parcelaElements.nomeLabel.style.display = 'block'
                parcelaElements.nomeInput.style.display = 'none'
                parcelaElements.valorPorcentagemLabel.style.display = 'block'
                parcelaElements.valorPorcentagemInput.style.display = 'none'
                parcelaElements.valorFixoLabel.style.display = 'block'
                parcelaElements.valorFixoInput.style.display = 'none'
            }
            const beginEditMode = (parcelaElements) => {
                parcelas.forEach((outraParcela) => {
                    const outraParcelaElements = getParcelaElements(outraParcela)
                    if (outraParcelaElements === null) return
                    endEditMode(outraParcelaElements)
                })
                const buttonIcon = document.createElement('i')
                buttonIcon.classList.add('fa')
                buttonIcon.classList.add('fa-save')
                parcelaElements.editButton.setAttribute('data-is-editing', 'true')
                parcelaElements.editButton.replaceChildren(buttonIcon)
                parcelaElements.nomeLabel.style.display = 'none'
                parcelaElements.nomeInput.style.display = 'block'
                parcelaElements.valorPorcentagemLabel.style.display = 'none'
                parcelaElements.valorPorcentagemInput.style.display = 'block'
                parcelaElements.valorFixoLabel.style.display = 'none'
                parcelaElements.valorFixoInput.style.display = 'block'
            }
            parcelas.forEach((parcela) => {
                const parcelaElements = getParcelaElements(parcela)
                if (parcelaElements === null) return
                parcelaElements.editButton.addEventListener('click', () => {
                    const isEditing = parcelaElements.editButton.getAttribute('data-is-editing')
                    if (isEditing === 'true') endEditMode(parcelaElements)
                    else beginEditMode(parcelaElements)
                })
                parcelaElements.deleteButton.addEventListener('click', () => {
                    swal({
                        title: `Realmente deseja remover a ${parcela.id}?`,
                        text: 'Esta operação não poderá ser desfeita.',
                        type: 'warning',
                        showCancelButton: true,
                        allowOutsideClick: true,
                        confirmButtonText: 'Remover',
                        confirmButtonColor: '#e64942',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.dismiss) return
                        if (result.value) {
                            swal({
                                title: 'Não implementado ainda',
                            })
                        }
                    })
                })
                parcelaElements.addButton.addEventListener('click', () => {})
            })
        }
        $(setup)
    </script>
@endsection

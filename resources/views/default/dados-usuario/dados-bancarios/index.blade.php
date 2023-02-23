@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Dados Bancários
            <br>
            <small class="text-red">As contas somente serão avaliadas após o envio dos documentos de identidade.</small>
            <div class="pull-right">
                <a href="{{ route('dados-usuario.dados-bancarios-create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> CADASTRAR CONTA</a>
            </div>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="notifications top-right"></div>
        @include('default.errors.errors')
        <div class="row">
            @foreach($contas as $conta)
                <div class="col-md-4">
                    <div class="box box-default">
                        <div class="box-body box-profile">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header" style="margin-top: 0; margin-bottom: 5px;">
                                        <i class="fa fa-bank"></i> {{ $conta->bancoReferencia->nome }}
                                    </h2>
                                    <div id="box_bank{{ $conta->id }}" data-id="{{ $conta->id }}" style="border-bottom: 1px solid #eee; margin-bottom: 5px;">
                                        @if($conta->status_comprovante == 'validado')
                                            <p><label class="label label-success"><i class="fa fa-check"></i> VERIFICADO</label></p>
                                        @elseif($conta->status_comprovante == 'em_analise')
                                            <p><label class="label label-warning"><i class="fa fa-clock-o"></i> EM ANÁLISE</label></p>
                                        @elseif($conta->status_comprovante == null || $conta->status_comprovante == 'recusado')
                                            @if($conta->status_comprovante == 'recusado')
                                                <p><label class="label label-danger"><i class="fa fa-remove"></i> RECUSADO</label></p>
                                            @else
                                                <label class="label label-danger"><i class="fa fa-remove"></i> NÃO VERIFICADA</label>
                                            @endif
                                            <p>
                                                Envie uma foto do seu cartão ou comprovante que contenha informações da conta.
                                                <button id="upload_bank{{ $conta->id }}" class="btn btn-xs btn-primary"><i class="fa fa-upload"></i> ENVIAR COMPROVANTE</button>
                                            </p>
                                            <div id="progress_bank{{ $conta->id }}" class="progress active" style="display: none;">
                                                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding-bottom: 5px; border-bottom: 1px solid #eee; margin-bottom: 10px;">
                                <div class="col-xs-4 col-sm-4">
                                    <strong>Banco</strong><br>{{ $conta->bancoReferencia->codigo }}
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <strong>Agência</strong><br>{{ $conta->agencia }}-{{ $conta->agencia_digito }}
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <strong>Conta</strong><br>{{ $conta->conta }}-{{ $conta->conta_digito }}
                                </div>
                            </div>
                            <button data-toggle="modal" data-target="#conta{{ $conta->id }}" class="btn btn-danger btn-block"><i class="fa fa-remove"></i> <b>Deletar</b></button>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="conta{{ $conta->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Atenção</h4>
                            </div>
                            <div class="modal-body">
                                <p>Tem certeza que deseja excluir sua conta?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Não</button>
                                <form method="post" action="{{ route('dados-usuario.dados-bancarios-destroy', $conta->id) }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-primary">Sim</button>
                                </form>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
            @endforeach
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    
    <script type="text/javascript">
        $(function () {
            @if(!Auth::user()->validado)
            swal({
                    title: "Aviso",
                    html: true,
                    text: "<p class='text-red'>As contas somente serão avaliadas após o envio dos documentos de identidade.</p>",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#00ff00",
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }
            );
            @endif

            $('div[id*="box_bank"]').each(function(){
                var id_bank = $(this).data('id');

                if(! $("#upload_bank" + id_bank).length) return;

                $("div#box_bank" + id_bank).dropzone({
                    url: '{{ route('dados-usuario.dados-bancarios-comprovante') }}',
                    headers: {
                        "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                    },
                    method: 'POST',
                    params: {
                        bank: id_bank
                    },
                    paramName: 'comprovante',
                    maxFiles: 1,
                    resizeWidth: 800,
                    resizeQuality: 0.6,
                    acceptedFiles: ".jpeg,.jpg,.png",
                    clickable: "#upload_bank" + id_bank,
                    addedfile: function(file) {
                        $("#progress_bank" + id_bank).show();
                        $("#upload_bank" + id_bank).hide();
                    },
                    totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                        $("#progress_bank" + id_bank + " > .progress-bar").css({'width': totalUploadProgress + "%"});
                    },
                    complete: function(file) {
                        $("#progress_bank" + id_bank).hide();
                        $("#progress_bank" + id_bank + " > .progress-bar").css({'width': "0%"});
                        $("#upload_bank" + id_bank).show();
                    },
                    error: function(errorMessage) {
                        $(".top-right").notify({
                            message: { html: '<i class="fa fa-error"></i> Erro ao enviar seu comprovante, tente novamente. '},
                            type: 'error'
                        }).show();
                    },
                    success: function(file, retorno) {
                        if (retorno.success) {
                            $("div#box_bank" + id_bank).html(`<p><label class="label label-warning"><i class="fa fa-clock-o"></i> EM ANÁLISE</label></p>`);
                        }
                        $(".top-right").notify({
                            message: { html: '<i class="fa fa-check"></i> ' + retorno.msg},
                            type: retorno.flash
                        }).show();
                    }
                });
            });
        })
    </script>
@endsection


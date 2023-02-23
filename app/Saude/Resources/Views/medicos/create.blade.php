@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('saude.medicos.store') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Medicos</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="nome">Nome</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                       placeholder="Nome">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="name">CRM</label>
                                <input type="text" name="crm" value="{{ old('crm') }}" class="form-control"
                                       placeholder="CRM">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="name">Telefone</label>
                                <input type="text" name="telefone1" value="{{ old('telefone1') }}" class="form-control"
                                       placeholder="Telefone">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="name">Celular</label>
                                <input type="text" name="telefone2" value="{{ old('telefone2') }}" class="form-control"
                                       placeholder="Celular">
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Clinicas</label>
                                <select class="form-control" multiple id="clinicas" name="user_id[]">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Especialidades</label>
                                <select class="form-control select2" multiple id="especialidade" name="especialidade[]">
                                    @foreach($especialidades as $especialidade)
                                        <option value="{{ $especialidade->id }}">{{ $especialidade->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('saude.medicos.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements

            $(".select2").select2();

            $("#clinicas").select2();
        });
    </script>
@endsection
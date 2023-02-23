@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('saude.dependentes.store', $usuario->id) }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Dependentes @if(isset($usuario)) para
                                <b>{{ $usuario->name }}</b> @endif</h3>
                        </div><!-- /.box-header -->


                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="status">Ativo</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary @if(!old('status')) active @endif {{ old('status') == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" @if(!old('status')) checked
                                               @endif {{ old('status') == 1 ? 'checked' : ''  }} name="status"
                                               autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('status') === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0"
                                               {{ old('status') === 0 ? 'checked' : ''  }} name="status"
                                               autocomplete="off">Não
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="name">Nome</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                       placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="sexo">Sexo</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary @if(!old('sexo')) active @endif {{ old('sexo') == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" @if(!old('sexo')) checked
                                               @endif {{ old('sexo') == 1 ? 'checked' : ''  }} name="sexo"
                                               autocomplete="off">Masculino
                                    </label>
                                    <label class="btn btn-primary {{ old('sexo') === 2 ? 'active' : ''  }}">
                                        <input type="radio" value="2"
                                               {{ old('sexo') === 2 ? 'checked' : ''  }} name="sexo" autocomplete="off">Feminino
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="rg">RG</label>
                                <input type="text" name="rg" value="{{ old('rg') }}"
                                       class="form-control" placeholder="RG">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="cpf">CPF</label>
                                <input type="text" name="cpf" value="{{ old('cpf') }}"
                                       class="form-control" placeholder="CPF">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="dt_nasc">Data nascimento</label>
                                <input type="text" name="dt_nasc" value="{{ old('dt_nasc') }}"
                                       class="form-control datepicker" placeholder="Data nascimento">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Parentesco</label>
                                <select class="form-control select2" id="parentesco" name="parentesco"
                                        data-placeholder="Selecione um parentesco" style="width: 100%;">
                                    @if($usuario->conjuge() == 0)
                                        <option value="1" {{ old('parentesco') == 1 ? 'selected' : '' }}>Cônjuge
                                        </option>
                                    @else
                                        <option disabled>Cônjuge (Permitido somente um)</option>
                                    @endif

                                    @if($usuario->filhos() < 10)
                                        <option value="2" {{ old('parentesco') == 2 ? 'selected' : '' }}>Filho</option>
                                    @else
                                        <option disabled>Filho (Valor maximo atingido 10)</option>
                                    @endif
                                    {{--    <option value="3" {{ old('parentesco') == 3 ? 'selected' : '' }}>Pais</option>--}}
                                </select>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <input type="hidden" name="titular_id" value="{{ $usuario->id }}">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            $.fn.datepicker.defaults.language = 'pt-BR';

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });
    </script>
@endsection
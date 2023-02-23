<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

<style>
    .table > thead > tr > th{
        line-height: 100%;
        vertical-align: middle;
    }
</style>
<h1>Lista para deposito</h1>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Nome</th>
        <th>Usuário</th>
        <th>CPF</th>
        <th>Banco</th>
        <th>Agencia</th>
        <th>Agencia D.</th>
        <th>Conta</th>
        <th>Conta D.</th>
        <th>Saldo</th>
        <th>Taxa</th>
        <th>Valor a receber</th>
        <th>T. conta</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dados as $key => $dado)
        <tr>
            <td>{{ $dado->name }}</td>
            <td>{{ $dado->user_id }} - {{ $dado->username }}</td>
            <td>{{ $dado->cpf }}</td>
            <td>{{ $dado->banco_codigo }} - {{ $dado->banco }}</td>
            <td>{{ $dado->agencia }}</td>
            <td>{{ $dado->agencia_digito }}</td>
            <td>{{ $dado->conta }}</td>
            <td>{{ $dado->conta_digito }}</td>
            <td>{{ $sistema->moeda }} {{ $dado->saldo }}</td>
            <td>{{ $sistema->moeda }} {{ $dado->saldo * 0.15 }}</td>
            <td>{{ $sistema->moeda }} {{ $dado->saldo - ($dado->saldo * 0.15) }}</td>
            <td>{{ $dado->tipo_conta == 1 ? 'Corrente' : 'Poupança'  }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
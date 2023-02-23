# Leia-me

Javascript para utlizar o PagSeguro

    Este  primeiro para usar o sandbox
   `<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>`
   
   
   - Este para usar o sweetalert 
   
   ` <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>`

    Utilize essa assets para utilizar a API principal do pagseguro

    <script type="text/javascript"
            src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js">
    </script>

    <script>
        $(function(){
            $('#pagseguro').click(function(){

                swal({
                        title: "Contatando PagSeguro",
                        text: "Solicitando autorização...",
                        type: "info",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        timer: 2000,
                        showConfirmButton: false
                    },
                    function(){
                        var solicitacao =  $.get('{{ route('pagseguro.codigo', $dados->id) }}');

                        //Solicitação de autorização
                        solicitacao.done(function(data){
                            swal.close();
                            var isOpenLightbox = PagSeguroLightbox({
                                code: data
                            }, {
                                success: function (transactionCode) {
                                    var registroPagamento = $.post('{{ route('pedido.usuario.pedido.pagar.pagseguro', [Auth::user()->id, $dados->id]) }}', {metodo_pagamento:2, transactionCode:transactionCode});

                                    registroPagamento.done(function(data){
                                        swal("Pedido pago", "Pagamento registrado no sistema com sucesso!", "success");
                                        $('#pagseguro').remove();
                                        location.href = "{{ route('pedido.usuario.pedidos', Auth::user()->id) }}";
                                    });

                                    registroPagamento.fail(function(data){
                                        swal('Erro!', 'Ocorreu ao registrar o pagamento no sistema. Não se preocupe ela foi registrada pelo PagSeguro e sera registrada posteriormente.', 'error');

                                    });
                                },
                                abort: function () {
                                    swal('Atenção', 'Transaçao cancelada!', 'info');
                                }
                            });

                            // Redirecionando o cliente caso o navegador não tenha suporte ao Lightbox
                            if (!isOpenLightbox) {
                                location.href = "https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=" + code;
                            }
                        }, 'json');

                        //Erro na autorização do pagseguro
                        solicitacao.fail(function () {
                            swal('Erro!', 'Ocorreu ao autorizar o pagamento. Se o erro persistir contate-nos.', 'error');
                        });
                    });

            });
        });
    </script>
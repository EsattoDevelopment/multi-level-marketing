/**
 * Created by GehakaMKT on 06/06/2016.
 * @author José Luiz josejlpp[at]hotmail.com
 * @uses Funções para manipulação de delete e troca de legendas das imagens
 */
$(function(){

    //função utilizada para mudar a legenda da imagem
    $('#hall-imagens').on('click' , 'a[title="Legenda"]',  function(){

        var objLegenda = $(this);
        window.objLegenda = objLegenda;
        window.imagem_id = objLegenda.attr('data-id');
        window.url_legenda = objLegenda.parent().find('input[name="url-legenda"]').val();

        swal({
            title: "Legenda",
            text: objLegenda.attr('data-legenda'),
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: 'Salvar',
            showLoaderOnConfirm: true,
            animation: "slide-from-top",
            inputPlaceholder: "Nova Legenda"
        },
            function(inputValue){
                if (inputValue === false)
                    return false;

                if (inputValue === "") {
                    swal.showInputError("Escreva algo antes de salvar!");
                    return false;
                }

                if (inputValue.length > 50) {
                    swal.showInputError("No máximo 50 caractéres por favor!");
                    return false;
                }

                //requisição
                var post = $.post(window.url_legenda, {imagem : window.imagem_id, legenda: inputValue});

                //Requisição OK (200)
                post.done(function(data){
                    swal("Salvo com sucesso!", "Legenda: " + data.legenda, "success");

                    window.objLegenda.attr('data-legenda', data.legenda);
                    $('#image-' + data.imagem_id + ' .thumbnail img').attr('src', data.url);
                    delete window.objLegenda;
                    delete window.imagem_id;
                    delete window.url_legenda;
                }, 'json');

                //Requisição falha (500)
                post.fail(function(data){
                    swal('Erro!', 'Ocorreu um erro ao salvar a legenda. Por favor contate o administrador do sistema.', 'error');
                }, 'json');

             }, 2000);

    });

    //função utilizada para apagar imagem
    $('#hall-imagens').on('click', 'a[title="Apagar"]', function(){

        //variaveis globais para serem utilizadas dentro do sweetalert
        window.imagem_id = $(this).attr('data-id');
        window.url_imagem = $(this).parent().find('input[name="url-delete"]').val();
        
        swal({   title: "Você tem certeza?",
            text: "Você não poderá desfazer isto!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim, delete a imagem!",
            cancelButtonText: "Não, Cancele por favor!",
            showLoaderOnConfirm: true,
            closeOnConfirm: false,
            closeOnCancel: false
        },
            function(isConfirm){
                if (isConfirm) {

                    //Requisição
                    var post = $.post(window.url_imagem, {imagem : window.imagem_id});

                    //Requisição OK
                    post.done(function(data){

                        $('#image-'+data.id).remove();

                        //retira o botão apagar todas se não houver imagens
                        if($('#hall-imagens .box-img').length == 0)
                            $('#deleteAll').addClass('hidden');

                        //limpa variavel global
                        delete window.imagem_id;
                        delete window.url_imagem;

                        swal("Deletada!", "Sua imagem foi deletada.", "success");

                    }, 'json');

                    //Erro na requisição
                    post.fail(function(data){

                        if(data.responseJSON){
                            swal('Erro!', data.responseJSON.message, "error");
                        }else{
                            swal('Erro!', 'Ocorreu um erro na sua requisição. Por favor contate o administrador do sistema', "error");
                        }


                    }, 'json');
                    
                } else {
                    swal("Cancelado", "Sua imagem esta a salvo :)", "error");
                }
            }, 2000
        );

    });

    //seta a imagem principal
    $('#hall-imagens').on('click', 'a[title="Principal"]', function(){

        //requisição
        var post = $.post($(this).parent().find('input[name="url-principal"]').val());

        //Requisição OK (200)
        post.done(function(data){

            //tira a marcação principal de todas as imagens
            $('.thumbnail').each(function(){
                $(this).removeClass('thumbnail-principal');
            });

            //seta a marcação de principal na imagem
            $('#image-'+data.imagem_id+' .thumbnail').addClass('thumbnail-principal');

            $('section.content').prepend('<div class="initial"><div class="alert alert-'+data.status+'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.message+'</div></div>');
            $('.initial').first().slideDown();

        }, 'json');

        //Requisição falha (500)
        post.fail(function(data){

            if(data.message) {
                $('section.content').prepend('<div class="initial"><div class="alert alert-' + data.status + '"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.message + '</div></div>');
            }else{
                $('section.content').prepend('<div class="initial"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.statusText + '</div></div>');
            }
            $('.initial').first().slideDown();

        }, 'json');

    });

    //deleta todas as imagens
    $('#deleteAll').click(function(){

        window.url_delete_all_img = $(this).attr('data-url');

        swal({   title: "Você tem certeza?",
                text: "Você não poderá desfazer isto!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, delete todas as imagens!",
                cancelButtonText: "Não, Cancele por favor!",
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {

                    //Requisição
                    var post = $.post(window.url_delete_all_img, {_method : 'delete'});

                    //Requisição OK
                    post.done(function(data){

                        data.apagadas.forEach(function(current, index){
                            $('#image-'+current).remove();
                        });

                        if(data.error.length > 0){
                            $('section.content').prepend('<div class="initial"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Ocorreu um erro ao apagar algumas imagens!</div></div>');
                            $('.initial').first().slideDown();
                        }else{
                            $('#deleteAll').addClass('hidden');
                        }

                        //limpa variavel global
                        delete window.url_delete_all_img;

                        swal("Deletadas!", "Suas imagens foram deletadas.", "success");

                    }, 'json');

                    //Erro na requisição
                    post.fail(function(data){

                        if(data.responseJSON){
                            swal('Erro!', data.responseJSON.message, "error");
                        }else{
                            swal('Erro!', 'Ocorreu um erro na sua requisição. Por favor contate o administrador do sistema', "error");
                        }

                    }, 'json');

                } else {
                    swal("Cancelado", "Suas imagens estão a salvo :)", "error");
                }
            }, 2000
        );
    });

    //aciona o input file
    $('#input-image').click(function(){
        $('#imagens').click();
    });

    $('.sortable').sortable({
        cancel: '#input-image',
        update: function(event, ui){
            var order = $(this).sortable('serialize');
            var post = $.post($('input[name="url-order"]').val()+'?'+order);

            //Requisição falha (500)
            post.fail(function(data){

                if(data.responseJSON) {
                    $('section.content').prepend('<div class="initial"><div class="alert alert-' + data.responseJSON.status + '"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.responseJSON.message + '</div></div>');
                }else{
                    $('section.content').prepend('<div class="initial"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.statusText + '</div></div>');
                }
                $('.initial').first().slideDown();

            }, 'json');

        }
    }).disableSelection();
});

/* Funções para mostrar o aviso acima dos cadastros (esta funcional é só ativar)

 $('section.content').prepend('<div class="initial"><div class="alert alert-'+data.status+'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.message+'</div></div>');
 $('.initial').first().slideDown();

 */
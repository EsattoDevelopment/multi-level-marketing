/**
 * Created by GehakaMKT on 18/05/2016.
 */
$(function(){
    //confirmação para ForceDelete
    $('.botao-del').click(function(event){
        event.preventDefault();

        window.lista_id = $(this).attr('data-id');

        swal({   title: "Você tem certeza?",
                text: "Você não poderá desfazer isto!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, delete!",
                cancelButtonText: "Não, Cancele por favor!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {
                    var id = window.lista_id;
                    delete window.lista_id;
                    $('#formDel_'+id).submit();
                } else {
                    swal("Cancelado", "O registro esta a salvo :)", "error");
                }
            }
        );
    });

    $('.table tbody.ordernar').sortable({
        update: function( event, ui ) {
            if(ui.item.context.offsetParent.id != 'table_desabled'){
                data = $('#' + ui.item.context.offsetParent.id + ' tbody').sortable('serialize');
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: $('.tab-content').attr('data-sort'),
                    datatype: 'json',
                }).done(function(data) {
                    console.log(data);
                }).fail(function(data) {
                    swal('Erro!', data.message, 'error');
                });
            }
        }
    });

    <!-- This is only necessary if you do Flash::overlay('...') -->
    $('#flash-overlay-modal').modal();
});
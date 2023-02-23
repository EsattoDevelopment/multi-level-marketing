/**
 * Created by GehakaMKT on 10/05/2016.
 */
$(function () {
    if ($('.ordernar').length > 0) {
        $("table.table").DataTable({
            responsive: true,
            "ordering": false
        });
    } else if ($('.responsive').length > 0) {
        $("table.table").DataTable({
            "order": [[0, "desc"]],
            responsive: true,
            scrollCollapse: true,
            scrollX: true,
            scroller: true
        });
    } else {
        $("table.table").DataTable({
            "order": [[0, "desc"]],
            responsive: true
        });
    }
    //$("table.table").DataTable();

    //$('[data-toggle=confirmation]').confirmation();

});
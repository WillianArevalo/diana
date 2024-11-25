$(document).ready(function () {
    const table = $("#table-rrhh").DataTable({
        paging: true,
        searching: true,
        info: true,
        responsive: true,
        pageLength: 50,
        lengthMenu: [50, 100, 200, 500],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
        },
        drawCallback: function () {
            $("#table-rrhh_filter").addClass("mb-4");
        },
    });

    $("#workplace").change(function () {
        table.column(3).search($(this).val()).draw();
    });

    $("#marks-table").DataTable({
        paging: false,
        searching: true,
        info: true,
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
        },
        drawCallback: function () {
            $("#marks-table_filter").addClass("mb-4");
        },
    });
});

$(document).ready(function () {
    $("#example").DataTable({
        paging: true,
        searching: true,
        info: true,
        responsive: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
        },
        drawCallback: function () {
            $("#example_filter").addClass("mb-4");
        },
    });

    const userIds = $("input[name='user_ids']");

    $(document).on("click", ".close-modal", function () {
        $(".modal").removeClass("flex").addClass("hidden");
        $(".modal-seventh").removeClass("flex").addClass("hidden");
        $(".modal-observation").removeClass("flex").addClass("hidden");
    });

    $(document).on("click", function (e) {
        if (
            ($(e.target).hasClass("modal") &&
                !$(e.target).hasClass("assign-schedule") &&
                !$(e.target).hasClass("close-modal")) ||
            ($(e.target).hasClass("modal-seventh") &&
                !$(e.target).hasClass("assign-seventh")) ||
            ($(e.target).hasClass("modal-observation") &&
                !$(e.target).hasClass("add-observation"))
        ) {
            $(".modal").addClass("hidden");
            $(".modal-seventh").addClass("hidden");
            $(".modal-observation").addClass("hidden");
        }
    });

    let ids = [];

    $("#selectAll").click(function () {
        $("input[type='checkbox']").prop("checked", $(this).is(":checked"));
        if ($(this).is(":checked")) {
            $("#assign-schedule").removeClass("hidden");
            $("input[type='checkbox']").each(function () {
                if ($(this).is(":checked")) {
                    ids.push($(this).val());
                }
            });
        } else {
            $("#assign-schedule").addClass("hidden");
            ids = [];
        }
    });

    $(".check-items").click(function () {
        const checkboxValue = $(this).val();

        if (!$(this).is(":checked")) {
            ids = ids.filter((id) => id !== checkboxValue);
        } else {
            if (!ids.includes(checkboxValue)) {
                ids.push(checkboxValue);
            }
        }

        if ($(".check-items:checked").length === 0) {
            $("#assign-schedule").addClass("hidden");
            $("#assign-seventh").addClass("hidden");
        } else {
            $("#assign-schedule").removeClass("hidden");
            $("#assign-seventh").removeClass("hidden");
        }
    });

    $(document).on("click", ".assign-schedule", function () {
        $(".modal").removeClass("hidden").addClass("flex");
        userIds.val($(this).data("user-id"));
    });

    $(document).on("click", ".assign-seventh", function () {
        $(".modal-seventh").removeClass("hidden").addClass("flex");
        userIds.val($(this).data("user-id"));
    });

    $(document).on("click", ".add-observation", function () {
        $(".modal-observation").removeClass("hidden").addClass("flex");
        $("#user_id").val($(this).data("user-id"));
    });

    $(document).on("click", "#assign-schedule", function () {
        $(".modal").removeClass("hidden").addClass("flex");
        userIds.val(ids);
    });

    $(document).on("click", "#assign-seventh", function () {
        $(".modal-seventh").removeClass("hidden").addClass("flex");
        userIds.val(ids);
    });
});

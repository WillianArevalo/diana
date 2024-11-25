import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

$(document).ready(function () {
    var calendarElement = $("#calendar");
    var holidaysWorkplace = holidays;
    var calendar = new Calendar(calendarElement[0], {
        initialView: "dayGridMonth",
        eventLimit: true,
        plugins: [dayGridPlugin, interactionPlugin],
        selectable: true,
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "",
        },
        events: holidaysWorkplace,
        select: function (info) {
            var startDate = info.startStr;
            var endDate = info.endStr;
            $("#date_start").val(startDate);
            $("#date_end").val(endDate);

            $(".modal-holiday").addClass("flex").removeClass("hidden");
        },
        eventClick: function (info) {},
        locale: "es",
        dateClick: function (info) {
            var date = info.dateStr;
        },
        buttonText: {
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
        },
    });

    calendar.render();

    $(".close-modal").click(function () {
        $(".modal-holiday").addClass("hidden").removeClass("flex");
    });
});

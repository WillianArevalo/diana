$(document).ready(function () {
    let video = document.getElementById("video-capture");
    let canvas = document.getElementById("canvas-capture");
    let context = canvas.getContext("2d");
    let stream = null;

    const lat = lat_empresa;
    const lng = lng_empresa;

    $("#btn-take-photo").click(async function () {
        $("#icon-photo").addClass("hidden");
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: true,
            });
            video.srcObject = stream;

            $("#video-capture").removeClass("hidden");
            $("#btn-capture").removeClass("hidden");
            $(this).addClass("hidden");
        } catch (err) {
            alert("No se pudo acceder a la cámara: " + err.message);
        }
    });

    $("#btn-capture").click(function () {
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        let tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
        $(this).addClass("hidden");
        $("#canvas-capture").removeClass("hidden");
        $("#video-capture").addClass("hidden");
        $("#btn-take-photo").addClass("hidden");
        $("#btn-marking").removeClass("hidden");

        canvas.toBlob(function (blob) {
            let file = new File([blob], "photo.png", { type: "image/png" });
            let dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            $("#photo")[0].files = dataTransfer.files;
            $("#btn-marking").removeClass("hidden");
        });
    });

    getGeolocation();

    function getGeolocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(verifyPosition);
        } else {
            alert("No se pudo acceder a la geolocalización");
        }
    }

    function verifyPosition(position) {
        const lat_user = position.coords.latitude;
        const lng_user = position.coords.longitude;

        const distance = calculateDistance(lat_user, lng_user, lat, lng);
        console.log(distance);

        if (distance > 0.1) {
            Swal.fire({
                title: "¡Atención!",
                text: "Para registrar tu marca, debes estar en la empresa",
                icon: "warning",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#3085d6",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/error/register-marking";
                }
            });
        }
    }

    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * (Math.PI / 180);
        const dLng = (lng2 - lng1) * (Math.PI / 180);

        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * (Math.PI / 180)) *
                Math.cos(lat2 * (Math.PI / 180)) *
                Math.sin(dLng / 2) *
                Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c;
        return distance;
    }
});

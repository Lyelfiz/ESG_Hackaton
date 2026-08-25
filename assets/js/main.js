document.addEventListener('DOMContentLoaded', function () {
    var alerts = document.querySelectorAll('.alert');

    alerts.forEach(function (alert) {
        alert.setAttribute('role', 'alert');
    });
});

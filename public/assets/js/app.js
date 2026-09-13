document.addEventListener('DOMContentLoaded', function () {
    var exportForm = document.getElementById('form-export-rapport');
    if (exportForm) {
        exportForm.addEventListener('submit', function () {
            var overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.classList.add('active');
            }
        });
    }

    var alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach(function (el) {
        setTimeout(function () {
            el.classList.remove('show');
        }, 5000);
    });
});

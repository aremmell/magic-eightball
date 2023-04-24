(function () {
    window.addEventListener('load', function () {
        let forms = $(".needs-validation");
        for (let f = 0; f < forms.length; f++) {
            let e = $(forms[f]);
            e.submit((evt) => {
                if (forms[f].checkValidity() === false) {
                    evt.preventDefault();
                    evt.stopPropagation();
                    console.error("Form failed to validate!");
                } else {
                    let textInput = $("#eb-input-text");
                    let base64Text = btoa(textInput.val());

                    textInput.val(base64Text);

                    e.addClass("was-validated");
                    console.info("Form validated.");
                }
            });
        }
    });
})();

(function() {
    'use strict';
    window.addEventListener('load', function() {
        var inputs = document.getElementsByClassName('form-control');
        for (let i of inputs) {
            i.addEventListener('blur', function() {
                if (i.checkValidity()) {
                    i.classList.remove('is-invalid');
                    i.classList.add('is-valid');
                } else {
                    i.classList.remove('is-valid');
                    i.classList.add('is-invalid');
                }
            });
        }
    });
})();

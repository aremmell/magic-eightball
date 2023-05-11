(() => {
  'use strict'
  window.addEventListener('load', () => {
    const forms = document.getElementsByClassName('needs-validation');

    for (let f of forms) {
      f.addEventListener('submit', (evt) => {
          let textInput = $("#eb-question-edit");
          let validity = f.checkValidity();

          textInput.addClass(validity ? 'is-valid' : 'is-invalid');
          textInput.removeClass(validity ? 'is-invalid' : 'is-valid');

          //console.log(`got submit event; valid: ${validity}`);

          if (!validity) {
            evt.preventDefault();
            evt.stopPropagation();
          } else {
            let base64Text = btoa(textInput.val());
            textInput.css("color", "#e0e0e0");
            textInput.val(base64Text);
          }
        }, true);
      }
  });
})()

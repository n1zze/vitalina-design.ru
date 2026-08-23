(function () {
  'use strict';

  var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.querySelectorAll('[data-deliverables-slider]').forEach(function (slider) {
    var slides = Array.prototype.slice.call(slider.querySelectorAll('img'));
    if (slides.length < 2) return;
    slides.forEach(function (slide, index) {
      slide.classList.toggle('is-active', index === 0);
      slide.setAttribute('aria-hidden', index === 0 ? 'false' : 'true');
    });
    if (reducedMotion) return;
    var active = 0;
    window.setInterval(function () {
      slides[active].classList.remove('is-active');
      slides[active].setAttribute('aria-hidden', 'true');
      active = (active + 1) % slides.length;
      slides[active].classList.add('is-active');
      slides[active].setAttribute('aria-hidden', 'false');
    }, 4000);
  });

  function formatPhone(input) {
    var value = input.value.replace(/\D/g, '');
    if (value.charAt(0) === '8') value = '7' + value.slice(1);
    if (value && value.charAt(0) !== '7') value = '7' + value;
    var output = value ? '+7' : '';
    if (value.length > 1) output += ' (' + value.slice(1, 4);
    if (value.length > 4) output += ') ' + value.slice(4, 7);
    if (value.length > 7) output += '-' + value.slice(7, 9);
    if (value.length > 9) output += '-' + value.slice(9, 11);
    input.value = output;
  }

  document.querySelectorAll('[data-service-form]').forEach(function (form) {
    var phone = form.querySelector('[data-phone-input]');
    var success = form.querySelector('.contact-form-inline__success');
    if (phone) phone.addEventListener('input', function () { formatPhone(phone); });

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      event.stopImmediatePropagation();
      var valid = true;
      form.querySelectorAll('[required]').forEach(function (field) {
        var invalid = field.type === 'checkbox' ? !field.checked : !field.value.trim();
        if (field.type === 'tel' && field.value.replace(/\D/g, '').length < 11) invalid = true;
        field.setAttribute('aria-invalid', invalid ? 'true' : 'false');
        valid = valid && !invalid;
      });
      if (!valid) return;

      var button = form.querySelector('button[type="submit"]');
      if (button) button.disabled = true;
      fetch(form.action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(Object.fromEntries(new FormData(form).entries()))
      }).then(function (response) {
        if (!response.ok) throw new Error('request failed');
        form.reset();
        if (success) success.style.display = 'block';
      }).catch(function () {
        window.alert('Не удалось отправить заявку. Попробуйте ещё раз или напишите в Telegram @rvvitalina.');
      }).finally(function () {
        if (button) button.disabled = false;
      });
    }, true);
  });
})();

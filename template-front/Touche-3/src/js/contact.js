//
// contact.js
//

import { init, sendForm } from 'emailjs-com';

import { toggleNotification } from './helpers';

const form = document.getElementById('contactForm');

const userId = 'user_XXXXXXXXXXXXXXXXXXXX'; // replace this with your own user id
const serviceId = 'touche_service';
const templateId = 'touche_contact_template';

// init EmailJS
init(userId);

// process form data
if (form) {
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const submit = form.querySelector('[type="submit"]');

    // disable submit button
    submit.disabled = true;

    // generate a five digit number for the contact_number variable
    this.contact_number.value = (Math.random() * 100000) | 0;

    // send email
    sendForm(serviceId, templateId, this).then(
      function () {
        toggleNotification('Your email has been sent successfully!');

        // enable submit button
        submit.disabled = false;

        // reset form
        form.reset();
      },
      function (error) {
        toggleNotification(error);

        // enable submit button
        submit.disabled = false;
      }
    );
  });
}

//
// events.js
//

import { Collapse } from 'bootstrap';

const eventLgs = document.querySelectorAll('.event-lg');

function getInstance(target) {
  const event = target.closest('.event');
  const eventSm = event.querySelector('.event-sm');
  return new Collapse(eventSm, { toggle: false });
}

eventLgs.forEach((eventLg) => {
  eventLg.addEventListener('show.bs.collapse', (e) => {
    const collapseInstance = getInstance(e.target);
    collapseInstance.hide();
  });

  eventLg.addEventListener('hide.bs.collapse', (e) => {
    const collapseInstance = getInstance(e.target);
    collapseInstance.show();
  });
});

'use strict';

const form = document.getElementById('registrationForm');
const fee = { registration: 1000, perDay: 500, workshop: 750, secondWorkshop: 1000, lunchPerDay: 300 };
const money = amount => `KSh ${amount.toLocaleString('en-KE')}`;

function showWorkshop() {
  const isWorkshop = form.querySelector('input[name="category"]:checked')?.value === 'workshop';
  const panel = document.getElementById('workshopPanel');
  panel.classList.toggle('hidden', !isWorkshop);
  document.getElementById('workshop').required = isWorkshop;
  if (!isWorkshop) {
    document.getElementById('workshop').value = '';
    document.getElementById('secondWorkshop').checked = false;
  }
  updatePackageMessage();
  calculateFee();
}

function updatePackageMessage() {
  const category = form.querySelector('input[name="category"]:checked')?.value;
  const days = Number(document.getElementById('days').value || 0);
  const workshopName = document.getElementById('workshop').selectedOptions[0]?.text;
  const packageMessage = document.getElementById('packageMessage');
  if (!category) packageMessage.textContent = 'Choose a participation category to see your package summary.';
  else if (category === 'conference') packageMessage.textContent = `Conference only${days ? ` · ${days} ${days === 1 ? 'day' : 'days'}` : ''}. Add lunch if you would like catering.`;
  else packageMessage.textContent = `${workshopName && document.getElementById('workshop').value ? `${workshopName} workshop` : 'Workshop participant'}${document.getElementById('secondWorkshop').checked ? ' + second workshop' : ''}${days ? ` · ${days} ${days === 1 ? 'day' : 'days'}` : ''}.`;
}

function calculateFee() {
  const days = Number(document.getElementById('days').value || 0);
  const category = form.querySelector('input[name="category"]:checked')?.value;
  const hasWorkshop = category === 'workshop';
  const hasSecondWorkshop = hasWorkshop && document.getElementById('secondWorkshop').checked;
  const hasMeals = document.getElementById('meals').checked;
  const attendance = days * fee.perDay;
  const workshop = hasWorkshop ? fee.workshop : 0;
  const secondWorkshop = hasSecondWorkshop ? fee.secondWorkshop : 0;
  const meals = hasMeals ? days * fee.lunchPerDay : 0;
  const total = fee.registration + attendance + workshop + secondWorkshop + meals;
  document.getElementById('feeTotal').textContent = money(total);
  document.getElementById('attendanceFee').textContent = money(attendance);
  document.getElementById('workshopFee').textContent = money(workshop);
  document.getElementById('secondWorkshopFee').textContent = money(secondWorkshop);
  document.getElementById('mealFee').textContent = money(meals);
  document.getElementById('dayCount').textContent = `${days} ${days === 1 ? 'day' : 'days'}`;
  updatePackageMessage();
}

function setError(id, message) {
  const element = document.getElementById(id);
  element.textContent = message;
  element.classList.toggle('visible', Boolean(message));
}

function validateForm(event) {
  let valid = true;
  const name = form.elements.full_name.value.trim();
  const admission = form.elements.admission_number.value.trim();
  const year = form.elements.year_of_study.value;
  const email = form.elements.email.value.trim();
  const phone = form.elements.phone.value.trim();
  const category = form.querySelector('input[name="category"]:checked')?.value;
  const days = Number(form.elements.days.value);
  const workshop = form.elements.workshop.value;

  const checks = [
    ['nameError', name.length > 0, 'Please enter your full name.'],
    ['admissionError', admission.length > 0, 'Please enter your admission number.'],
    ['yearError', Boolean(year), 'Please select your year of study.'],
    ['emailError', /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email), 'Enter a valid email address.'],
    ['phoneError', /^\d{10}$/.test(phone), 'Enter exactly 10 digits.'],
    ['categoryError', Boolean(category), 'Please select a participation category.'],
    ['daysError', days >= 1 && days <= 3, 'Choose between 1 and 3 conference days.'],
    ['workshopError', category !== 'workshop' || Boolean(workshop), 'Please select a workshop.']
  ];
  for (const [id, ok, message] of checks) { setError(id, ok ? '' : message); valid = valid && ok; }
  if (!valid) {
    event.preventDefault();
    form.querySelector('.error.visible')?.closest('label,fieldset,.dynamic-panel')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
  return valid;
}

form.addEventListener('change', event => {
  if (event.target.name === 'category') showWorkshop();
  else { calculateFee(); }
});
form.addEventListener('input', event => {
  if (event.target.name === 'phone') event.target.value = event.target.value.replace(/\D/g, '').slice(0, 10);
});
form.addEventListener('submit', validateForm);

for (const field of form.querySelectorAll('input, select')) {
  field.addEventListener('input', () => {
    const mapping = { full_name: 'nameError', admission_number: 'admissionError', email: 'emailError', phone: 'phoneError' };
    if (mapping[field.name]) setError(mapping[field.name], '');
  });
}
calculateFee();

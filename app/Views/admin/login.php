<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>เข้าสู่ระบบผู้ดูแลระบบ | <?= esc($websiteName) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <style>
    html { scroll-behavior: smooth; }
    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
    body {
      font-family: 'K2D', sans-serif;
      background: #090d16;
      min-height: 100vh;
      color: #f8fafc;
      overflow-x: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }
    .bg-animation {
      position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;
      overflow: hidden; pointer-events: none; background: radial-gradient(circle at center, #111827 0%, #090d16 100%);
    }
    .blob {
      position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.35;
      animation: move 15s infinite alternate ease-in-out;
    }
    .blob-1 { width: 300px; height: 300px; background: #ec4899; top: -10%; left: -10%; }
    .blob-2 { width: 400px; height: 400px; background: #0ea5e9; bottom: -10%; right: -10%; animation-delay: -5s; }
    @keyframes move { from { transform: translate(0, 0) scale(1); } to { transform: translate(40px, 40px) scale(1.2); } }

    .glass-card {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 32px;
      padding: 2rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      animation: slideIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .input-box {
      width: 100%; background: rgba(0,0,0,0.3); border: 1.5px solid rgba(255,255,255,0.1);
      border-radius: 16px; padding: 1rem 1.25rem; color: #fff; font-size: 1rem;
      transition: all 0.3s; margin-bottom: 1rem; outline: none;
    }
    .input-box:focus { border-color: #0ea5e9; background: rgba(0,0,0,0.5); box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15); }
    label { font-size: 0.75rem; font-weight: 700; color: #ec4899; text-transform: uppercase; margin-left: 0.5rem; margin-bottom: 0.25rem; display: block; }

    .btn-main {
      width: 100%; padding: 1.1rem; border-radius: 18px; font-weight: 800; font-size: 1.15rem;
      background: linear-gradient(135deg, #ec4899, #0ea5e9); color: white;
      box-shadow: 0 10px 20px -5px rgba(236, 72, 153, 0.4); transition: all 0.3s;
      border: none; cursor: pointer;
    }
    .btn-main:hover { transform: translateY(-2px); box-shadow: 0 15px 25px -5px rgba(236, 72, 153, 0.6); }
    .btn-main:active { transform: scale(0.98); }
  </style>
</head>
<body>
  <?= view('common/loader') ?>

  <div class="bg-animation"><div class="blob blob-1"></div><div class="blob blob-2"></div></div>

  <div class="w-full max-w-[420px]">
    <div class="glass-card">
      <div class="flex flex-col items-center mb-6">
        <?php if (!empty($logoUrl)): ?>
          <img src="<?= esc($logoUrl) ?>" alt="logo" class="h-16 mb-4">
        <?php endif; ?>
        <h1 class="text-2xl font-extrabold text-center bg-gradient-to-r from-pink-500 to-sky-400 bg-clip-text text-transparent mb-1"><?= esc($websiteName) ?></h1>
        <p class="text-sky-400 font-bold text-center text-sm">เข้าสู่ระบบสำหรับครูด้วยบัญชี Google</p>
      </div>

      <?php 
      $clientId = !empty($googleClientId) ? $googleClientId : 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com'; 
      ?>

      <div class="flex flex-col items-center justify-center mt-6">
        <div id="g_id_onload"
             data-client_id="<?= esc($clientId) ?>"
             data-context="signin"
             data-ux_mode="popup"
             data-callback="handleGoogleCredentialResponse"
             data-auto_prompt="false">
        </div>
        <div class="p-[2px] rounded-full bg-gradient-to-r from-pink-500 to-sky-400 shadow-lg shadow-pink-500/25 hover:scale-105 active:scale-95 transition-all duration-300">
          <div class="g_id_signin"
               data-type="standard"
               data-shape="pill"
               data-theme="outline"
               data-text="signin_with"
               data-size="large"
               data-logo_alignment="left">
          </div>
        </div>

        <!-- Master Admin Password Login Fallback -->
        <div class="w-full mt-6 pt-5 border-t border-white/10 text-center">
          <button type="button" onclick="togglePasswordLogin()" class="text-xs text-slate-400 hover:text-sky-400 transition-colors inline-flex items-center justify-center gap-1.5 cursor-pointer">
            <span>🔑</span> หรือเข้าสู่ระบบด้วยรหัสผ่านผู้ดูแลระบบ
          </button>
          
          <form id="passwordLoginForm" onsubmit="handlePasswordLogin(event)" class="hidden mt-4 space-y-3 text-left">
            <div>
              <label for="adminPasswordInput" class="text-xs font-bold text-slate-300 mb-1 block">รหัสผ่านผู้ดูแลระบบ (Admin Password)</label>
              <input type="password" id="adminPasswordInput" placeholder="กรอกรหัสผ่านผู้ดูแลระบบ" 
                     class="input-box text-sm mb-2 w-full" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-main text-sm py-2.5 flex items-center justify-center gap-2">
              <span>เข้าสู่ระบบ</span> <span>🚀</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePasswordLogin() {
      const form = document.getElementById('passwordLoginForm');
      form.classList.toggle('hidden');
      if (!form.classList.contains('hidden')) {
        document.getElementById('adminPasswordInput').focus();
      }
    }

    async function handlePasswordLogin(e) {
      e.preventDefault();
      const password = document.getElementById('adminPasswordInput').value;
      if (!password) return;

      Swal.fire({
        title: 'กำลังตรวจสอบรหัสผ่าน...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      const formData = new FormData();
      formData.append('password', password);

      try {
        const res = await fetch('/api/teacher/login', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const data = await res.json();
        Swal.close();

        if (data.success) {
          window.location.href = '/teacher/dashboard';
        } else {
          Swal.fire({
            icon: 'error',
            title: 'เข้าสู่ระบบล้มเหลว',
            text: data.message || 'รหัสผ่านไม่ถูกต้อง'
          });
        }
      } catch (error) {
        Swal.close();
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ในขณะนี้'
        });
      }
    }

    window.handleGoogleCredentialResponse = async (response) => {
        Swal.fire({
            title: 'กำลังลงชื่อเข้าใช้งานด้วย Google...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const formData = new FormData();
        formData.append('credential', response.credential);

        try {
            const res = await fetch('/api/teacher/google-login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();
            Swal.close();

            if (data.success) {
                window.location.href = '/teacher/dashboard';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เข้าสู่ระบบล้มเหลว',
                    text: data.message || 'กรุณาลองอีกครั้ง'
                });
            }
        } catch (error) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ในขณะนี้'
            });
        }
    };
  </script>
</body>
</html>

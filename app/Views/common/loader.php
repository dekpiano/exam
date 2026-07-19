<?php
// Check if the current page is the homepage to show the cool welcome intro, otherwise show a fast loader
$isHomepage = (current_url(true)->getPath() === '/' || current_url(true)->getPath() === '/index.php');
?>

<?php if ($isHomepage): ?>
  <!-- Cinematic Welcome Intro Splash Screen (4-5 Seconds) -->
  <div id="app-loader" style="
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: #090d16;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.6s;
    opacity: 1;
    visibility: visible;
  ">
    <!-- Animated Nebula Blobs -->
    <div style="
      position: absolute; inset: 0; pointer-events: none; overflow: hidden; z-index: -1;
    ">
      <div style="
        position: absolute; top: -10%; left: -10%; width: 300px; height: 300px;
        background: rgba(236, 72, 153, 0.15); border-radius: 50%; filter: blur(80px);
        animation: intro-blob-move 10s infinite alternate ease-in-out;
      "></div>
      <div style="
        position: absolute; bottom: -10%; right: -10%; width: 350px; height: 350px;
        background: rgba(14, 165, 233, 0.15); border-radius: 50%; filter: blur(80px);
        animation: intro-blob-move 12s infinite alternate-reverse ease-in-out;
      "></div>
    </div>

    <div style="display: flex; flex-direction: column; align-items: center; max-w-sm w-full px-6 text-center; gap: 2rem;">
      <!-- Glowing Logo Shield with Pulse Zoom -->
      <div style="
        width: 90px;
        height: 90px;
        border-radius: 24px;
        background: linear-gradient(135deg, #ec4899, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-family: 'K2D', sans-serif;
        font-size: 2.25rem;
        font-weight: 900;
        box-shadow: 0 0 40px rgba(236, 72, 153, 0.4), inset 0 2px 4px rgba(255,255,255,0.4);
        animation: intro-logo-pulse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
      ">
        SKJ
      </div>

      <!-- Welcome Text with Typing / Reveal Effect -->
      <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <h2 style="
          color: #ffffff;
          font-family: 'K2D', sans-serif;
          font-size: 1.6rem;
          font-weight: 900;
          letter-spacing: 0.05em;
          margin: 0;
          background: linear-gradient(135deg, #f8fafc, #cbd5e1);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
        ">
          ระบบคลังข้อสอบออนไลน์
        </h2>
        <span style="
          color: #0ea5e9;
          font-family: 'Sarabun', sans-serif;
          font-size: 0.8rem;
          font-weight: 800;
          letter-spacing: 0.15em;
          text-transform: uppercase;
        ">
          สวนกุหลาบวิทยาลัย จิรประวัติ
        </span>
      </div>

      <!-- Premium Progress Bar -->
      <div style="width: 100%; display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.7rem; font-weight: bold; color: #64748b; font-family: 'Sarabun', sans-serif;">
          <span style="letter-spacing: 0.05em; animation: text-shimmer 1.5s infinite;">กำลังจัดเตรียมห้องสอบ...</span>
          <span id="intro-progress-percent">0%</span>
        </div>
        <div style="
          width: 100%; height: 5px; background: rgba(255, 255, 255, 0.03); border-radius: 99px; overflow: hidden;
          border: 1px solid rgba(255, 255, 255, 0.05);
        ">
          <div id="intro-progress-bar" style="
            width: 0%; height: 100%;
            background: linear-gradient(90deg, #ec4899, #0ea5e9);
            border-radius: 99px;
            box-shadow: 0 0 10px #ec4899;
            transition: width 0.1s linear;
          "></div>
        </div>
      </div>
    </div>
  </div>

  <style>
    @keyframes intro-blob-move {
      0% { transform: translate(0, 0) scale(1); }
      100% { transform: translate(40px, 40px) scale(1.2); }
    }
    @keyframes intro-logo-pulse {
      0%, 100% { transform: scale(1); box-shadow: 0 0 40px rgba(236, 72, 153, 0.4); }
      50% { transform: scale(1.05); box-shadow: 0 0 60px rgba(14, 165, 233, 0.6); }
    }
    @keyframes text-shimmer {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }
  </style>

  <script>
    // 4 Seconds Welcome Intro Animation Controller
    window.addEventListener('DOMContentLoaded', () => {
      const progressBar = document.getElementById('intro-progress-bar');
      const progressPercent = document.getElementById('intro-progress-percent');
      const loader = document.getElementById('app-loader');
      
      let duration = 3800; // 3.8 seconds fill
      let start = null;

      function animateProgress(timestamp) {
        if (!start) start = timestamp;
        let elapsed = timestamp - start;
        let progress = Math.min((elapsed / duration) * 100, 100);

        if (progressBar) progressBar.style.width = progress + '%';
        if (progressPercent) progressPercent.textContent = Math.floor(progress) + '%';

        if (elapsed < duration) {
          requestAnimationFrame(animateProgress);
        } else {
          // Wait 300ms at 100% then fade out scale-down
          setTimeout(() => {
            if (loader) {
              loader.style.opacity = '0';
              loader.style.transform = 'scale(1.05)';
              loader.style.visibility = 'hidden';
              setTimeout(() => loader.remove(), 600);
            }
          }, 300);
        }
      }

      requestAnimationFrame(animateProgress);
    });
  </script>

<?php else: ?>
  <!-- Regular Fast Loader for Other Pages -->
  <div id="app-loader" style="
    position: fixed;
    inset: 0;
    z-index: 999999;
    background: #090d16;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: opacity 0.4s ease, visibility 0.4s ease;
    opacity: 1;
    visibility: visible;
  ">
    <div style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem; text-align: center;">
      <div style="
        width: 56px;
        height: 56px;
        border: 4px solid rgba(236, 72, 153, 0.1);
        border-top: 4px solid #ec4899;
        border-right: 4px solid #0ea5e9;
        border-radius: 50%;
        animation: loader-spin 0.8s linear infinite;
        filter: drop-shadow(0 0 8px rgba(236, 72, 153, 0.3));
      "></div>
      <span style="
        color: #f8fafc;
        font-family: 'Sarabun', 'K2D', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        animation: loader-pulse 1.5s ease-in-out infinite;
      ">กำลังโหลดระบบ...</span>
    </div>
  </div>
  <style>
    @keyframes loader-spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    @keyframes loader-pulse {
      0%, 100% { opacity: 0.5; }
      50% { opacity: 1; }
    }
  </style>
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      setTimeout(() => {
        const loader = document.getElementById('app-loader');
        if (loader) {
          loader.style.opacity = '0';
          loader.style.visibility = 'hidden';
          setTimeout(() => loader.remove(), 400);
        }
      }, 100);
    });
  </script>
<?php endif; ?>

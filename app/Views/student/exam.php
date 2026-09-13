<!DOCTYPE html>
<html lang="th">
<head>
  <base target="_top">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>ทำข้อสอบ | <?= esc($websiteName) ?></title>
  <meta name="description" content="ระบบสอบออนไลน์ - <?= esc($websiteName) ?>">
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root {
      --pink: #ec4899;
      --blue: #0ea5e9;
      --dark: #0a0e1a;
      --card: rgba(255,255,255,0.04);
      --border: rgba(255,255,255,0.08);
      --emerald: #10b981;
      --amber: #f59e0b;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'K2D', sans-serif;
      background: var(--dark);
      min-height: 100dvh;
      color: #f1f5f9;
      overflow-x: hidden;
    }

    /* ===== Background ===== */
    .bg-anim {
      position: fixed; inset: 0; z-index: -1; pointer-events: none;
      background: radial-gradient(ellipse at 30% 20%, rgba(236,72,153,0.08) 0%, transparent 50%),
                  radial-gradient(ellipse at 70% 80%, rgba(14,165,233,0.08) 0%, transparent 50%),
                  var(--dark);
    }

    /* ===== Sticky Top Bar ===== */
    .top-bar {
      position: sticky; top: 0; z-index: 100;
      background: rgba(10, 14, 26, 0.92);
      backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      padding: 0.75rem 1rem;
    }
    .top-bar-inner {
      max-width: 680px; margin: 0 auto;
      display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
    }
    .top-left { display: flex; align-items: center; gap: 0.6rem; min-width: 0; }
    .top-logo { height: 28px; flex-shrink: 0; }
    .top-title {
      font-size: 0.8rem; font-weight: 800; color: #94a3b8;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .timer-box {
      display: flex; align-items: center; gap: 0.4rem;
      background: rgba(236,72,153,0.12);
      border: 1px solid rgba(236,72,153,0.25);
      padding: 0.4rem 0.9rem; border-radius: 99px; flex-shrink: 0;
    }
    .timer-icon { font-size: 1rem; }
    .timer-number {
      font-size: 1.35rem; font-weight: 800; color: var(--pink);
      min-width: 2ch; text-align: center; line-height: 1;
    }
    .timer-box.warning { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); }
    .timer-box.warning .timer-number { color: #ef4444; }
    .timer-pulse { animation: tPulse 0.5s ease-in-out infinite alternate; }
    @keyframes tPulse { to { transform: scale(1.08); } }

    /* ===== Progress ===== */
    .progress-section {
      max-width: 680px; margin: 0 auto;
      padding: 1rem 1rem 0;
    }
    .progress-info {
      display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.4rem;
    }
    .progress-label { font-size: 0.75rem; font-weight: 700; color: #64748b; }
    .progress-value { font-size: 0.85rem; font-weight: 800; color: var(--pink); }
    .progress-track {
      width: 100%; height: 6px; background: rgba(255,255,255,0.05);
      border-radius: 99px; overflow: hidden;
    }
    .progress-fill {
      height: 100%; width: 0%; border-radius: 99px;
      background: linear-gradient(90deg, var(--pink), var(--blue));
      transition: width 0.5s cubic-bezier(0.4,0,0.2,1);
    }

    /* ===== Question Card ===== */
    .question-area {
      max-width: 680px; margin: 0 auto;
      padding: 1rem 1rem 3rem;
    }
    .q-type-badge {
      display: inline-flex; align-items: center; gap: 0.3rem;
      padding: 0.35rem 0.9rem; border-radius: 99px;
      font-size: 0.7rem; font-weight: 800; letter-spacing: 0.05em;
      margin-bottom: 0.75rem;
    }
    .q-type-choice { background: rgba(236,72,153,0.12); color: var(--pink); border: 1px solid rgba(236,72,153,0.2); }
    .q-type-writing { background: rgba(245,158,11,0.12); color: var(--amber); border: 1px solid rgba(245,158,11,0.2); }

    .q-text {
      font-size: 1.2rem; font-weight: 800; color: #fff;
      line-height: 1.65; margin-bottom: 1.25rem;
      white-space: pre-wrap; word-break: break-word;
    }
    @media (min-width: 640px) {
      .q-text { font-size: 1.35rem; }
    }

    /* ===== Image ===== */
    .q-image-wrap {
      margin: 0.75rem 0 1.25rem;
      border-radius: 16px; overflow: hidden;
      background: rgba(0,0,0,0.3);
      border: 1px solid var(--border);
    }
    .q-image {
      width: 100%; display: block; cursor: zoom-in;
      transition: filter 0.3s;
    }
    .q-image:hover { filter: brightness(1.1); }

    /* ===== Choice Options ===== */
    .options-list { display: flex; flex-direction: column; gap: 0.65rem; }

    .opt-card {
      display: flex; align-items: center; gap: 0.85rem;
      padding: 1rem 1.15rem;
      background: var(--card);
      border: 2px solid var(--border);
      border-radius: 16px;
      cursor: pointer;
      transition: all 0.2s ease;
      -webkit-user-select: none; user-select: none;
    }
    .opt-card:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.14); }
    .opt-card:active { transform: scale(0.98); }
    .opt-card.selected {
      background: rgba(14,165,233,0.1);
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(14,165,233,0.15), 0 8px 25px rgba(0,0,0,0.25);
    }

    .opt-radio {
      width: 22px; height: 22px; border-radius: 50%;
      border: 2.5px solid rgba(255,255,255,0.25);
      flex-shrink: 0; position: relative;
      transition: border-color 0.2s;
    }
    .opt-card.selected .opt-radio {
      border-color: var(--blue);
    }
    .opt-card.selected .opt-radio::after {
      content: ''; position: absolute; inset: 4px;
      background: var(--blue); border-radius: 50%;
      animation: dotPop 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes dotPop { from { transform: scale(0); } to { transform: scale(1); } }

    .opt-label {
      flex: 1; font-size: 1rem; font-weight: 700; color: #e2e8f0;
      line-height: 1.5;
      white-space: pre-wrap; word-break: break-word;
    }
    @media (min-width: 640px) {
      .opt-label { font-size: 1.1rem; }
    }

    .opt-letter {
      font-size: 0.75rem; font-weight: 800; color: #475569;
      background: rgba(255,255,255,0.05);
      width: 28px; height: 28px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .opt-card.selected .opt-letter { color: var(--blue); background: rgba(14,165,233,0.15); }

    /* ===== Writing Input ===== */
    .writing-label {
      font-size: 0.8rem; font-weight: 800; color: #64748b;
      text-transform: uppercase; letter-spacing: 0.08em;
      margin-bottom: 0.6rem;
    }
    .writing-area {
      width: 100%; min-height: 140px;
      background: rgba(255,255,255,0.03);
      border: 2px solid rgba(255,255,255,0.1);
      border-radius: 16px; padding: 1rem 1.15rem;
      color: #f1f5f9; font-family: 'K2D', sans-serif;
      font-size: 1.05rem; font-weight: 600;
      line-height: 1.6; outline: none; resize: vertical;
      transition: border-color 0.2s;
    }
    .writing-area::placeholder { color: #334155; }
    .writing-area:focus {
      border-color: var(--amber);
      box-shadow: 0 0 0 3px rgba(245,158,11,0.12);
    }

    /* ===== Bottom Action Bar ===== */
    .bottom-bar {
      position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
      background: rgba(10,14,26,0.95);
      backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
      border-top: 1px solid var(--border);
      padding: 0.75rem 1rem;
      padding-bottom: max(0.75rem, env(safe-area-inset-bottom));
    }
    .bottom-bar-inner {
      max-width: 680px; margin: 0 auto;
      display: flex; gap: 0.6rem;
    }
    .btn-next, .btn-submit {
      width: 100%; padding: 1.1rem 2rem; border-radius: 20px;
      font-family: 'K2D', sans-serif;
      font-size: 1.15rem; font-weight: 800;
      border: 1px solid rgba(255, 255, 255, 0.1); cursor: pointer;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex; align-items: center; justify-content: center; gap: 0.6rem;
      letter-spacing: 0.03em;
    }
    .btn-next {
      background: linear-gradient(135deg, #ec4899, #8b5cf6);
      color: #fff;
      box-shadow: 0 8px 24px rgba(236, 72, 153, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.2);
    }
    .btn-next:hover:not(:disabled) { 
      box-shadow: 0 12px 30px rgba(236, 72, 153, 0.4), inset 0 2px 4px rgba(255, 255, 255, 0.3); 
      transform: translateY(-2px) scale(1.01); 
    }
    .btn-next:active:not(:disabled) { transform: scale(0.98); }
    
    .btn-submit {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      box-shadow: 0 8px 24px rgba(16, 185, 129, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.2);
    }
    .btn-submit:hover:not(:disabled) { 
      box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4), inset 0 2px 4px rgba(255, 255, 255, 0.3); 
      transform: translateY(-2px) scale(1.01); 
    }
    .btn-submit:active:not(:disabled) { transform: scale(0.98); }

    .btn-next:disabled, .btn-submit:disabled {
      background: rgba(255, 255, 255, 0.03) !important;
      color: rgba(255, 255, 255, 0.1) !important;
      box-shadow: none !important;
      cursor: not-allowed; transform: none !important;
      border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .hidden { display: none !important; }

    /* ===== Image Overlay ===== */
    #imageOverlay {
      position: fixed; inset: 0;
      background: rgba(10,14,26,0.97);
      z-index: 10000;
      display: none;
      align-items: center; justify-content: center;
      padding: 1rem;
      backdrop-filter: blur(16px);
      cursor: zoom-out; overflow: auto;
    }
    #imageOverlay img {
      max-width: 95%; max-height: 88dvh;
      object-fit: contain; border-radius: 16px;
      box-shadow: 0 25px 60px rgba(0,0,0,0.6);
      transition: transform 0.3s ease;
      cursor: zoom-in;
    }
    #imageOverlay.active { display: flex; animation: fadeIn 0.25s ease; }
    #imageOverlay.zoomed { cursor: zoom-out; display: block; }
    #imageOverlay.zoomed img {
      max-width: none; max-height: none;
      transform: scale(1.8); margin: auto;
      cursor: zoom-out;
    }
    .overlay-close {
      position: absolute; top: 1rem; right: 1rem;
      width: 44px; height: 44px; border-radius: 50%;
      background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 1.2rem; cursor: pointer;
      transition: background 0.2s;
    }
    .overlay-close:hover { background: rgba(255,255,255,0.2); }
    .overlay-hint {
      position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
      color: rgba(255,255,255,0.3); font-size: 0.65rem; font-weight: 700;
      letter-spacing: 0.1em; text-transform: uppercase;
      background: rgba(0,0,0,0.4); padding: 0.4rem 1rem; border-radius: 99px;
      pointer-events: none; white-space: nowrap;
    }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .animate-in { animation: slideUp 0.45s cubic-bezier(0.16,1,0.3,1); }

    /* ===== Responsive ===== */
    @media (min-width: 640px) {
      .top-bar { padding: 0.85rem 1.5rem; }
      .progress-section, .question-area { padding-left: 1.5rem; padding-right: 1.5rem; }
      .bottom-bar { padding: 1rem 1.5rem; }
    }
  </style>
</head>
<body>
  <?= view('common/loader') ?>

  <div class="bg-anim"></div>

  <!-- ===== STICKY TOP BAR ===== -->
  <div class="top-bar">
    <div class="top-bar-inner">
      <div class="top-left">
        <?php if (!empty($logoUrl)): ?>
          <img src="<?= esc($logoUrl) ?>" alt="logo" class="top-logo">
        <?php endif; ?>
        <span class="top-title"><?= esc($websiteName) ?></span>
      </div>
      <div class="flex items-center gap-2">
        <div id="overallTimerBox" class="timer-box hidden" style="border-color: rgba(14,165,233,0.35); background: rgba(14,165,233,0.12); gap: 0.35rem;" title="เวลาทำข้อสอบที่เหลือทั้งหมด">
          <span class="timer-icon">⏳</span>
          <span class="timer-number" id="overallTimerNumber" style="color: var(--blue);">--:--</span>
        </div>
        <div id="questionTimer" class="timer-box">
          <span class="timer-icon">⏱</span>
          <span class="timer-number" id="timerNumber">--</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== PROGRESS ===== -->
  <div class="progress-section">
    <div class="progress-info">
      <span class="progress-label" id="examSubject"><?= esc($examType) ?></span>
      <span class="progress-value" id="examProgress">ข้อที่ 1 / --</span>
    </div>
    <div class="progress-track">
      <div class="progress-fill" id="progressBarFill"></div>
    </div>
  </div>

  <!-- ===== QUESTION AREA ===== -->
  <div class="question-area">
    <div id="questionContainer">
      <!-- Dynamic question content -->
    </div>
    
    <!-- Action buttons right under the choices -->
    <div style="margin-top: 3.5rem; border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 1.5rem;">
      <button id="nextQuestionBtn" disabled class="btn-next">ข้อถัดไป ➔</button>
      <button id="submitExamBtn" disabled class="btn-submit hidden">ส่งข้อสอบ ✓</button>
    </div>
  </div>

  <!-- ===== IMAGE ZOOM OVERLAY ===== -->
  <div id="imageOverlay" onclick="handleOverlayClick(event)">
    <img id="overlayImg" src="" alt="Zoomed" onclick="toggleSecondaryZoom(event)">
    <div class="overlay-close" onclick="closeZoom()">✕</div>
    <div class="overlay-hint">แตะรูปเพื่อขยาย · แตะพื้นที่ว่างเพื่อปิด</div>
  </div>

  <script>
    // ===== Anti-Cheating =====
    document.addEventListener('copy', (e) => { e.preventDefault(); Swal.fire({ icon:'warning', title:'ไม่อนุญาตให้ Copy', timer:1500, showConfirmButton:false }); });
    document.addEventListener('contextmenu', (e) => { e.preventDefault(); return false; });

    var questions = [];
    var currentQuestionIndex = 0;
    var studentAnswers = [];
    var timerInterval;
    var startTime;
    var isExamActive = false;
    var cheatingCount = 0;
    var cheatingFlag = '';
    var maxCheatingLimit = <?= (int)$maxStrikes ?>;
    var timeLimitChoice = <?= (int)$timePerQuestion ?>;
    var timeLimitWriting = <?= (int)$writingTimePerQuestion ?>;
    var examDuration = <?= (int)$examDuration ?>; // in minutes (0 = unlimited)
    var examDurationSeconds = <?= (int)$examDurationSeconds ?>;
    var overallTimeLeft = examDurationSeconds;
    var overallTimerInterval;
    var isAntiCheatingEnabled = <?= (int)$antiCheating ?>; // 1 = enabled, 0 = disabled
    var blurHandler = null;
    var visibilityHandler = null;
    var keydownBlocker = null;
    var examId = "<?= $examId ?>";
    var statusInterval = null;
    var isPaused = false;
    var questionTimeLeft = 0;
    var pausedSwal = null;
    var isSubmitting = false;

    window.onload = () => {
        attemptFullscreen();
        startExamMode();
    };

    function attemptFullscreen() {
        const elem = document.documentElement;
        try {
            if (elem.requestFullscreen) {
                elem.requestFullscreen().catch(err => console.log("Auto-fullscreen blocked, waiting for gesture."));
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        } catch (e) {
            console.log("Auto-fullscreen error:", e);
        }
    }

    // Transparent fallback: if browser blocks automatic fullscreen, trigger it on first interaction
    const autoFullscreenHandler = () => {
        if (!document.fullscreenElement && !document.webkitIsFullScreen) {
            attemptFullscreen();
        }
        document.removeEventListener('click', autoFullscreenHandler);
        document.removeEventListener('touchstart', autoFullscreenHandler);
        document.removeEventListener('mousedown', autoFullscreenHandler);
        document.removeEventListener('keydown', autoFullscreenHandler);
    };
    document.addEventListener('click', autoFullscreenHandler);
    document.addEventListener('touchstart', autoFullscreenHandler);
    document.addEventListener('mousedown', autoFullscreenHandler);
    document.addEventListener('keydown', autoFullscreenHandler);

    function startExamMode() {
        startTime = new Date();
        isExamActive = true;
        studentAnswers = [];
        cheatingCount = 0;
        cheatingFlag = '';
        loadQuestions();

        // Detect exiting fullscreen (with initial grace period so auto-fullscreen block won't instantly trigger cheating)
        var fullscreenCheckEnabled = false;
        setTimeout(() => { fullscreenCheckEnabled = true; }, 1500);

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement && isExamActive && fullscreenCheckEnabled) {
                handleCheating('ออกจากโหมดเต็มจอ (Exit Fullscreen)');
            }
        });
        document.addEventListener('webkitfullscreenchange', () => {
            if (!document.webkitIsFullScreen && isExamActive && fullscreenCheckEnabled) {
                handleCheating('ออกจากโหมดเต็มจอ (Exit Fullscreen)');
            }
        });

        if (examDurationSeconds > 0) {
            initOverallTimer();
        }

        // Periodically check if exam is cancelled, paused, or reset by teacher
        statusInterval = setInterval(async () => {
            if (!isExamActive) return;
            try {
                const response = await fetch(`/api/exam/status?examId=${examId}`);
                const res = await response.json();
                const st = res.examStatus;

                if (st === 'Paused') {
                    if (!isPaused) enterPausedState();
                    return;
                }
                if (isPaused) exitPausedState();

                if (st !== 'Started' && isExamActive) {
                    isExamActive = false;
                    clearInterval(timerInterval);
                    clearInterval(overallTimerInterval);
                    clearInterval(statusInterval);
                    if (blurHandler) window.removeEventListener('blur', blurHandler);
                    if (visibilityHandler) document.removeEventListener('visibilitychange', visibilityHandler);

                    Swal.fire({
                        icon: 'error',
                        title: 'การสอบถูกยกเลิก!',
                        text: 'การสอบวิชานี้ถูกยกเลิกหรือปิดการสอบโดยคุณครูผู้ควบคุมระบบแล้ว',
                        allowOutsideClick: false,
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href = '/';
                    });
                }
            } catch (err) {
                console.error("Failed to check status", err);
            }
        }, 4000);

        // Only register anti-cheating event listeners if enabled by teacher
        if (isAntiCheatingEnabled === 1) {
            blurHandler = function() {
                if (!fullscreenCheckEnabled) return; // Ignore during initial page load/fullscreen prompt
                // Ignore blur if the input or textarea is active (prevents virtual keyboard false-positives)
                if (document.activeElement && (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA')) {
                    return;
                }
                
                // Add a small 200ms delay to check if focus is regained
                setTimeout(() => {
                    if (document.hasFocus() && document.visibilityState === 'visible') {
                        return;
                    }
                    handleCheating('สลับแอป/ย่อหน้าต่างสอบ (Window Blur)');
                }, 200);
            };

            visibilityHandler = function() {
                if (!fullscreenCheckEnabled) return; // Ignore during initial page load
                if (document.visibilityState === 'hidden') {
                    handleCheating('สลับแท็บเบราว์เซอร์/ย่อเบราว์เซอร์ (Tab Hidden)');
                }
            };

            window.addEventListener('blur', blurHandler);
            document.addEventListener('visibilitychange', visibilityHandler);

            // ตรวจจับการเปิด 2 จอ (Split-screen) หรือหน้าต่างป๊อปอัป
            var initialWindowHeight = window.innerHeight;
            var initialWindowWidth = window.innerWidth;
            
            window.addEventListener('resize', () => {
                if (!isExamActive) return;
                
                // ถ้าโฟกัสอยู่ที่ช่องพิมพ์ข้อความ (คีย์บอร์ดมือถือเด้งขึ้นมา) ให้ข้ามไป
                const activeTag = document.activeElement ? document.activeElement.tagName : '';
                if (activeTag === 'INPUT' || activeTag === 'TEXTAREA') return;

                // ถ้าขนาดหน้าจอหดลงไปเกิน 35% (ปกติเปิด 2 จอจะกินพื้นที่ 50%)
                const heightDrop = window.innerHeight < (initialWindowHeight * 0.65);
                const widthDrop = window.innerWidth < (initialWindowWidth * 0.65);

                if (heightDrop || widthDrop) {
                    // หน่วงเวลา 500ms เพื่อป้องกัน False Positive จากแอนิเมชันคีย์บอร์ดกำลังปิด
                    setTimeout(() => {
                        const currentActive = document.activeElement ? document.activeElement.tagName : '';
                        if (currentActive === 'INPUT' || currentActive === 'TEXTAREA') return;
                        
                        const stillHeightDrop = window.innerHeight < (initialWindowHeight * 0.65);
                        const stillWidthDrop = window.innerWidth < (initialWindowWidth * 0.65);
                        
                        if ((stillHeightDrop || stillWidthDrop) && isExamActive) {
                             handleCheating('เปิดใช้งานหลายหน้าต่าง (Split-screen / Floating Window)');
                        }
                    }, 500);
                }
            });
        }

        // Universal capture-phase keydown blocker: captures events at the highest level
        keydownBlocker = function(e) {
            const activeTag = document.activeElement ? document.activeElement.tagName : '';
            const isTypingField = (activeTag === 'INPUT' || activeTag === 'TEXTAREA');

            let shouldBlock = false;
            let cheatReason = '';

            // 1. Block F1 - F12 keys completely
            if (e.keyCode >= 112 && e.keyCode <= 123) {
                shouldBlock = true;
                cheatReason = 'กดปุ่ม F1-F12';
            }
            // 2. Block Command / Windows / Meta keys completely
            else if (e.key === 'Meta' || e.keyCode == 91 || e.keyCode == 92) {
                shouldBlock = true;
                cheatReason = 'กดปุ่ม Windows / Meta Key';
            }
            // 3. Block Escape completely
            else if (e.key === 'Escape' || e.keyCode == 27) {
                shouldBlock = true;
                cheatReason = 'กดปุ่ม Esc เพื่อพยายามออกจากโหมดเต็มจอ';
            }
            // 4. Block Alt key completely
            else if (e.altKey) {
                shouldBlock = true;
                cheatReason = 'กดปุ่ม Alt';
            }
            // 5. Block Ctrl / Control hotkeys (except Ctrl+A for selecting text in typing field)
            else if (e.ctrlKey) {
                if (!(isTypingField && (e.key === 'a' || e.key === 'A' || e.keyCode === 65))) {
                    shouldBlock = true;
                    cheatReason = 'กดปุ่มลัด Control (Ctrl)';
                }
            }
            // 6. Block Tab key to prevent navigating out of the exam layout
            else if (e.key === 'Tab' || e.keyCode === 9) {
                shouldBlock = true;
            }
            // 7. If NOT in a typing field, block all keys except standard navigation
            else if (!isTypingField) {
                const allowedNonTypingKeys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'];
                if (!allowedNonTypingKeys.includes(e.key)) {
                    shouldBlock = true;
                }
            }

            if (shouldBlock) {
                e.preventDefault();
                e.stopPropagation();
                if (isAntiCheatingEnabled === 1 && cheatReason) {
                    handleCheating(cheatReason);
                }
                return false;
            }
            return true;
        };

        window.addEventListener('keydown', keydownBlocker, true);
    }

    // ===== Pause / Resume (teacher-controlled) =====
    function enterPausedState() {
        isPaused = true;
        clearInterval(timerInterval);
        clearInterval(overallTimerInterval);
        pausedSwal = Swal.fire({
            icon: 'info',
            title: 'การสอบถูกหยุดชั่วคราว',
            html: 'คุณครูผู้คุมสอบหยุดการสอบชั่วคราว<br>โปรดรอสักครู่ ระบบจะกลับมาให้ทำข้อสอบต่อโดยอัตโนมัติ',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });
    }

    function exitPausedState() {
        isPaused = false;
        if (pausedSwal) { Swal.close(); pausedSwal = null; }
        if (!isExamActive) return;
        if (questionTimeLeft <= 0) questionTimeLeft = 1;
        startQuestionTimer(questionTimeLeft);
        if (examDurationSeconds > 0) {
            initOverallTimer();
        }
        attemptFullscreen();
    }

    async function handleCheating(reason) {
        if (!isExamActive) return;
        if (isPaused) return;

        // ป้องกัน False Positive บนมือถือ (เช่น เลื่อนจอใน LINE แล้วเกิด blur แต่หน้าจอยังแสดงอยู่)
        if (typeof reason !== 'string' && document.visibilityState === 'visible') {
            return;
        }

        var msg = typeof reason === 'string' ? reason : 'สลับหน้าจอ / ออกจากหน้าสอบ';
        cheatingCount++;
        cheatingFlag = (cheatingFlag ? cheatingFlag + ', ' : '') + msg;

        const formData = new FormData();
        formData.append('type', 'SUSPICIOUS_ACTIVITY');
        formData.append('message', `${msg} (ครั้งที่ ${cheatingCount})`);
        formData.append('screenResolution', `${window.screen.width}x${window.screen.height}`);
        formData.append('exam_id', examId);

        try {
            await fetch('/api/exam/log-error', { method: 'POST', body: formData });
        } catch (err) {
            console.error("Log error failed", err);
        }

        if (cheatingCount >= maxCheatingLimit) {
            isExamActive = false;
            Swal.fire({
                icon: 'error',
                title: 'ถูกระงับการสอบ!',
                text: 'คุณทำผิดกฎสลับหน้าจอเกิน ' + maxCheatingLimit + ' ครั้ง ระบบจะส่งคำตอบของคุณโดยอัตโนมัติทันที',
                allowOutsideClick: false,
                confirmButtonText: 'ตกลง'
            }).then(() => {
                finishExamWithCheating('ถูกส่งอัตโนมัติเนื่องจากทำผิดกฎ ' + maxCheatingLimit + ' ครั้ง');
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: '🛑 คำเตือน!',
                html: 'ห้ามสลับหน้าจอหรือเปิดโปรแกรมอื่น!<br><br><b>คุณทำผิดกฎแล้ว ' + cheatingCount + ' / ' + maxCheatingLimit + ' ครั้ง</b><br>หากครบ ' + maxCheatingLimit + ' ครั้ง ระบบจะส่งข้อสอบทันที',
                confirmButtonText: 'รับทราบและทำต่อ',
                allowOutsideClick: false
            });
        }
    }

    // ===== Load Questions =====
    async function loadQuestions() {
        try {
            const response = await fetch('/api/exam/start', { method: 'POST' });
            const res = await response.json();
            if (res.success && res.questions) {
                questions = res.questions;
                if (questions.length > 0) displayQuestion();
                else {
                    isExamActive = false;
                    Swal.fire('ข้อผิดพลาด', 'ไม่มีข้อสอบในรายวิชานี้', 'error').then(() => window.location.href = '/');
                }
            } else {
                isExamActive = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถทำข้อสอบได้',
                    text: res.message || 'ไม่สามารถโหลดข้อสอบได้',
                    confirmButtonText: 'กลับหน้าหลัก'
                }).then(() => {
                    window.location.href = '/';
                });
            }
        } catch (err) {
            isExamActive = false;
            Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error').then(() => window.location.href = '/');
        }
    }

    // ===== Format Text with Inline Images =====
    function formatWithImages(text) {
        if (!text) return '';
        var result = String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");

        var commonRegex = /(https?:\/\/[^\s]+\.(?:png|jpg|jpeg|gif|webp|svg)(?:\?[^\s]*)?)/gi;
        result = result.replace(commonRegex, function(url) {
            return `<div class="q-image-wrap"><img src="${url}" onclick="zoomImage('${url}')" onerror="this.parentElement.style.display='none'" class="q-image"></div>`;
        });

        return result;
    }

    // ===== Image Zoom =====
    window.zoomImage = function(url) {
        const overlay = document.getElementById('imageOverlay');
        document.getElementById('overlayImg').src = url;
        overlay.classList.add('active');
        overlay.classList.remove('zoomed');
        document.body.style.overflow = 'hidden';
    }
    window.toggleSecondaryZoom = function(e) {
        e.stopPropagation();
        document.getElementById('imageOverlay').classList.toggle('zoomed');
    }
    window.handleOverlayClick = function(e) {
        if (e.target.id === 'imageOverlay' || e.target.tagName !== 'IMG') {
            closeZoom();
        }
    }
    window.closeZoom = function() {
        const overlay = document.getElementById('imageOverlay');
        overlay.classList.remove('active', 'zoomed');
        document.body.style.overflow = '';
    }

    // ===== Display Question =====
    function displayQuestion() {
        const q = questions[currentQuestionIndex];
        const container = document.getElementById('questionContainer');
        const total = questions.length;
        const current = currentQuestionIndex + 1;

        // Update progress
        document.getElementById('examProgress').textContent = `ข้อที่ ${current} / ${total}`;
        document.getElementById('progressBarFill').style.width = `${(current / total) * 100}%`;

        // Badge
        const badge = q.type === 'writing'
            ? '<div class="q-type-badge q-type-writing">📜 อัตนัย (ข้อเขียน)</div>'
            : '<div class="q-type-badge q-type-choice">🔘 ปรนัย (เลือกตอบ)</div>';

        // Question image
        let qImgHtml = '';
        if (q.image_url) {
            qImgHtml = `<div class="q-image-wrap"><img src="${q.image_url}" onclick="zoomImage('${q.image_url}')" class="q-image"></div>`;
        }

        // Content
        let contentHtml = '';
        if (q.type === 'writing') {
            contentHtml = `
                <div>
                    <p class="writing-label">✍️ พิมพ์คำตอบของคุณ</p>
                    <textarea
                        id="writing-input"
                        oninput="checkWritingInput(this)"
                        class="writing-area"
                        rows="5"
                        placeholder="พิมพ์คำตอบที่นี่..."
                        style="white-space: pre-wrap; tab-size: 4; -moz-tab-size: 4;"
                    ></textarea>
                </div>
            `;
        } else {
            const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            let optsHtml = '';
            q.options.forEach((opt, i) => {
                optsHtml += `
                    <div class="opt-card" onclick="selectOption(this, ${i})">
                        <div class="opt-radio"></div>
                        <span class="opt-letter">${letters[i] || (i+1)}</span>
                        <span class="opt-label">${formatWithImages(opt)}</span>
                        <input type="radio" name="q${currentQuestionIndex}" value="${opt.replace(/"/g, '&quot;')}" style="display:none">
                    </div>
                `;
            });
            contentHtml = `<div class="options-list">${optsHtml}</div>`;
        }

        container.innerHTML = `
            <div class="animate-in">
                ${badge}
                <div class="q-text">${formatWithImages(q.question)}</div>
                ${qImgHtml}
                ${contentHtml}
            </div>
        `;

        if (q.type === 'writing') {
            const txtArea = document.getElementById('writing-input');
            if (txtArea) {
                txtArea.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        e.preventDefault();
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        this.value = this.value.substring(0, start) + "\t" + this.value.substring(end);
                        this.selectionStart = this.selectionEnd = start + 1;
                        checkWritingInput(this);
                    }
                });
            }
        }

        // Timer
        startQuestionTimer();

        // Buttons
        document.getElementById('nextQuestionBtn').disabled = true;
        document.getElementById('submitExamBtn').disabled = true;

        if (currentQuestionIndex === questions.length - 1) {
            document.getElementById('nextQuestionBtn').classList.add('hidden');
            document.getElementById('submitExamBtn').classList.remove('hidden');
        } else {
            document.getElementById('nextQuestionBtn').classList.remove('hidden');
            document.getElementById('submitExamBtn').classList.add('hidden');
        }
    }

    // ===== Writing Input Check =====
    window.checkWritingInput = function(el) {
        const has = el.value.trim().length > 0;
        document.getElementById('nextQuestionBtn').disabled = !has;
        document.getElementById('submitExamBtn').disabled = !has;
    }

    // ===== Select Option =====
    function selectOption(el, index) {
        document.querySelectorAll('.opt-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
        document.getElementById('nextQuestionBtn').disabled = false;
        document.getElementById('submitExamBtn').disabled = false;
    }

    // ===== Timer =====
    function startQuestionTimer(startFrom) {
        clearInterval(timerInterval);
        const currentQ = questions[currentQuestionIndex];
        let timeLeft = (typeof startFrom === 'number' && startFrom > 0)
            ? Math.floor(startFrom)
            : (currentQ.type === 'writing' ? timeLimitWriting : timeLimitChoice);
        questionTimeLeft = timeLeft;

        const timerEl = document.getElementById('timerNumber');
        const timerBox = document.getElementById('questionTimer');
        timerEl.textContent = timeLeft;
        timerBox.classList.remove('warning');
        timerEl.classList.remove('timer-pulse');

        if (currentQ.type === 'writing') {
            timerBox.style.borderColor = 'rgba(245,158,11,0.25)';
            timerBox.style.background = 'rgba(245,158,11,0.12)';
            timerEl.style.color = 'var(--amber)';
        } else {
            timerBox.style.borderColor = '';
            timerBox.style.background = '';
            timerEl.style.color = '';
        }

        timerInterval = setInterval(() => {
            questionTimeLeft--;
            timeLeft = questionTimeLeft;
            timerEl.textContent = questionTimeLeft;
            if (questionTimeLeft <= 10) {
                timerBox.classList.add('warning');
                timerEl.classList.add('timer-pulse');
            }
            if (questionTimeLeft <= 0) {
                clearInterval(timerInterval);
                handleTimeOut();
            }
        }, 1000);
    }

    function handleTimeOut() {
        if (!isExamActive || isPaused || isSubmitting) return;
        const currentQ = questions[currentQuestionIndex];
        
        if (currentQ.type === 'writing') {
            Swal.fire({
                title: 'หมดเวลา!',
                text: 'หมดเวลาทำข้อสอบอัตนัย ระบบกำลังบันทึกและส่งคำตอบโดยอัตโนมัติ',
                icon: 'warning',
                timer: 2500,
                showConfirmButton: false,
                allowOutsideClick: false
            });
            setTimeout(() => {
                collectAnswer();
                finishExam();
            }, 2500);
        } else {
            Swal.fire({ title: 'หมดเวลา!', text: 'ระบบกำลังเปลี่ยนไปข้อถัดไป', icon: 'info', timer: 1500, showConfirmButton: false });
            setTimeout(() => {
                collectAnswer();
                currentQuestionIndex++;
                if (currentQuestionIndex < questions.length) {
                    displayQuestion();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    finishExam();
                }
            }, 1500);
        }
    }

    // ===== Navigation =====
    document.getElementById('nextQuestionBtn').onclick = moveToNext;

    function moveToNext() {
        collectAnswer();
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            displayQuestion();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function collectAnswer() {
        var q = questions[currentQuestionIndex];
        var ansValue = '';

        if (q.type === 'writing') {
            var input = document.getElementById('writing-input');
            ansValue = input ? input.value : '';
        } else {
            var selected = document.querySelector('input[name="q' + currentQuestionIndex + '"]:checked');
            ansValue = selected ? selected.value : '';
        }

        studentAnswers.push({
            questionId: String(q.id),
            selectedOption: String(ansValue)
        });
    }

    // ===== Submit =====
    document.getElementById('submitExamBtn').onclick = () => {
        Swal.fire({
            title: 'ต้องการส่งข้อสอบ?',
            text: "เมื่อส่งแล้วจะไม่สามารถกลับมาแก้ไขได้อีก",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ส่งข้อสอบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                collectAnswer();
                finishExam();
            }
        });
    };

    function initOverallTimer() {
        const box = document.getElementById('overallTimerBox');
        const display = document.getElementById('overallTimerNumber');
        if (!box || !display) return;

        box.classList.remove('hidden');
        clearInterval(overallTimerInterval);
        overallTimerTick();
        overallTimerInterval = setInterval(overallTimerTick, 1000);
    }

    function overallTimerTick() {
        const box = document.getElementById('overallTimerBox');
        const display = document.getElementById('overallTimerNumber');
        if (!box || !display || !isExamActive) {
            clearInterval(overallTimerInterval);
            return;
        }
        if (overallTimeLeft <= 0) {
            clearInterval(overallTimerInterval);
            isExamActive = false;
            Swal.fire({
                icon: 'warning',
                title: 'หมดเวลาทำข้อสอบ!',
                text: 'หมดเวลาทำข้อสอบรวมแล้ว ระบบกำลังส่งคำตอบของท่านโดยอัตโนมัติ',
                allowOutsideClick: false,
                confirmButtonText: 'ตกลง'
            }).then(() => {
                collectAnswer();
                finishExam();
            });
            return;
        }

        const m = Math.floor(overallTimeLeft / 60);
        const s = overallTimeLeft % 60;
        display.textContent = m.toString().padStart(2, '0') + ':' + s.toString().padStart(2, '0');

        if (overallTimeLeft <= 60) { // Warning: 1 minute left
            box.style.borderColor = '#ef4444';
            display.style.color = '#ef4444';
            display.classList.add('timer-pulse');
        }

        overallTimeLeft--;
    }

    async function finishExam() {
        if (isSubmitting) return;
        isSubmitting = true;
        isExamActive = false;
        clearInterval(timerInterval);
        clearInterval(overallTimerInterval);
        clearInterval(statusInterval);

        if (blurHandler) window.removeEventListener('blur', blurHandler);
        if (visibilityHandler) document.removeEventListener('visibilitychange', visibilityHandler);
        if (keydownBlocker) window.removeEventListener('keydown', keydownBlocker, true);

        var totalTime = Math.floor((new Date() - startTime) / 1000);

        Swal.fire({ title: 'กำลังส่งคำตอบ...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});

        const formData = new FormData();
        formData.append('answers', JSON.stringify(studentAnswers));
        formData.append('totalTimeSpent', totalTime);
        formData.append('cheatingFlag', cheatingCount >= maxCheatingLimit ? 'YES' : 'NO');
        formData.append('cheatingCount', cheatingCount);

        try {
            const response = await fetch('/api/exam/submit', { method: 'POST', body: formData });
            const res = await response.json();
            Swal.close();
            if (res.success) {
                window.location.href = '/result';
            } else {
                Swal.fire('Error', res.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
            }
        } catch (err) {
            Swal.close();
            Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
        }
    }

    async function finishExamWithCheating(reason) {
        if (isSubmitting) return;
        isSubmitting = true;
        isExamActive = false;
        clearInterval(timerInterval);
        clearInterval(overallTimerInterval);
        clearInterval(statusInterval);

        if (blurHandler) window.removeEventListener('blur', blurHandler);
        if (visibilityHandler) document.removeEventListener('visibilitychange', visibilityHandler);
        if (keydownBlocker) window.removeEventListener('keydown', keydownBlocker, true);

        var totalTime = Math.floor((new Date() - startTime) / 1000);

        Swal.fire({ title: 'กำลังบันทึกพฤติกรรมโกง...', allowOutsideClick: false, didOpen: () => Swal.showLoading()});

        const formData = new FormData();
        formData.append('cheatingReason', reason);
        formData.append('totalTimeSpent', totalTime);

        try {
            const response = await fetch('/api/exam/cheat', { method: 'POST', body: formData });
            Swal.close();
            window.location.href = '/result';
        } catch (err) {
            Swal.close();
            window.location.href = '/result';
        }
    }
  </script>
</body>
</html>

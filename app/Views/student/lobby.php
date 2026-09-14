<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>ห้องพักคอยการสอบ | <?= esc($websiteName) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    html { scroll-behavior: smooth; }
    * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
    body {
      font-family: 'K2D', sans-serif;
      background: #080c14;
      min-height: 100vh;
      color: #f1f5f9;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1.5rem;
      overflow-y: auto;
    }
    .bg-animation {
      position: fixed; inset: 0; z-index: -1; pointer-events: none;
      background: radial-gradient(circle at 20% 30%, rgba(236,72,153,0.06) 0%, transparent 60%),
                  radial-gradient(circle at 80% 70%, rgba(14,165,233,0.06) 0%, transparent 60%),
                  #080c14;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.02);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 28px;
      box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.6);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .glass-card:hover {
      border-color: rgba(255, 255, 255, 0.09);
    }

    /* Pulse animation for waiting status indicator */
    .pulse-glow {
      box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);
      animation: pulse-ring 2s infinite;
    }
    @keyframes pulse-ring {
      0% { box-shadow: 0 0 0 0px rgba(236, 72, 153, 0.5); }
      70% { box-shadow: 0 0 0 12px rgba(236, 72, 153, 0); }
      100% { box-shadow: 0 0 0 0px rgba(236, 72, 153, 0); }
    }

    /* Digital clock style */
    .digital-clock-box {
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 20px;
      padding: 1.25rem;
      width: 100%;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .digital-clock-box::before {
      content: '';
      position: absolute; top: 0; left: 0; width: 100%; height: 2px;
      background: linear-gradient(90deg, #ec4899, #0ea5e9);
    }
    .digital-time {
      font-size: 3.25rem;
      font-weight: 800;
      font-family: 'Courier New', Courier, monospace;
      background: linear-gradient(135deg, #ec4899, #0ea5e9);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      line-height: 1;
      letter-spacing: -1px;
    }

    /* Scrollbar style */
    .custom-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.01);
      border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.08);
      border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
      background: rgba(255,255,255,0.15);
    }

    /* Animations */
    .animate-scale-in {
      animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes scaleIn {
      from { transform: scale(0.96); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    #countdownOverlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(8, 12, 20, 0.96);
      backdrop-filter: blur(30px);
      z-index: 99999;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }
    #countdownOverlay.active { display: flex; }
    .countdown-num {
      font-size: 11rem;
      font-weight: 900; line-height: 1;
      background: linear-gradient(135deg, #ec4899, #0ea5e9);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      filter: drop-shadow(0 0 60px rgba(236,72,153,0.6));
      animation: countdownPop 1.0s ease infinite;
    }
    @keyframes countdownPop {
      0%   { transform: scale(1.6); opacity: 0; }
      40%  { transform: scale(0.95); opacity: 1; }
      70%  { transform: scale(1.03); }
      100% { transform: scale(1); }
    }
  </style>
</head>
<body>
  <?= view('common/loader') ?>

  <div class="bg-animation"></div>

  <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-5 gap-6 animate-scale-in">
    
    <!-- LEFT PANEL: Info & Timer -->
    <div class="lg:col-span-2 flex flex-col gap-6">
      
      <!-- Exam Info Card -->
      <div class="glass-card p-6 flex flex-col justify-between items-center text-center h-full relative">
        <div class="w-full">
          <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-pink-500/10 text-pink-500 text-2xl mb-3 mx-auto pulse-glow">
            ⏳
          </div>

          <?php
            $isMidterm = (mb_strpos($examType, 'กลางภาค') !== false);
            $isFinal   = (mb_strpos($examType, 'ปลายภาค') !== false);
            if ($isMidterm) {
                $badgeClass = 'bg-gradient-to-r from-amber-500/25 via-orange-500/20 to-amber-500/25 text-amber-300 border border-amber-400/60 shadow-[0_0_15px_rgba(245,158,11,0.25)]';
                $badgeIcon  = '🎯';
            } elseif ($isFinal) {
                $badgeClass = 'bg-gradient-to-r from-purple-500/30 via-fuchsia-500/25 to-pink-500/25 text-purple-200 border border-purple-400/60 shadow-[0_0_15px_rgba(168,85,247,0.25)]';
                $badgeIcon  = '🏁';
            } else {
                $badgeClass = 'bg-sky-500/20 text-sky-300 border border-sky-400/50 shadow-[0_0_10px_rgba(14,165,233,0.15)]';
                $badgeIcon  = '📝';
            }
          ?>
          <div class="mb-2.5">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-xs font-black tracking-wide <?= $badgeClass ?>">
              <span class="text-sm"><?= $badgeIcon ?></span>
              <span><?= esc($examType) ?></span>
            </span>
          </div>

          <h2 class="text-2xl font-extrabold text-white mb-2 leading-snug"><?= esc($subjectName) ?></h2>
          <div class="inline-flex flex-wrap gap-2 justify-center mb-6">
            <span class="px-3 py-1 bg-slate-800 text-slate-300 border border-white/5 rounded-full text-xs font-bold">รหัสวิชา: <?= esc($subjectCode) ?></span>
            <span class="px-3 py-1 bg-pink-500/10 text-pink-400 border border-pink-500/20 rounded-full text-xs font-bold">ผู้สอน: <?= esc($teacherName) ?></span>
          </div>
          
          <p class="text-slate-400 text-sm font-semibold mb-6">กรุณาสแตนด์บายรอสักครู่...<br>ระบบจะเริ่มทำข้อสอบพร้อมกันทันทีเมื่อคุณครูสั่งเริ่มสอบ</p>
        </div>

        <div class="w-full space-y-4">
          <!-- Exam Rules and Warning -->
          <div class="w-full bg-rose-500/10 border border-rose-500/30 rounded-2xl p-4 text-left shadow-lg shadow-rose-500/5">
            <h4 class="text-rose-400 font-bold text-sm mb-2 flex items-center gap-2">
              <span class="text-lg animate-pulse">⚠️</span> กฎกติกาและข้อควรระวังในการสอบ
            </h4>
            <ul class="text-slate-300 text-xs space-y-1.5 list-disc list-inside">
              <li>ห้าม <span class="text-rose-400 font-bold">สลับแท็บ ย่อหน้าต่าง หรือเปิดโปรแกรมอื่น</span> ขณะทำการสอบเด็ดขาด</li>
              <li>ระบบเปิดใช้งานระบบป้องกันการทุจริต <span class="text-rose-400 font-bold">(Anti-Cheating)</span></li>
              <li>หากระบบตรวจพบพฤติกรรมน่าสงสัยเกินกำหนด <span class="text-rose-400 font-bold">ระบบจะปรับตก (0 คะแนน)</span> ทันที</li>
              <li>กรุณาเชื่อมต่ออินเทอร์เน็ตให้เสถียรก่อนเริ่มทำข้อสอบ</li>
            </ul>
          </div>

          <div class="digital-clock-box">
            <div id="mobileDigitalTime" class="digital-time">00:00:00</div>
            <div id="mobileWaitingTime" class="waiting-duration">รอมาแล้ว: 0 นาที 0 วินาที</div>
          </div>

          <div class="w-full">
            <div id="statusLabel" class="w-full py-3 bg-pink-500/10 border border-pink-500/25 rounded-2xl text-pink-400 font-extrabold text-sm transition-all duration-300 flex items-center justify-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-pink-500 animate-ping"></span>
              📱 กำลังเชื่อมต่อระบบห้องพักคอย...
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- RIGHT PANEL: Waiting List -->
    <div class="lg:col-span-3">
      <div class="glass-card p-6 flex flex-col h-full min-h-[420px] lg:min-h-[500px]">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-white/5 pb-4 mb-4">
          <div class="flex items-center gap-2.5">
            <span class="text-xl">👥</span>
            <h3 class="text-lg font-extrabold text-white">รายชื่อผู้รอเข้าสอบทั้งหมด</h3>
          </div>
          <span id="waitingPlayersCount" class="px-3.5 py-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-extrabold rounded-full">
            1 คนกำลังรอสอบ
          </span>
        </div>

        <!-- Student Grid List Container -->
        <div class="flex-grow overflow-y-auto custom-scroll pr-1.5 max-h-[350px] lg:max-h-[420px]">
          <div id="waitingPlayersGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            
            <!-- My Card (Always shown first) -->
            <div class="p-3.5 rounded-2xl bg-gradient-to-tr from-pink-500/10 to-sky-500/10 border border-pink-500/30 flex items-center gap-3.5 relative shadow-md shadow-pink-500/5">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center text-sm font-extrabold text-white shrink-0">
                👑
              </div>
              <div class="min-w-0 flex-grow">
                <p class="text-sm font-black text-pink-400 truncate"><?= esc($studentName) ?> (ฉัน)</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                  <span class="px-1.5 py-0.5 bg-pink-500/20 text-pink-400 text-[10px] font-bold rounded">ม.<?= esc($studentRoom) ?></span>
                  <span class="text-[10px] text-slate-400 font-bold">เลขที่ <?= esc($studentId) ?></span>
                </div>
              </div>
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
            </div>

          </div>
        </div>

      </div>
    </div>

  </div>

  <!-- Countdown Overlay -->
  <div id="countdownOverlay">
    <div class="text-center">
      <p class="text-base font-black text-amber-500 uppercase tracking-[0.3em] mb-4">เตรียมตัว!</p>
      <div id="countdownNum" class="countdown-num">5</div>
      <p class="text-sm font-bold text-slate-400 mt-4 tracking-wider">ระบบจะเปลี่ยนหน้าเข้าทำข้อสอบโดยอัตโนมัติ...</p>
    </div>
  </div>

  <script>
    const studentEmail = "<?= esc($studentEmail) ?>";
    const studentName = "<?= esc($studentName) ?>";
    const studentRoom = "<?= esc($studentRoom) ?>";
    const studentId = "<?= esc($studentId) ?>";
    var waitingStartTime = new Date();
    var clockInterval;
    var syncInterval;
    var statusInterval;
    var pollingActive = true;

    window.onload = () => {
        // Start local clock
        clockInterval = setInterval(updateClock, 1000);
        updateClock();

        // Start server sync
        syncWithServer();
        syncInterval = setInterval(syncWithServer, 4000);

        // Start status check
        checkExamStatus();
        statusInterval = setInterval(checkExamStatus, 4000);
    };

    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('th-TH', { hour12: false });
        const timeEl = document.getElementById('mobileDigitalTime');
        if (timeEl) timeEl.textContent = timeStr;

        const diff = Math.floor((now - waitingStartTime) / 1000);
        const mins = Math.floor(diff / 60);
        const secs = diff % 60;
        const durationEl = document.getElementById('mobileWaitingTime');
        if (durationEl) durationEl.textContent = `รอมาแล้ว: ${mins} นาที ${secs} วินาที`;
    }

    function getInitials(name) {
        if (!name) return '?';
        const parts = name.trim().split(' ');
        if (parts.length >= 2) {
            return parts[0].substring(0, 1) + parts[1].substring(0, 1);
        }
        return name.substring(0, 2);
    }

    async function syncWithServer() {
        if (!pollingActive) return;

        const formData = new FormData();
        formData.append('x', '0');
        formData.append('y', '0');
        formData.append('score', '0');

        try {
            const response = await fetch('/api/lobby/sync', {
                method: 'POST',
                body: formData
            });
            const res = await response.json();
            
            if (res.redirect && pollingActive) {
                pollingActive = false;
                clearInterval(syncInterval);
                clearInterval(statusInterval);
                clearInterval(clockInterval);
                window.location.href = '/';
                return;
            }

            if (res.success && res.players) {
                const grid = document.getElementById('waitingPlayersGrid');
                const countEl = document.getElementById('waitingPlayersCount');
                const statusLabel = document.getElementById('statusLabel');
                
                if (countEl) {
                    countEl.textContent = `${res.players.length} คนกำลังรอสอบ`;
                }
                if (statusLabel) {
                    statusLabel.className = "w-full py-3 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl text-emerald-400 font-extrabold text-sm transition-all duration-300 flex items-center justify-center gap-2";
                    statusLabel.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500"></span> 🟢 เชื่อมต่อระบบห้องพักคอยแล้ว`;
                }
                
                if (grid) {
                    let html = `
                        <!-- My Card -->
                        <div class="p-3.5 rounded-2xl bg-gradient-to-tr from-pink-500/10 to-sky-500/10 border border-pink-500/30 flex items-center gap-3.5 relative shadow-md shadow-pink-500/5 animate-scale-in">
                          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-rose-500 flex items-center justify-center text-sm font-extrabold text-white shrink-0">
                            👑
                          </div>
                          <div class="min-w-0 flex-grow">
                            <p class="text-sm font-black text-pink-400 truncate">${studentName} (ฉัน)</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                              <span class="px-1.5 py-0.5 bg-pink-500/20 text-pink-400 text-[10px] font-bold rounded">ม.${studentRoom}</span>
                              <span class="text-[10px] text-slate-400 font-bold">เลขที่ ${studentId}</span>
                            </div>
                          </div>
                          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                        </div>
                    `;
                    res.players.forEach(p => {
                        if (p.name !== studentName) {
                            const initials = getInitials(p.name);
                            html += `
                                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/5 flex items-center gap-3.5 relative hover:bg-white/10 transition-all duration-200 animate-scale-in">
                                  <div class="w-10 h-10 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center text-xs font-black text-slate-350 shrink-0">
                                    ${initials}
                                  </div>
                                  <div class="min-w-0 flex-grow">
                                    <p class="text-sm font-bold text-slate-200 truncate">${p.name}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                      <span class="px-1.5 py-0.5 bg-sky-500/10 text-sky-400 text-[10px] font-bold rounded border border-sky-500/20">ม.${p.room || '-'}</span>
                                      <span class="text-[10px] text-slate-400 font-bold">เลขที่ ${p.student_number || '-'}</span>
                                    </div>
                                  </div>
                                  <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0 shadow-sm shadow-emerald-500"></span>
                                </div>
                            `;
                        }
                    });
                    grid.innerHTML = html;
                }
            }
        } catch (err) {
            console.error("Sync error", err);
            const statusLabel = document.getElementById('statusLabel');
            if (statusLabel) {
                statusLabel.className = "w-full py-3 bg-red-500/10 border border-red-500/25 rounded-2xl text-red-500 font-extrabold text-sm transition-all duration-300 flex items-center justify-center gap-2 animate-pulse";
                statusLabel.innerHTML = `<span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm shadow-red-500"></span> 🔴 การเชื่อมต่อขัดข้อง กำลังลองใหม่...`;
            }
        }
    }

    async function checkExamStatus() {
        if (!pollingActive) return;

        try {
            const response = await fetch('/api/exam/status?examId=<?= $examId ?>');
            const res = await response.json();
            if (res.examStatus === 'Started' && pollingActive) {
                pollingActive = false;
                clearInterval(syncInterval);
                clearInterval(statusInterval);
                clearInterval(clockInterval);
                startCountdown(5, () => {
                    window.location.href = '/exam';
                });
            }
        } catch (err) {
            console.error("Failed to check status", err);
        }
    }

    function startCountdown(seconds, callback) {
        const el = document.getElementById('countdownOverlay');
        const numEl = document.getElementById('countdownNum');
        el.classList.add('active');
        let left = seconds;
        numEl.textContent = left;

        const tick = setInterval(() => {
            left--;
            if (left <= 0) {
                clearInterval(tick);
                el.classList.remove('active');
                callback();
                return;
            }
            numEl.textContent = left;
        }, 1000);
    }
  </script>
</body>
</html>

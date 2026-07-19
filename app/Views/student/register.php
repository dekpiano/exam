<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>หน้าแรก | <?= esc($websiteName) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Load jQuery and Select2 CDN -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            skjPink: '#ec4899',
            skjBlue: '#0ea5e9',
          }
        }
      }
    }
  </script>
  <style>
    html {
      scroll-behavior: smooth;
    }

    /* Select2 Dark/Glassmorphism theme overrides */
    .select2-container--default .select2-selection--single {
      background: rgba(0, 0, 0, 0.3) !important;
      border: 1.5px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 16px !important;
      height: auto !important;
      padding: 0.55rem 0.6rem !important;
      color: #fff !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #fff !important;
      font-size: 1rem !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 100% !important;
      right: 10px !important;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--open .select2-selection--single {
      border-color: #0ea5e9 !important;
      background: rgba(0, 0, 0, 0.5) !important;
      box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15) !important;
    }

    .select2-dropdown {
      background: #0f172a !important;
      border: 1.5px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 16px !important;
      overflow: hidden !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
      background: rgba(0, 0, 0, 0.3) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 10px !important;
      color: #fff !important;
      outline: none !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #ec4899 !important;
      color: white !important;
    }

    .select2-container--default .select2-results__option[aria-selected="true"] {
      background-color: rgba(14, 165, 233, 0.2) !important;
      color: white !important;
    }

    .select2-results__option {
      color: #cbd5e1 !important;
      font-size: 0.9rem !important;
      padding: 8px 16px !important;
    }

    .select2-search--dropdown {
      padding: 10px !important;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    body {
      font-family: 'K2D', sans-serif;
      background: #090d16;
      min-height: 100vh;
      color: #f8fafc;
      overflow-x: hidden;
    }

    .bg-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      overflow: hidden;
      pointer-events: none;
      background: radial-gradient(circle at center, #111827 0%, #090d16 100%);
    }

    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.35;
      animation: move 20s infinite alternate ease-in-out;
    }

    .blob-1 {
      width: 350px;
      height: 350px;
      background: #ec4899;
      top: -10%;
      left: -10%;
    }

    .blob-2 {
      width: 450px;
      height: 450px;
      background: #0ea5e9;
      bottom: -10%;
      right: -10%;
      animation-delay: -7s;
    }

    @keyframes move {
      from {
        transform: translate(0, 0) scale(1);
      }

      to {
        transform: translate(60px, 60px) scale(1.15);
      }
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .glass-card:hover {
      background: rgba(255, 255, 255, 0.06);
      border-color: rgba(255, 255, 255, 0.15);
      transform: translateY(-4px);
    }

    .input-box {
      width: 100%;
      background: rgba(0, 0, 0, 0.3);
      border: 1.5px solid rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      padding: 0.85rem 1.1rem;
      color: #fff;
      font-size: 1rem;
      transition: all 0.3s;
      outline: none;
    }

    .input-box:focus {
      border-color: #0ea5e9;
      background: rgba(0, 0, 0, 0.5);
      box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
    }

    .btn-pink-blue {
      background: linear-gradient(135deg, #ec4899, #0ea5e9);
      color: white;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
    }

    .btn-pink-blue:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(236, 72, 153, 0.5);
    }

    .btn-pink-blue:active {
      transform: scale(0.98);
    }

    /* Modal Animation */
    .modal-overlay {
      transition: opacity 0.3s ease-in-out;
      pointer-events: none;
      opacity: 0;
    }

    .modal-overlay.active {
      pointer-events: auto;
      opacity: 1;
    }

    .modal-content {
      transform: scale(0.9) translateY(20px);
      transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active .modal-content {
      transform: scale(1) translateY(0);
    }

    /* Flowing Gradient Text Animation */
    .animate-gradient-text {
      background-size: 200% auto;
      animation: textGradientFlow 6s linear infinite;
    }
    @keyframes textGradientFlow {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    
    /* Smooth Letter-by-Letter Entrance Animation */
    .animate-text-reveal {
      animation: textReveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      opacity: 0;
      transform: translateY(15px);
    }
    @keyframes textReveal {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Floating UI Badges Animations */
    .animate-float-slow {
      animation: floatBadge 4s ease-in-out infinite;
    }
    .animate-float-delay {
      animation: floatBadge 4s ease-in-out infinite;
      animation-delay: 2s;
    }
    @keyframes floatBadge {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    
    .animate-spin-slow {
      animation: spinSlow 12s linear infinite;
    }
    @keyframes spinSlow {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    /* Mascot Float Animation */
    .animate-mascot-float {
      animation: mascotFloat 5s ease-in-out infinite;
    }
    @keyframes mascotFloat {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-12px) rotate(2.5deg); }
    }
  </style>
</head>

<body class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 relative">
  <?= view('common/loader') ?>

  <div class="bg-animation">
    <canvas id="plexus-canvas" class="fixed inset-0 w-full h-full z-[-2] pointer-events-none opacity-65"></canvas>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
  </div>

  <!-- Container -->
  <div class="max-w-7xl mx-auto w-full">

    <!-- Hero Section -->
    <div class="flex flex-col lg:grid lg:grid-cols-12 gap-4 lg:gap-8 items-center mb-6 lg:mb-12 relative z-10 pt-2 lg:pt-4 w-full text-center lg:text-left">
      
      <!-- Left Column: Title, Description & Badges (Col Span 7) -->
      <div class="w-full lg:col-span-7 flex flex-col items-center lg:items-start justify-center">
        <!-- Badge -->
        <div class="w-fit mb-3 px-4 py-1 bg-gradient-to-r from-skjPink/10 to-skjBlue/10 border border-skjPink/20 text-skjPink text-[10px] sm:text-[11px] font-bold rounded-full uppercase tracking-wider flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-skjPink animate-ping"></span>
          ระบบสอบออนไลน์โฉมใหม่
        </div>
        
        <!-- Main Title -->
        <h1 class="text-3xl sm:text-4xl lg:text-6xl font-black tracking-tight leading-tight bg-gradient-to-r from-skjPink via-purple-400 to-skjBlue bg-clip-text text-transparent mb-2 lg:mb-4 animate-gradient-text animate-text-reveal" style="animation-delay: 0.1s;">
          <?= esc($websiteName) ?>
        </h1>

        <!-- Short Description (Always visible, font size optimized for mobile) -->
        <p class="text-slate-300 text-xs sm:text-sm lg:text-base leading-relaxed font-semibold opacity-85 mb-4 lg:mb-8 animate-text-reveal max-w-xl px-2 lg:px-0" style="animation-delay: 0.3s;">
          ยินดีต้อนรับเข้าสู่ระบบคลังข้อสอบอัจฉริยะ โรงเรียนสวนกุหลาบวิทยาลัย จิรประวัติ นครสวรรค์ พิมพ์ค้นหาวิชาของคุณที่ด้านล่าง เพื่อเข้าทำข้อสอบได้ทันที!
        </p>

        <!-- Search Box (Centered/Stacked) -->
        <div class="w-full max-w-2xl bg-white/5 border border-white/10 p-2.5 sm:p-3 lg:p-4 rounded-2xl sm:rounded-3xl backdrop-blur-xl flex justify-center shadow-2xl relative overflow-hidden group">
          <div class="absolute inset-0 bg-gradient-to-r from-skjPink/5 to-skjBlue/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <div class="relative w-full z-10">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
              <svg class="h-5 w-5 text-slate-400 group-hover:text-skjBlue transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </span>
            <input type="text" id="searchInput" oninput="filterExams()"
              class="w-full pl-11 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl sm:rounded-2xl text-xs sm:text-sm focus:outline-none focus:border-skjPink focus:ring-4 focus:ring-skjPink/15 text-white placeholder-slate-500 transition-all duration-300 font-semibold"
              placeholder="ค้นหาชื่อวิชา รหัสวิชา หรือครูผู้สอนเพื่อเริ่มทำข้อสอบ...">
          </div>
        </div>
      </div>

      <!-- Right Column: Interactive Mascot (Shown on all screens, centered and size-optimized) -->
      <div class="w-full lg:col-span-5 flex justify-center items-center relative min-h-[160px] sm:min-h-[220px] lg:min-h-[320px]">
        <!-- Mascot Glow Background -->
        <div class="absolute w-36 h-36 sm:w-56 lg:w-72 h-36 sm:h-56 lg:h-72 bg-gradient-to-tr from-skjPink to-skjBlue rounded-full blur-3xl opacity-15 animate-pulse"></div>
        
        <!-- Framed Interactive Mascot -->
        <div class="relative group/mascot select-none px-4 scale-95 sm:scale-100">
          <!-- Floating UI Badges (Scaled down on mobile) -->
          <div class="absolute -top-3 -left-4 sm:-left-6 bg-white/5 border border-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl sm:rounded-2xl text-[9px] sm:text-[11px] font-bold text-emerald-400 shadow-lg flex items-center gap-1 animate-float-slow z-20">
            💯 A+ Score
          </div>
          <div class="absolute bottom-4 -right-4 sm:-right-6 bg-white/5 border border-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl sm:rounded-2xl text-[9px] sm:text-[11px] font-bold text-sky-400 shadow-lg flex items-center gap-1 animate-float-delay z-20">
            ✍️ Exam Ready
          </div>
          <div class="absolute top-1/2 -right-5 sm:-right-8 transform -translate-y-1/2 bg-white/5 border border-white/10 backdrop-blur-md p-2 sm:p-3 rounded-full text-sm sm:text-lg shadow-lg animate-spin-slow z-20">
            ✏️
          </div>
          
          <!-- Large Mascot Frame -->
          <div class="p-3 sm:p-4 rounded-[32px] sm:rounded-[40px] bg-gradient-to-tr from-white/5 to-white/0 border border-white/10 backdrop-blur-md shadow-2xl flex items-center justify-center">
            <img src="/uploads/cute_student_exam.png" alt="SKJ Exam Mascot" class="w-36 h-36 sm:w-52 lg:w-72 sm:h-52 lg:h-72 object-contain drop-shadow-[0_15px_30px_rgba(236,72,153,0.35)] animate-mascot-float">
          </div>
        </div>
      </div>
    </div>

    <!-- Exams Grid Container -->
    <main class="w-full mt-8">
      <div id="examsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pb-12">
        <?php if (empty($exams)): ?>
          <div class="col-span-full text-center py-20 bg-white/5 border border-white/10 rounded-3xl">
            <span class="text-5xl">📭</span>
            <p class="text-slate-400 font-bold mt-4">ยังไม่มีรายวิชาเปิดสอบในขณะนี้</p>
          </div>
        <?php else: ?>
          <?php foreach ($exams as $exam): ?>
            <div class="exam-card glass-card rounded-[32px] p-6 flex flex-col justify-between" style="display: none;"
              data-subject-name="<?= esc($exam['subject_name']) ?>" data-subject-code="<?= esc($exam['subject_code']) ?>"
              data-teacher-name="<?= esc($exam['teacher_name']) ?>" data-learning-area="<?= esc($exam['learning_area']) ?>">

              <div>
                <!-- Badge Group -->
                <div class="flex justify-between items-center mb-4">
                  <span
                    class="px-3 py-1 bg-white/5 border border-white/10 text-slate-300 rounded-full text-[10px] font-bold">
                    📚 <?= esc($exam['learning_area']) ?>
                  </span>

                  <?php if ($exam['exam_status'] === 'Started'): ?>
                    <span
                      class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-[10px] font-bold">
                      <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                      กำลังสอบ
                    </span>
                  <?php elseif ($exam['exam_status'] === 'Finished'): ?>
                    <span
                      class="px-3 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full text-[10px] font-bold">
                      เสร็จสิ้นการสอบ
                    </span>
                  <?php else: ?>
                    <span
                      class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-full text-[10px] font-bold">
                      รอเข้าห้องสอบ
                    </span>
                  <?php endif; ?>
                </div>

                <!-- Subject Info -->
                <h3 class="text-lg font-black text-white leading-snug mb-1 line-clamp-2"><?= esc($exam['subject_name']) ?>
                </h3>
                <p class="text-skjBlue font-extrabold text-xs mb-4">รหัสวิชา: <?= esc($exam['subject_code']) ?>
                  &nbsp;|&nbsp;
                  <?= esc($exam['exam_type']) ?>    <?php if (!empty($exam['academic_year']) || !empty($exam['semester'])): ?>
                    &nbsp;|&nbsp; ปีการศึกษา: <?= esc($exam['academic_year']) ?>/<?= esc($exam['semester']) ?><?php endif; ?>
                </p>

                <!-- Additional detail info -->
                <div class="space-y-1.5 border-t border-white/5 pt-4 mb-6">
                  <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>👨‍🏫 ครูผู้สอน:</span>
                    <span class="font-bold text-slate-200"><?= esc($exam['teacher_name']) ?></span>
                  </div>
                  <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>⏱️ เวลาทำปรนัย / อัตนัย:</span>
                    <span class="font-bold text-slate-200"><?= esc($exam['time_limit_choice']) ?> /
                      <?= esc($exam['time_limit_writing']) ?> วินาที</span>
                  </div>
                  <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>⏳ เวลาทำข้อสอบรวม:</span>
                    <span
                      class="font-bold text-slate-200"><?= (empty($exam['exam_duration']) || (int) $exam['exam_duration'] === 0) ? 'ไม่จำกัดเวลา' : esc($exam['exam_duration']) . ' นาที' ?></span>
                  </div>
                  <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>📝 จำนวนข้อสอบ:</span>
                    <span class="font-bold text-slate-200"><?= esc($exam['num_questions']) ?> ข้อ</span>
                  </div>
                </div>
              </div>

              <!-- Action Button -->
              <div>
                <?php if ($exam['exam_status'] === 'Finished'): ?>
                  <button disabled
                    class="w-full py-3.5 bg-white/5 border border-white/5 text-slate-500 font-extrabold rounded-2xl text-sm cursor-not-allowed">
                    ปิดระบบเข้าสอบ 🔒
                  </button>
                <?php else: ?>
                  <div class="flex gap-2">
                    <button
                      onclick="openRegisterModal('<?= esc($exam['id']) ?>', '<?= esc($exam['subject_name']) ?>', '<?= esc($exam['teacher_name']) ?>')"
                      class="flex-1 py-3.5 btn-pink-blue font-extrabold rounded-2xl text-sm flex items-center justify-center gap-2">
                      เข้าห้องสอบ 🚀
                    </button>
                    <button onclick="openShareModal('<?= esc($exam['id']) ?>', '<?= esc($exam['subject_name']) ?>')"
                      class="px-4 py-3.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-slate-350 hover:text-white transition-all flex items-center justify-center shadow-lg cursor-pointer"
                      title="แชร์ลิงก์ / QR Code">
                      🔗
                    </button>
                  </div>
                <?php endif; ?>
              </div>

            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </main>

    <!-- Teacher Entry Footer -->
    <footer class="py-3 flex justify-center items-center z-20 mt-auto">
      <a href="/teacher"
        class="px-5 py-2.5 bg-white/5 hover:bg-gradient-to-r hover:from-skjPink hover:to-skjBlue border border-white/10 rounded-2xl text-xs font-bold text-slate-350 hover:text-white transition-all duration-300 flex items-center gap-1.5 backdrop-blur-md hover:shadow-lg hover:shadow-skjPink/20 hover:scale-105 active:scale-95">
        👨‍🏫 เข้าสู่ระบบสำหรับครูผู้สอน
      </a>
    </footer>

  </div>

  <!-- Share Modal -->
  <div id="shareModal"
    class="modal-overlay fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div
      class="modal-content glass-card w-full max-w-md rounded-[32px] p-8 border border-white/10 relative text-center">

      <!-- Close Button -->
      <button onclick="closeShareModal()"
        class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Modal Header -->
      <div class="mb-4">
        <span
          class="px-3.5 py-1.5 bg-skjBlue/10 text-skjBlue rounded-full text-xs font-black uppercase tracking-widest border border-skjBlue/20 mb-3 inline-block">ลิงก์เข้าสอบ
          & QR Code</span>
        <h3 id="shareSubjectTitle" class="text-lg font-black text-white leading-tight">วิชา</h3>
      </div>

      <!-- QR Code Image -->
      <div
        class="bg-white p-4 rounded-3xl w-60 h-60 mx-auto flex items-center justify-center shadow-lg shadow-white/5 mb-4">
        <img id="shareQRCodeImg" src="" alt="QR Code" class="w-full h-full">
      </div>

      <!-- Link Box -->
      <div class="space-y-3">
        <input type="text" id="shareLinkField" class="input-box text-center font-mono text-xs select-all" readonly>

        <button onclick="copyShareLink()"
          class="w-full py-3.5 btn-pink-blue font-extrabold rounded-2xl text-sm flex items-center justify-center gap-2 cursor-pointer">
          📋 คัดลอกลิงก์ส่งให้นักเรียน
        </button>
      </div>

    </div>
  </div>

  <!-- Registration Modal -->
  <div id="registerModal"
    class="modal-overlay fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
    <div class="modal-content glass-card w-full max-w-md rounded-[32px] p-8 border border-white/10 relative">

      <!-- Close Button -->
      <button onclick="closeRegisterModal()"
        class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Modal Header -->
      <div class="text-center mb-6">
        <span
          class="px-3.5 py-1.5 bg-skjPink/10 text-skjPink rounded-full text-xs font-black uppercase tracking-widest border border-skjPink/20 mb-3 inline-block">กรอกข้อมูลผู้เข้าสอบ</span>
        <h3 id="modalSubjectTitle" class="text-xl font-black text-white leading-tight">คณิตศาสตร์พื้นฐาน</h3>
        <p id="modalTeacherName" class="text-xs text-slate-500 font-bold mt-1">ครูผู้สอน: ครูสมชาย ใจดี</p>
      </div>

      <!-- Registration Form -->
      <form id="studentInfoForm" action="/api/register" method="POST" class="space-y-4">
        <?= csrf_field() ?>
        <input type="hidden" id="examIdField" name="examId">

        <div>
          <label for="studentName"
            class="text-xs font-black text-skjBlue ml-1 mb-1.5 block uppercase tracking-wider">ชื่อ-นามสกุล</label>
          <input type="text" id="studentName" name="name" class="input-box" placeholder="กรอกชื่อ-นามสกุลของคุณ (เช่น นายสมชาย ใจดี)"
            pattern="^[ก-๙a-zA-Z\s\.]+$" title="กรุณากรอกเฉพาะตัวอักษรภาษาไทย/อังกฤษ และเว้นวรรคเท่านั้น" required>
        </div>

        <div>
          <label for="studentCode"
            class="text-xs font-black text-skjBlue ml-1 mb-1.5 block uppercase tracking-wider">เลขประจำตัวนักเรียน</label>
          <input type="text" id="studentCode" name="studentCode" class="input-box" placeholder="กรอกเลขประจำตัว 5 หลัก"
            inputmode="numeric" pattern="[0-9]{5}" maxlength="5" title="กรุณากรอกตัวเลข 5 หลักเท่านั้น" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="studentId"
              class="text-xs font-black text-skjBlue ml-1 mb-1.5 block uppercase tracking-wider">เลขที่</label>
            <input type="number" id="studentId" name="studentId" class="input-box" placeholder="เช่น 1"
              inputmode="numeric" min="1" max="100" title="กรุณากรอกเลขที่ 1-100" required>
          </div>
          <div>
            <label for="studentRoom"
              class="text-xs font-black text-skjBlue ml-1 mb-1.5 block uppercase tracking-wider">ห้องเรียน</label>
            <select id="studentRoom" name="studentRoom" class="input-box w-full" style="width: 100%" required>
              <option value="" disabled selected>เลือกห้องเรียน</option>
              <?php 
              // Generate rooms ม.1 to ม.6, rooms 1 to 15
              for ($grade = 1; $grade <= 6; $grade++) {
                  for ($room = 1; $room <= 15; $room++) {
                      $roomVal = "$grade/$room";
                      echo "<option value=\"$roomVal\">ม.$roomVal</option>";
                  }
              }
              ?>
            </select>
          </div>
        </div>

        <div>
          <label for="studentEmail"
            class="text-xs font-black text-skjBlue ml-1 mb-1.5 block uppercase tracking-wider">อีเมลสำหรับใช้สอบ</label>
          <input type="email" id="studentEmail" name="email" class="input-box" placeholder="กรอกอีเมลของคุณ (เช่น yourname@gmail.com)"
            required>
        </div>

        <button type="submit"
          class="w-full py-4 btn-pink-blue font-extrabold rounded-2xl text-base shadow-lg mt-6 flex items-center justify-center gap-2">
          ยืนยันลงชื่อเข้าสอบ 🚀
        </button>
      </form>

    </div>
  </div>

  <script>

    window.onload = () => {
      // Initialize Select2 room dropdown
      $('#studentRoom').select2({
        placeholder: 'ค้นหาและเลือกห้องเรียน',
        allowClear: false,
        dropdownParent: $('#registerModal') // Ensure dropdown displays correctly inside modal
      });

      // Run initial filter to show search placeholder
      filterExams();

      const urlParams = new URLSearchParams(window.location.search);
      const examId = urlParams.get('exam_id');
      if (examId) {
        const cards = document.querySelectorAll('.exam-card');
        let foundCard = null;
        cards.forEach(card => {
          const btn = card.querySelector('button[onclick*="openRegisterModal"]');
          if (btn) {
            const onclickStr = btn.getAttribute('onclick');
            if (onclickStr && onclickStr.includes(examId)) {
              foundCard = card;
            }
          }
        });

        if (foundCard) {
          const subName = foundCard.getAttribute('data-subject-name');
          const teacherName = foundCard.getAttribute('data-teacher-name');
          openRegisterModal(examId, subName, teacherName);
        }
      }
    };

    function openShareModal(examId, subjectName) {
      const link = window.location.origin + '/?exam_id=' + examId;
      document.getElementById('shareSubjectTitle').textContent = subjectName;
      document.getElementById('shareLinkField').value = link;
      document.getElementById('shareQRCodeImg').src = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(link);

      const modal = document.getElementById('shareModal');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeShareModal() {
      const modal = document.getElementById('shareModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }

    function copyShareLink() {
      const copyText = document.getElementById('shareLinkField');
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(copyText.value).then(() => {
        Swal.fire({
          icon: 'success',
          title: 'คัดลอกลิงก์สำเร็จ',
          text: 'คัดลอกลิงก์เรียบร้อย ส่งให้นักเรียนได้เลย',
          timer: 1500,
          showConfirmButton: false
        });
      }).catch(err => {
        Swal.fire({
          icon: 'error',
          title: 'ล้มเหลว',
          text: 'ไม่สามารถคัดลอกลิงก์ได้โดยอัตโนมัติ กรุณากดคลุมดำและคัดลอกด้วยตนเอง'
        });
      });
    }

    function filterExams() {
      const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
      const cards = document.querySelectorAll('.exam-card');
      let visibleCount = 0;

      const isSearchActive = searchVal.length > 0;

      cards.forEach(card => {
        if (!isSearchActive) {
          card.style.display = 'none';
          return;
        }

        const subName = card.getAttribute('data-subject-name').toLowerCase();
        const subCode = card.getAttribute('data-subject-code').toLowerCase();
        const teacher = card.getAttribute('data-teacher-name').toLowerCase();

        const matchesSearch = subName.includes(searchVal) || subCode.includes(searchVal) || teacher.includes(searchVal);

        if (matchesSearch) {
          card.style.display = 'flex';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      // Handle empty filtered list state
      let emptyMsg = document.getElementById('emptyFilteredMessage');
      if (emptyMsg) emptyMsg.remove();

      if (!isSearchActive) {
        emptyMsg = document.createElement('div');
        emptyMsg.id = 'emptyFilteredMessage';
        emptyMsg.className = 'col-span-full text-center py-14 bg-white/3 border border-white/5 rounded-[32px] backdrop-blur-md p-6';
        emptyMsg.innerHTML = `
          <span class="text-4xl block mb-2">🔍</span>
          <h4 class="text-white font-extrabold text-lg mb-1">พิมพ์ค้นหาวิชาสอบของคุณด้านบน</h4>
          <p class="text-slate-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">ค้นหาด้วย ชื่อวิชา, รหัสวิชา หรือชื่อของคุณครูผู้สอน เพื่อเริ่มต้นทำข้อสอบน้า 💖</p>
        `;
        document.getElementById('examsGrid').appendChild(emptyMsg);
      } else if (visibleCount === 0) {
        emptyMsg = document.createElement('div');
        emptyMsg.id = 'emptyFilteredMessage';
        emptyMsg.className = 'col-span-full text-center py-16 bg-white/5 border border-white/5 rounded-3xl backdrop-blur-md p-8';
        emptyMsg.innerHTML = `
          <span class="text-6xl block mb-4">😿</span>
          <h3 class="text-white font-black text-xl mb-1">ไม่พบวิชาสอบนี้</h3>
          <p class="text-slate-400 text-xs sm:text-sm">กรุณาตรวจสอบการสะกดคำค้นหา หรือค้นหาด้วยชื่อผู้สอนแทนอีกครั้งนะ</p>
        `;
        document.getElementById('examsGrid').appendChild(emptyMsg);
      }
    }

    // Modal control
    function openRegisterModal(examId, subjectName, teacherName) {
      document.getElementById('examIdField').value = examId;
      document.getElementById('modalSubjectTitle').textContent = subjectName;
      document.getElementById('modalTeacherName').textContent = `ครูผู้สอน: ${teacherName}`;

      const modal = document.getElementById('registerModal');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeRegisterModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }

    // Submit handler
    document.getElementById('studentInfoForm').onsubmit = async (e) => {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      Swal.fire({
        title: 'กำลังลงทะเบียนเข้าสอบ...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        const res = await response.json();
        Swal.close();

        if (res.success) {
          if (res.exam_status === 'Started' && res.join_policy === 'anytime') {
            window.location.href = '/exam';
          } else {
            window.location.href = '/lobby';
          }
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'ฮั่นแน่ะ ^_^',
            text: res.message || 'ไม่สามารถเข้าร่วมวิชาสอบนี้ได้',
            confirmButtonColor: '#0ea5e9'
          });
        }
      } catch (error) {
        Swal.close();
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          text: 'ไม่สามารถเชื่อมต่อระบบได้ในขณะนี้',
          confirmButtonColor: '#ec4899'
        });
      }
    };

    // Premium Plexus Particle Background Animation (High-DPI Sharp & Interactive)
    (() => {
      const canvas = document.getElementById('plexus-canvas');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      let particles = [];
      const baseParticleCount = 45;
      const connectionDist = 130;
      
      // Mouse Interaction coordinates
      let mouse = { x: null, y: null, radius: 180 };
      
      window.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        mouse.x = (e.clientX - rect.left) * (canvas.width / rect.width);
        mouse.y = (e.clientY - rect.top) * (canvas.height / rect.height);
      });
      
      window.addEventListener('mouseleave', () => {
        mouse.x = null;
        mouse.y = null;
      });

      // Touch events support for mobile devices
      window.addEventListener('touchmove', (e) => {
        if (e.touches.length > 0) {
          const rect = canvas.getBoundingClientRect();
          mouse.x = (e.touches[0].clientX - rect.left) * (canvas.width / rect.width);
          mouse.y = (e.touches[0].clientY - rect.top) * (canvas.height / rect.height);
        }
      });
      
      window.addEventListener('touchend', () => {
        mouse.x = null;
        mouse.y = null;
      });

      // Spawn extra particles on click / tap
      window.addEventListener('click', (e) => {
        const rect = canvas.getBoundingClientRect();
        const clickX = (e.clientX - rect.left) * (canvas.width / rect.width);
        const clickY = (e.clientY - rect.top) * (canvas.height / rect.height);
        for (let i = 0; i < 5; i++) {
          particles.push(new Particle(clickX, clickY));
          if (particles.length > 80) particles.shift();
        }
      });

      function resize() {
        const dpr = window.devicePixelRatio || 1;
        canvas.width = window.innerWidth * dpr;
        canvas.height = window.innerHeight * dpr;
        ctx.scale(dpr, dpr);
        
        particles = [];
        for (let i = 0; i < baseParticleCount; i++) {
          particles.push(new Particle());
        }
      }
      window.addEventListener('resize', resize);

      class Particle {
        constructor(x, y) {
          const dpr = window.devicePixelRatio || 1;
          const logicalWidth = canvas.width / dpr;
          const logicalHeight = canvas.height / dpr;
          
          this.x = x !== undefined ? x / dpr : Math.random() * logicalWidth;
          this.y = y !== undefined ? y / dpr : Math.random() * logicalHeight;
          this.vx = (Math.random() - 0.5) * 0.4;
          this.vy = (Math.random() - 0.5) * 0.4;
          this.radius = Math.random() * 2.2 + 1.2;
          this.color = Math.random() > 0.5 ? '#ec4899' : '#0ea5e9';
          this.pulseSpeed = 0.015 + Math.random() * 0.02;
          this.pulseDir = 1;
        }
        update() {
          const dpr = window.devicePixelRatio || 1;
          const logicalWidth = canvas.width / dpr;
          const logicalHeight = canvas.height / dpr;

          // Mouse attraction force
          if (mouse.x !== null && mouse.y !== null) {
            const mx = mouse.x / dpr;
            const my = mouse.y / dpr;
            const dx = mx - this.x;
            const dy = my - this.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < mouse.radius) {
              const force = (mouse.radius - dist) / mouse.radius;
              this.vx += (dx / dist) * force * 0.02;
              this.vy += (dy / dist) * force * 0.02;
            }
          }

          // Apply velocity limit
          const speed = Math.sqrt(this.vx * this.vx + this.vy * this.vy);
          if (speed > 1.2) {
            this.vx = (this.vx / speed) * 1.2;
            this.vy = (this.vy / speed) * 1.2;
          }

          this.x += this.vx;
          this.y += this.vy;

          if (this.x < 0 || this.x > logicalWidth) this.vx *= -1;
          if (this.y < 0 || this.y > logicalHeight) this.vy *= -1;

          // Pulsate radius
          this.radius += this.pulseSpeed * this.pulseDir;
          if (this.radius > 4 || this.radius < 1.2) this.pulseDir *= -1;
        }
        draw() {
          ctx.beginPath();
          ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
          ctx.fillStyle = this.color;
          ctx.shadowBlur = 8;
          ctx.shadowColor = this.color;
          ctx.fill();
          ctx.shadowBlur = 0;
        }
      }

      resize();

      function drawConnections() {
        const dpr = window.devicePixelRatio || 1;
        
        // Connections between particles
        for (let i = 0; i < particles.length; i++) {
          for (let j = i + 1; j < particles.length; j++) {
            const dx = particles[i].x - particles[j].x;
            const dy = particles[i].y - particles[j].y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < connectionDist) {
              const alpha = (1 - dist / connectionDist) * 0.25;
              ctx.beginPath();
              ctx.moveTo(particles[i].x, particles[i].y);
              ctx.lineTo(particles[j].x, particles[j].y);
              ctx.strokeStyle = particles[i].color === '#ec4899' ? `rgba(236, 72, 153, ${alpha})` : `rgba(14, 165, 233, ${alpha})`;
              ctx.lineWidth = 1.0;
              ctx.stroke();
            }
          }
        }

        // Connections from mouse pointer
        if (mouse.x !== null && mouse.y !== null) {
          const mx = mouse.x / dpr;
          const my = mouse.y / dpr;
          particles.forEach(p => {
            const dx = p.x - mx;
            const dy = p.y - my;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < mouse.radius - 20) {
              const alpha = (1 - dist / (mouse.radius - 20)) * 0.4;
              ctx.beginPath();
              ctx.moveTo(p.x, p.y);
              ctx.lineTo(mx, my);
              ctx.strokeStyle = `rgba(255, 255, 255, ${alpha})`;
              ctx.lineWidth = 1.3;
              ctx.stroke();
            }
          });
        }
      }

      function animate() {
        const dpr = window.devicePixelRatio || 1;
        const logicalWidth = canvas.width / dpr;
        const logicalHeight = canvas.height / dpr;

        ctx.clearRect(0, 0, logicalWidth, logicalHeight);
        particles.forEach(p => {
          p.update();
          p.draw();
        });
        drawConnections();
        requestAnimationFrame(animate);
      }
      animate();
    })();
  </script>
</body>

</html>
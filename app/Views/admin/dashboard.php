<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ระบบจัดการข้อสอบสำหรับครู | <?= esc($websiteName) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf-8"
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Custom DataTables Dark Theme Styling */
    .dataTables_wrapper {
      color: #cbd5e1 !important;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
      background-color: #1e293b !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: #fff !important;
      border-radius: 8px !important;
      padding: 4px 8px !important;
      outline: none !important;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
      color: #94a3b8 !important;
      margin-bottom: 12px !important;
      margin-top: 12px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
      color: #cbd5e1 !important;
      border-radius: 6px !important;
      border: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
      background: linear-gradient(to right, #ec4899, #0ea5e9) !important;
      color: #fff !important;
      border: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
      background: rgba(255, 255, 255, 0.1) !important;
      color: #fff !important;
    }

    table.dataTable {
      border-collapse: collapse !important;
    }

    table.dataTable thead th {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: #94a3b8 !important;
      font-weight: 700 !important;
      background-color: #0f172a !important;
    }

    table.dataTable tbody tr {
      background-color: transparent !important;
    }

    table.dataTable tbody td {
      border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    table.dataTable.no-footer {
      border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    body {
      font-family: 'K2D', sans-serif;
      background: #090d16;
      color: #f8fafc;
      min-height: 100vh;
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
      opacity: 0.25;
      animation: move 20s infinite alternate ease-in-out;
    }

    .blob-1 {
      width: 400px;
      height: 400px;
      background: #ec4899;
      top: -10%;
      left: -10%;
    }

    .blob-2 {
      width: 500px;
      height: 500px;
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

    .glass-panel {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .active-tab {
      background: linear-gradient(135deg, rgba(236, 72, 153, 0.28), rgba(14, 165, 233, 0.20)) !important;
      border-left: 4px solid #ec4899 !important;
      color: #ffffff !important;
      box-shadow: inset 0 0 0 1px rgba(236, 72, 153, 0.15), 0 8px 20px rgba(236, 72, 153, 0.10);
      transform: translateX(2px);
    }

    .active-tab::after {
      content: '✓';
      margin-left: auto;
      color: #f9a8d4;
      font-weight: 900;
      font-size: 13px;
    }

    .monitor-choice-active {
      position: relative;
      outline: 2px solid rgba(255, 255, 255, 0.22);
      outline-offset: 1px;
    }


    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.02);
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.2);
    }
  </style>
</head>

<body class="pb-12">
  <?= view('common/loader') ?>

  <div class="bg-animation">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
  </div>

  <!-- Header -->
  <header class="glass-panel sticky top-0 z-50 px-6 py-4 flex justify-between items-center border-b border-white/5">
    <div class="flex items-center gap-3">
      <?php if (!empty($logoUrl)): ?>
        <img src="<?= esc($logoUrl) ?>" alt="logo" class="h-10">
      <?php else: ?>
        <div
          class="h-10 w-10 rounded-full bg-gradient-to-tr from-pink-500 to-sky-400 flex items-center justify-center text-white text-sm font-black shadow-lg shadow-pink-500/20">
          SKJ</div>
      <?php endif; ?>
      <div>
        <h1 class="font-extrabold text-lg bg-gradient-to-r from-pink-500 to-sky-400 bg-clip-text text-transparent">
          <?= esc($websiteName) ?>
        </h1>
        <p class="text-xs text-slate-400">ระบบจัดการข้อสอบสำหรับครูผู้ควบคุม (Teacher Portal)</p>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="text-right hidden sm:block">
        <p class="text-sm font-bold text-slate-200"><?= esc($teacherName) ?></p>
        <p class="text-[10px] text-sky-400 font-bold uppercase tracking-wider">ผู้ตรวจสอบระดับโรงเรียน</p>
      </div>
      <a href="<?= (isset($activeExamId) && $activeExamId !== '' && $activeExamId !== 'global') ? ('/teacher/manual?exam_id=' . esc($activeExamId)) : '/teacher/manual' ?>"
        class="px-3.5 py-2 bg-gradient-to-r from-sky-500/15 to-purple-500/15 hover:from-sky-500/25 hover:to-purple-500/25 text-sky-300 hover:text-white font-bold rounded-xl text-xs border border-sky-400/30 transition-all flex items-center gap-1.5 shadow-sm">
        📖 คู่มือการใช้งาน
      </a>
      <button onclick="logout()"
        class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-bold rounded-xl text-sm border border-red-500/20 transition-all">
        ออกจากระบบ 🚪
      </button>
    </div>
  </header>

  <main class="max-w-full mx-auto px-4 sm:px-8 lg:px-12 mt-8">

    <!-- TIER 1: LOBBY SCREEN (Subject Cards & Lobby overview) -->
    <div id="lobby-screen" class="space-y-6">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h2 class="text-2xl font-black text-white">🏫 รายวิชาสอบทั้งหมดของคุณครู</h2>
          <p class="text-xs text-slate-400">เลือกการ์ดวิชาด้านล่างเพื่อเข้าสู่เมนู คลังข้อสอบ ล็อบบี้การสอบ
            หรือดูผลคะแนน</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">
          <button onclick="openExamModal()"
            class="w-full sm:w-auto px-5 py-3 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl text-xs transition-all shadow-lg shadow-pink-500/20 flex items-center justify-center gap-2">
            ➕ เพิ่มวิชาสอบใหม่
          </button>
          <a href="/teacher/settings"
            class="w-full sm:w-auto px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl text-xs transition-all border border-white/5 flex items-center justify-center gap-2">
            ⚙️ ตั้งค่าส่วนกลาง
          </a>
          <a href="/teacher/manual"
            class="w-full sm:w-auto px-5 py-3 bg-gradient-to-r from-sky-500/20 to-purple-500/20 hover:from-sky-500/30 hover:to-purple-500/30 text-sky-300 font-bold rounded-xl text-xs transition-all border border-sky-400/30 flex items-center justify-center gap-2 shadow-md">
            📖 คู่มือการใช้งานระบบ
          </a>
        </div>
      </div>

      <!-- Teacher Exams Filter & Search Bar -->
      <div class="glass-panel rounded-2xl p-4 border border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-xs">🔍</span>
          <input type="text" id="teacherExamSearch" oninput="renderLobbyScreen()"
            class="w-full pl-9 pr-4 py-2 bg-black/40 border border-white/10 rounded-xl text-xs font-semibold text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition-colors"
            placeholder="ค้นหาชื่อวิชา รหัสวิชา หรือรอบสอบ...">
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
          <!-- Academic Year Filter for Teacher (Defaults to latest year always) -->
          <div class="flex items-center gap-1.5 bg-black/40 border border-white/10 px-3 py-1.5 rounded-xl">
            <span class="text-xs font-black text-slate-300 flex items-center gap-1">
              <span>📅</span> ปีการศึกษา:
            </span>
            <select id="teacherYearFilter" onchange="onTeacherYearFilterChange()"
              class="bg-slate-900 border border-pink-500/30 text-pink-300 text-xs font-black rounded-lg px-2.5 py-1 outline-none focus:border-pink-500 cursor-pointer">
              <!-- populated dynamically via JS -->
            </select>
          </div>

          <span class="text-xs font-bold text-slate-400 mr-1 hidden sm:inline">รอบสอบ:</span>
          <button type="button" onclick="setTeacherExamFilter('all')" id="t-filter-all"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border border-white/20 bg-white/20 text-white shadow-md cursor-pointer flex items-center gap-1.5">
            🌟 ทั้งหมด
          </button>
          <button type="button" onclick="setTeacherExamFilter('กลางภาค')" id="t-filter-midterm"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border border-amber-500/40 bg-amber-500/10 text-amber-300 hover:bg-amber-500/20 cursor-pointer flex items-center gap-1.5">
            🎯 สอบกลางภาค
          </button>
          <button type="button" onclick="setTeacherExamFilter('ปลายภาค')" id="t-filter-final"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border border-purple-500/40 bg-purple-500/10 text-purple-200 hover:bg-purple-500/20 cursor-pointer flex items-center gap-1.5">
            🏁 สอบปลายภาค
          </button>
          <button type="button" onclick="setTeacherExamFilter('other')" id="t-filter-other"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border border-sky-500/40 bg-sky-500/10 text-sky-300 hover:bg-sky-500/20 cursor-pointer flex items-center gap-1.5">
            📝 สอบอื่นๆ
          </button>
        </div>
      </div>

      <!-- Subject Cards Container (Separated by Midterm & Final Sections) -->
      <div id="exam-cards-grid" class="space-y-10 pt-2">
        <!-- Populated dynamically via JS -->
      </div>
    </div>

    <!-- TIER 2: WORKSPACE SCREEN (When a subject is selected) -->
    <div id="workspace-screen" class="hidden grid grid-cols-1 lg:grid-cols-6 gap-6">

      <!-- Left Navigation Menu -->
      <div class="lg:col-span-1 flex flex-col gap-4">
        <!-- Card 1: Main Management Menu -->
        <div class="glass-panel rounded-2xl p-4 flex flex-col gap-2 border border-white/5">
          <!-- Active Subject Card Header -->
          <div
            class="p-4 mb-2 bg-gradient-to-tr from-pink-500/10 to-sky-400/10 border border-pink-500/10 rounded-xl relative">
            <span id="workspace-subject-code" class="text-xs text-pink-400 font-bold block">CODE</span>
            <h4 id="workspace-subject-name" class="text-sm font-bold text-white truncate">SUBJECT NAME</h4>
            <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/5">
              <span id="workspace-subject-status"
                class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-500/10 text-slate-400 border border-slate-500/20">Waiting</span>
              <span id="workspace-subject-area" class="text-[10px] text-slate-400 font-bold">Area</span>
            </div>
            <div id="workspace-subject-type-badge" class="mt-2 pt-2 border-t border-white/5"></div>
          </div>

          <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-3 mb-1">เมนูการจัดการ</p>
          <?php if (isset($activeExamId) && $activeExamId !== '' && $activeExamId !== 'global'): ?>
            <a href="/teacher/questions?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-questions"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-350 hover:bg-white/5 transition-all flex items-center gap-2">
              📝 คลังข้อสอบรายวิชา
            </a>
            <a href="/teacher/monitor?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-monitor"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              🖥️ จอภาพควบคุม (Lobby)
            </a>
            <a href="/teacher/results?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-results"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              📊 ผลสอบและตรวจอัตนัย
            </a>
            <a href="/teacher/logs?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-logs"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              🚨 บันทึกความเสี่ยง/ทุจริต
            </a>
            <a href="/teacher/exam-settings?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-exam-settings"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              ⚙️ ตั้งค่าวิชาสอบ
            </a>
            <a href="/teacher/manual?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-manual"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              📖 คู่มือการใช้งานระบบ
            </a>
          <?php else: ?>
            <a href="/teacher/settings" id="tab-btn-settings"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              ⚙️ ตั้งค่าระบบส่วนกลาง
            </a>
            <a href="/teacher/manual" id="tab-btn-manual"
              class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm text-slate-300 hover:bg-white/5 transition-all flex items-center gap-2">
              📖 คู่มือการใช้งานระบบ
            </a>
          <?php endif; ?>

          <div class="border-t border-white/5 my-2 pt-2">
            <a href="/teacher/dashboard"
              class="w-full text-left px-4 py-3 bg-slate-900 hover:bg-slate-800 border border-white/5 text-slate-300 font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-2">
              🔙 กลับไปเลือกวิชาอื่น
            </a>
          </div>
        </div>

        <!-- Card 2: Real-time Monitor Lobby Card (Highly Prominent & Vibrant) -->
        <?php if (isset($activeExamId) && $activeExamId !== '' && $activeExamId !== 'global'): ?>
          <div
            class="relative overflow-hidden rounded-2xl p-[2px] bg-gradient-to-r from-pink-500 via-purple-500 to-sky-500 shadow-xl shadow-pink-500/10 hover:shadow-pink-500/20 transition-all duration-300 hover:scale-[1.03]">
            <div class="bg-[#0f172a] rounded-[14px] p-4 flex flex-col gap-3 relative">

              <!-- Active Neon Indicator Dot -->
              <span class="absolute top-4 right-4 flex h-3 w-3">
                <span
                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
              </span>

              <p
                class="text-[10px] font-black text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-sky-400 uppercase tracking-widest px-1">
                ระบบวิเคราะห์เรียลไทม์</p>

              <a href="/teacher/monitor?exam_id=<?= esc($activeExamId) ?>" id="tab-btn-monitor"
                class="w-full text-center px-4 py-3.5 bg-gradient-to-r from-pink-500 via-purple-600 to-sky-500 hover:from-pink-600 hover:via-purple-750 hover:to-sky-600 text-white font-black rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-lg shadow-pink-500/20 active:scale-[0.97] cursor-pointer">
                🖥️ จอภาพควบคุม (Lobby)
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right Content Panel -->
      <div class="lg:col-span-5">

        <!-- SECTION 2: QUESTIONS -->
        <div id="section-questions" class="section-content hidden space-y-6">
          <!-- Header Panel -->
          <div
            class="glass-panel rounded-3xl p-6 border border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
              <h2 class="text-xl font-black text-white flex items-center gap-2">📝 คลังข้อสอบของรายวิชา</h2>
              <p class="text-xs text-slate-400 mt-1">คลังคำถามแบ่งออกเป็นข้อสอบปรนัย (ตัวเลือก) และข้อสอบอัตนัย
                (เขียนตอบ)</p>
            </div>
            <div class="flex flex-wrap gap-2">
              <button onclick="openQuestionModal()"
                class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-xl text-xs transition-all shadow-md shadow-pink-500/20">
                ➕ เพิ่มข้อสอบใหม่
              </button>
              <button onclick="openImportModal()"
                class="px-4 py-2 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-400/25 font-bold rounded-xl text-xs transition-all">
                📥 นำเข้าข้อสอบ
              </button>
              <button onclick="clearAllQuestions()"
                class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/25 font-bold rounded-xl text-xs transition-all">
                🗑️ ล้างคำถามทั้งหมด
              </button>
            </div>
          </div>

          <!-- Dashboard Cards Grid -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Total Points -->
            <div class="glass-panel rounded-2xl p-6 border border-white/5 flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center text-pink-400 text-xl border border-pink-500/20">
                🏆</div>
              <div>
                <p class="text-xs text-slate-400 font-bold uppercase">คะแนนรวมในคลัง</p>
                <h3 id="q-dash-total-points" class="text-2xl font-black text-white mt-1">0.0 คะแนน</h3>
                <p id="q-dash-points-breakdown" class="text-[10px] text-slate-500 font-semibold mt-1">ปรนัย: 0.0 คะแนน |
                  อัตนัย: 0.0 คะแนน</p>
              </div>
            </div>
            <!-- Card 2: Total Questions -->
            <div class="glass-panel rounded-2xl p-6 border border-white/5 flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 text-xl border border-sky-500/20">
                📝</div>
              <div>
                <p class="text-xs text-slate-400 font-bold uppercase">จำนวนข้อสอบทั้งหมด</p>
                <h3 id="q-dash-total-count" class="text-2xl font-black text-white mt-1">0 ข้อ</h3>
                <p id="q-dash-count-breakdown" class="text-[10px] text-slate-500 font-semibold mt-1">ปรนัย: 0 ข้อ |
                  อัตนัย: 0 ข้อ</p>
              </div>
            </div>
            <!-- Card 3: Exam Summary config -->
            <div class="glass-panel rounded-2xl p-6 border border-white/5 flex items-center gap-4">
              <div
                class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 text-xl border border-amber-500/20">
                ⚙️</div>
              <div>
                <p class="text-xs text-slate-400 font-bold uppercase">การจัดสอบจริง</p>
                <h3 id="q-dash-active-setting" class="text-2xl font-black text-white mt-1">สุ่มสอบ 0 ข้อ</h3>
                <p id="q-dash-active-pass" class="text-[10px] text-slate-500 font-semibold mt-1">เกณฑ์ผ่าน 0% | เวลา 0
                  นาที</p>
              </div>
            </div>
          </div>

          <!-- Choice Questions Table Section -->
          <div class="glass-panel rounded-3xl p-6 border border-white/5 space-y-4">
            <h3 class="text-sm font-black text-pink-400 flex items-center gap-2 px-1">🔘 ข้อสอบประเภทปรนัย (Multiple
              Choice)</h3>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="border-b border-white/10 text-slate-400 text-xs font-bold">
                    <th class="py-3 px-4">คำถาม (โจทย์) และตัวเลือก</th>
                    <th class="py-3 px-4">เฉลย</th>
                    <th class="py-3 px-4 w-24">คะแนน</th>
                    <th class="py-3 px-4 text-right w-36">การจัดการ</th>
                  </tr>
                </thead>
                <tbody id="choice-questions-list" class="divide-y divide-white/5">
                  <!-- Choice questions load here -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Writing Questions Table Section -->
          <div class="glass-panel rounded-3xl p-6 border border-white/5 space-y-4">
            <h3 class="text-sm font-black text-amber-400 flex items-center gap-2 px-1">📜 ข้อสอบประเภทอัตนัย (Writing /
              Essay)</h3>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="border-b border-white/10 text-slate-400 text-xs font-bold">
                    <th class="py-3 px-4">คำถาม (โจทย์)</th>
                    <th class="py-3 px-4">แนวคำตอบเฉลย</th>
                    <th class="py-3 px-4 w-24">คะแนน</th>
                    <th class="py-3 px-4 text-right w-36">การจัดการ</th>
                  </tr>
                </thead>
                <tbody id="writing-questions-list" class="divide-y divide-white/5">
                  <!-- Writing questions load here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- SECTION 3: MONITOR -->
        <div id="section-monitor" class="section-content hidden">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT COLUMN: Waiting students list -->
            <div class="lg:col-span-2 glass-panel rounded-3xl p-6 border border-white/5 space-y-4">
              <div class="flex justify-between items-center border-b border-white/5 pb-4">
                <div>
                  <h2 class="text-xl font-black text-white flex items-center gap-2">🖥️ จอภาพควบคุมห้องพักคอย (Lobby)
                  </h2>
                  <p class="text-xs text-slate-400 mt-1">* รายชื่อผู้เข้าสอบที่เข้ามารอสแตนด์บายในห้องพักคอย</p>
                </div>
                <span id="active-players-count"
                  class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold rounded-full">
                  0 คนกำลังพักคอย
                </span>
              </div>

              <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                  <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-xs uppercase font-bold">
                      <th class="py-3 px-4">ชื่อ-นามสกุล</th>
                      <th class="py-3 px-4">เลขที่/ห้อง</th>
                      <th class="py-3 px-4">อีเมล</th>
                      <th class="py-3 px-4">สถานะ</th>
                      <th class="py-3 px-4 text-right">ดำเนินการ</th>
                    </tr>
                  </thead>
                  <tbody id="monitor-list" class="divide-y divide-white/5">
                    <!-- Dynamic content -->
                  </tbody>
                </table>
              </div>
            </div>

            <!-- RIGHT COLUMN: Control Cards -->
            <div class="lg:col-span-1 space-y-6">
              <!-- Card 1: Exam Control -->
              <div class="glass-panel rounded-3xl p-6 border border-white/5 space-y-6">
                <div>
                  <h3 class="text-base font-black text-white flex items-center gap-2">⚙️ แผงควบคุมระบบสอบ</h3>
                  <p class="text-xs text-slate-400 mt-1">ใช้ควบคุมสถานะการสอบของรายวิชา</p>
                </div>

                <!-- Status display -->
                <div class="bg-white/5 p-4 rounded-2xl border border-white/5 flex flex-col gap-2">
                  <span
                    class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">สถานะการสอบปัจจุบัน:</span>
                  <div class="flex items-center">
                    <span id="monitor-status-badge"
                      class="px-3 py-1 text-xs font-black rounded-xl transition-all"></span>
                  </div>
                </div>

                <!-- Action Buttons Stack -->
                <div class="flex flex-col gap-3.5">
                  <button onclick="changeExamStatusDirect('Started')"
                    class="w-full py-4 px-6 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-650 active:scale-[0.98] text-white font-black rounded-2xl text-sm transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 hover:scale-[1.02] cursor-pointer">
                    🚀 เริ่มการสอบ (ให้นักเรียนเข้าทำข้อสอบ)
                  </button>
                  <button onclick="changeExamStatusDirect('Finished')"
                    class="w-full py-4 px-6 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-650 hover:to-rose-650 active:scale-[0.98] text-white font-black rounded-2xl text-sm transition-all shadow-lg shadow-red-500/25 flex items-center justify-center gap-2 hover:scale-[1.02] cursor-pointer">
                    🛑 ปิดการสอบ (ปิดสิทธิ์การเข้าทำข้อสอบ)
                  </button>
                  <button onclick="changeExamStatusDirect('Waiting')"
                    class="w-full py-4 px-6 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-650 active:scale-[0.98] text-white font-black rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/30 flex items-center justify-center gap-2 hover:scale-[1.02] cursor-pointer">
                    ⏳ เปิดพักคอย (พักรอสอบในห้อง Lobby)
                  </button>
                </div>
              </div>

              <!-- Card 2: Exam Mode Selection (รูปแบบการสอบ) -->
              <div class="glass-panel rounded-3xl p-6 border border-white/5 space-y-6">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-base font-black text-white flex items-center gap-2">🎮 รูปแบบการสอบ (Exam Mode)</h3>
                    <p class="text-xs text-slate-400 mt-1">กำหนดรูปแบบระบบสอบที่นักเรียนจะได้ทำ</p>
                  </div>
                  <span id="monitor-mode-badge" class="px-3 py-1 text-xs font-black rounded-xl"></span>
                </div>

                <div class="flex flex-col gap-3">
                  <button id="btn-mode-classic" onclick="changeExamModeDirect('classic')"
                    class="w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer">
                    <div class="flex items-center justify-between">
                      <span class="font-extrabold text-white text-sm flex items-center gap-2">📝 แบบมาตรฐาน (Classic Mode)</span>
                      <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-700 text-slate-300 font-semibold">มาตรฐาน</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium font-sans">หน้าทำข้อสอบแบบมาตรฐาน เรียบง่าย สบายตา ชัดเจน</span>
                  </button>

                  <button id="btn-mode-pokemon" onclick="changeExamModeDirect('pokemon')"
                    class="w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer">
                    <div class="flex items-center justify-between">
                      <span class="font-extrabold text-white text-sm flex items-center gap-2">⚡ ผจญภัย Pokémon RPG</span>
                      <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/30 text-amber-300 border border-amber-500/40 font-semibold">Gamified</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium font-sans">เดินผจญภัยบนแผนที่ RPG สไตล์โปเกมอน ตอบคำถามประลอง</span>
                  </button>

                  <button id="btn-mode-escape" onclick="changeExamModeDirect('escape_room')"
                    class="w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer">
                    <div class="flex items-center justify-between">
                      <span class="font-extrabold text-white text-sm flex items-center gap-2">🪙 Coin Quest</span>
                      <span class="text-[10px] px-2 py-0.5 rounded-full bg-violet-500/30 text-violet-300 border border-violet-500/40 font-semibold">Platformer</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium font-sans">วิ่งและกระโดดเก็บเหรียญ แล้วตอบคำถามเพื่อผ่านด่าน</span>
                  </button>
                </div>
              </div>

              <!-- Card 3: Join Policy Selection -->
              <div class="glass-panel rounded-3xl p-6 border border-white/5 space-y-6">
                <div>
                  <h3 class="text-base font-black text-white flex items-center gap-2">🔒 นโยบายการเข้าทำข้อสอบ</h3>
                  <p class="text-xs text-slate-400 mt-1">กำหนดสิทธิ์และกติกาในการลงทะเบียนเข้าสอบของนักเรียน</p>
                </div>

                <div class="flex flex-col gap-3">
                  <button id="btn-policy-anytime" onclick="changeJoinPolicy('anytime')"
                    class="w-full py-3 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer">
                    <span class="font-extrabold text-white text-sm">⏱️ เริ่มสอบตอนไหนก็ได้ (Anytime)</span>
                    <span class="text-[10px] text-slate-400 font-medium font-sans">นักเรียนเข้าสอบได้ตลอดเวลา
                      ตราบใดที่ระบบเปิดและเวลาสอบไม่หมด</span>
                  </button>
                  <button id="btn-policy-lobby" onclick="changeJoinPolicy('lobby_first')"
                    class="w-full py-3 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer">
                    <span class="font-extrabold text-white text-sm">🚪 ต้องเข้าห้องรอสอบก่อน (Lobby First)</span>
                    <span
                      class="text-[10px] text-slate-400 font-medium font-sans">ต้องลงทะเบียนเข้ามาสแตนด์บายก่อนครูเริ่มระบบเท่านั้น
                      หากครูกดเริ่มสอบแล้วเด็กใหม่จะเข้าไม่ได้</span>
                  </button>
                </div>
              </div>

              <!-- Card 1.5: Danger Zone -->
              <div class="glass-panel rounded-3xl p-6 border border-red-500/20 bg-red-500/5 space-y-6">
                <div>
                  <h3 class="text-base font-black text-red-400 flex items-center gap-2">⚠️ เขตอันตราย (Danger Zone)</h3>
                  <p class="text-xs text-slate-400 mt-1">ใช้ยกเลิกการสอบและคัดผู้เรียนออกจากห้องสอบทั้งหมดทันที</p>
                </div>
                <div class="flex flex-col gap-3.5">
                  <button onclick="cancelExamDirect()"
                    class="w-full py-4 px-6 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 active:scale-[0.98] text-white font-black rounded-2xl text-sm transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-2 hover:scale-[1.02] cursor-pointer">
                    🚨 ยกเลิกการสอบทันที (เตะทุกคนออก)
                  </button>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- SECTION 4: RESULTS -->
        <div id="section-results" class="section-content hidden">
          <div class="glass-panel rounded-3xl p-6 border border-white/5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
              <h2 class="text-xl font-black text-white flex items-center gap-2">📊 ผลการสอบและตรวจคำตอบอัตนัย</h2>
              <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                  <span class="text-xs text-slate-400 font-bold">กรองห้องเรียน:</span>
                  <select id="results-filter-room" onchange="renderResultsTable()"
                    class="bg-slate-800 border border-white/10 rounded-xl px-3 py-2 text-white font-bold text-xs outline-none focus:border-pink-500">
                    <option value="ALL">แสดงทุกห้อง</option>
                  </select>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-xs text-slate-400 font-bold">กรองรอบสอบ:</span>
                  <select id="results-filter-round" onchange="renderResultsTable()"
                    class="bg-slate-800 border border-white/10 rounded-xl px-3 py-2 text-white font-bold text-xs outline-none focus:border-pink-500">
                    <option value="ALL">แสดงทุกรอบ</option>
                  </select>
                </div>
                <button onclick="printExamResults()"
                  class="px-4 py-2.5 bg-sky-500/10 hover:bg-sky-500 text-sky-400 hover:text-white border border-sky-500/25 hover:border-transparent font-bold rounded-xl text-xs transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                  🖨️ พิมพ์ประกาศผลสอบ
                </button>
                <button onclick="exportResultsCSV()"
                  class="px-4 py-2.5 bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-white border border-emerald-500/25 hover:border-transparent font-bold rounded-xl text-xs transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                  📥 ส่งออกคะแนน (Excel/CSV)
                </button>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="border-b border-white/10 text-slate-400 text-xs font-bold">
                    <th class="py-3 px-4">ผู้เข้าสอบ</th>
                    <th class="py-3 px-4">เลขที่/ห้อง</th>
                    <th class="py-3 px-4">คะแนนรวม</th>
                    <th class="py-3 px-4">คะแนนปรนัย</th>
                    <th class="py-3 px-4">คะแนนอัตนัย</th>
                    <th class="py-3 px-4">ครั้งที่สอบ (รอบ)</th>
                    <th class="py-3 px-4">เวลาที่ใช้</th>
                    <th class="py-3 px-4">ทุจริต/สลับหน้าจอ</th>
                    <th class="py-3 px-4 text-right">ดำเนินการ</th>
                  </tr>
                </thead>
                <tbody id="results-list" class="divide-y divide-white/5">
                  <!-- Dynamic content -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- SECTION 5: LOGS -->
        <div id="section-logs" class="section-content hidden">
          <div class="glass-panel rounded-3xl p-6 border border-white/5">
            <div
              class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-white/5 mb-6">
              <div>
                <h2 class="text-xl font-black text-white flex items-center gap-2">🚨
                  บันทึกพฤติกรรมเสี่ยงและกิจกรรมน่าสงสัย</h2>
                <p class="text-xs text-slate-400 mt-1">ประวัติการสลับหน้าจอ ออกจากหน้าต่าง หรือเหตุการณ์ผิดปกติอื่นๆ
                  ระหว่างการสอบ</p>
              </div>
              <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <button type="button" onclick="clearLogs()"
                  class="px-4 py-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-xl text-xs font-bold border border-red-500/20 hover:border-transparent transition-all duration-200 cursor-pointer flex items-center gap-1.5">
                  🗑️ ล้างบันทึกความเสี่ยง
                </button>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table id="logsTable" class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="border-b border-white/10 text-slate-400 text-xs font-bold">
                    <th class="py-3 px-4">เวลาประทับ</th>
                    <th class="py-3 px-4">อีเมลนักเรียน</th>
                    <th class="py-3 px-4">กิจกรรมที่เกิดขึ้น</th>
                    <th class="py-3 px-4">ความละเอียด/สเปคเครื่อง</th>
                  </tr>
                </thead>
                <tbody id="logs-list" class="divide-y divide-white/5">
                  <!-- Dynamic content -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- SECTION 5.5: EXAM SETTINGS (WORKSPACE SPECIFIC) -->
        <div id="section-exam-settings" class="section-content hidden">
          <div class="glass-panel rounded-3xl p-6 border border-white/5 shadow-xl">
            <div class="mb-6">
              <h2 class="text-xl font-black text-white flex items-center gap-2">⚙️ ตั้งค่ารายวิชาสอบ</h2>
              <p class="text-xs text-slate-400 mt-1">ตั้งค่าชื่อวิชา รหัสวิชา จำนวนข้อสอบ สิทธิ์การทำสอบ
                และเกณฑ์ผ่านรายวิชานี้</p>
            </div>

            <form id="activeExamSettingsForm" class="space-y-6">
              <input type="hidden" name="id" id="workspace-e-id">
              <input type="hidden" name="teacher_name" id="workspace-e-teacher">
              <input type="hidden" name="exam_type" id="workspace-e-type">

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">รหัสวิชา</label>
                  <input type="text" name="subject_code" id="workspace-e-code"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required placeholder="เช่น ค31101">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">ชื่อวิชา</label>
                  <input type="text" name="subject_name" id="workspace-e-name"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required placeholder="เช่น คณิตศาสตร์พื้นฐาน 1">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">ปีการศึกษา</label>
                  <input type="text" name="academic_year" id="workspace-e-year"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required placeholder="เช่น 2569">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">ภาคเรียนที่ (เทอม)</label>
                  <input type="text" name="semester" id="workspace-e-semester"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required placeholder="เช่น 1 หรือ ฤดูร้อน">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">กลุ่มสาระการเรียนรู้</label>
                  <select name="learning_area" id="workspace-e-area"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500">
                    <option value="ภาษาไทย">ภาษาไทย</option>
                    <option value="คณิตศาสตร์">คณิตศาสตร์</option>
                    <option value="วิทยาศาสตร์และเทคโนโลยี">วิทยาศาสตร์และเทคโนโลยี</option>
                    <option value="สังคมศึกษา ศาสนา และวัฒนธรรม">สังคมศึกษา ศาสนา และวัฒนธรรม</option>
                    <option value="สุขศึกษา และพลศึกษา">สุขศึกษา และพลศึกษา</option>
                    <option value="ศิลปะ">ศิลปะ</option>
                    <option value="การงานอาชีพ">การงานอาชีพ</option>
                    <option value="ภาษาต่างประเทศ">ภาษาต่างประเทศ</option>
                    <option value="แนะแนว">แนะแนว</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">สถานะสอบ</label>
                  <select name="exam_status" id="workspace-e-status"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500">
                    <option value="Waiting">Waiting (เปิดห้องพักคอย)</option>
                    <option value="Started">Started (เริ่มสอบ/ห้ามลงทะเบียนเพิ่ม)</option>
                    <option value="Finished">Finished (ปิดระบบ/เสร็จสิ้นการสอบ)</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">จำนวนข้อสอบที่สุ่ม (ข้อ)</label>
                  <input type="number" name="num_questions" id="workspace-e-num"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required value="20">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">สิทธิ์สอบสูงสุด (ครั้ง)</label>
                  <input type="number" name="max_attempts" id="workspace-e-attempts"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required value="1">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">เกณฑ์ผ่าน (%)</label>
                  <input type="number" name="passing_percentage" id="workspace-e-pass"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required value="50">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">เวลาจำกัด ปรนัย
                    (วินาที/ข้อ)</label>
                  <input type="number" name="time_limit_choice" id="workspace-e-time-c"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required value="60">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 mb-2 uppercase">เวลาจำกัด อัตนัย
                    (วินาที/ข้อ)</label>
                  <input type="number" name="time_limit_writing" id="workspace-e-time-w"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required value="300">
                </div>
                <div>
                  <label class="block text-xs font-bold text-sky-400 mb-2 uppercase">เวลาสอบรวม (นาที)
                    [0=ไม่จำกัด]</label>
                  <input type="number" name="exam_duration" id="workspace-e-duration"
                    class="w-full bg-slate-800/50 border border-sky-500/20 rounded-xl p-3 text-white font-bold outline-none focus:border-sky-500 transition-colors"
                    required value="0" placeholder="0 = ไม่จำกัด">
                </div>
                <div>
                  <label class="block text-xs font-bold text-pink-400 mb-2 uppercase">รอบการสอบปัจจุบัน</label>
                  <input type="text" name="exam_round" id="workspace-e-round"
                    class="w-full bg-slate-800/50 border border-pink-500/20 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors"
                    required value="1" placeholder="เช่น 1, 2, แก้ตัว">
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                  <label class="block text-xs font-bold text-amber-400 mb-2 uppercase">ระบบป้องกันสลับหน้าจอ
                    (จับโกง)</label>
                  <select name="anti_cheating" id="workspace-e-anticheat"
                    class="w-full bg-slate-800/50 border border-amber-500/20 rounded-xl p-3 text-white font-bold outline-none focus:border-amber-500 transition-colors">
                    <option value="1">🔒 เปิดการตรวจจับสลับหน้าจอ</option>
                    <option value="0">🔓 ปิดระบบตรวจจับ (สลับหน้าจอไม่ระงับสอบ)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-bold text-amber-400 mb-2 uppercase">จำนวนครั้งที่อนุญาตให้สลับหน้าจอ
                    (ครั้ง)</label>
                  <input type="number" name="max_strikes" id="workspace-e-maxstrikes"
                    class="w-full bg-slate-800/50 border border-amber-500/20 rounded-xl p-3 text-white font-bold outline-none focus:border-amber-500 transition-colors"
                    required value="3" min="1" max="10">
                </div>
              </div>

              <!-- รูปแบบการสอบ (Exam Mode) -->
              <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 via-purple-500/10 to-sky-500/10 border border-amber-500/30">
                <label class="block text-xs font-black text-amber-300 uppercase tracking-wide mb-2 flex items-center justify-between">
                  <span class="flex items-center gap-1.5"><span>🎮</span> รูปแบบการสอบ (Exam Mode)</span>
                  <span class="text-[10px] text-slate-400 font-normal">กำหนดให้ผู้เรียนสอบในรูปแบบนี้</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10 bg-slate-800/80 cursor-pointer hover:border-sky-400 transition-all">
                    <input type="radio" name="exam_mode" value="classic" id="workspace-mode-classic" class="accent-sky-500 w-4 h-4 cursor-pointer">
                    <div>
                      <div class="text-xs font-bold text-white flex items-center gap-1"><span>📝</span> แบบมาตรฐาน (Classic)</div>
                      <div class="text-[11px] text-slate-400">ข้อสอบออนไลน์ทั่วไป ตัวเลือกสะอาดตา</div>
                    </div>
                  </label>
                  <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10 bg-slate-800/80 cursor-pointer hover:border-amber-400 transition-all">
                    <input type="radio" name="exam_mode" value="pokemon" id="workspace-mode-pokemon" class="accent-amber-500 w-4 h-4 cursor-pointer">
                    <div>
                      <div class="text-xs font-bold text-amber-300 flex items-center gap-1"><span>⚡</span> เดินเกม Pokémon RPG</div>
                      <div class="text-[11px] text-slate-400">ผจญภัยบนแผนที่ ประลองคำถามโปเกมอน</div>
                    </div>
                  </label>
                  <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10 bg-slate-800/80 cursor-pointer hover:border-violet-400 transition-all">
                    <input type="radio" name="exam_mode" value="escape_room" id="workspace-mode-escape" class="accent-violet-500 w-4 h-4 cursor-pointer">
                    <div>
                      <div class="text-xs font-bold text-violet-300 flex items-center gap-1"><span>🪙</span> Coin Quest</div>
                      <div class="text-[11px] text-slate-400">วิ่งกระโดดแตะเหรียญเพื่อรับคำถาม แล้วตอบเพื่อไปด่านถัดไป</div>
                    </div>
                  </label>
                </div>
              </div>

              <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-white/5">
                <button type="button" onclick="duplicateCurrentExam()"
                  class="px-5 py-3.5 bg-sky-500/15 hover:bg-sky-500/25 text-sky-400 hover:text-sky-300 font-bold rounded-xl text-xs sm:text-sm border border-sky-500/30 transition-all flex items-center gap-2 cursor-pointer shadow-sm">
                  📋 คัดลอกวิชานี้เป็นวิชาใหม่
                </button>
                <button type="submit"
                  class="px-8 py-3.5 bg-gradient-to-r from-pink-500 to-sky-400 hover:scale-[1.02] active:scale-[0.98] transition-all text-white font-black rounded-xl text-sm shadow-lg shadow-pink-500/20 cursor-pointer">
                  💾 บันทึกการตั้งค่าวิชาสอบ
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- SECTION 6: GLOBAL SETTINGS -->
        <div id="section-settings" class="section-content hidden">
          <div class="glass-panel rounded-3xl p-6 border border-white/5 shadow-xl">
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-black text-white flex items-center gap-2">⚙️ ตั้งค่าระบบส่วนกลาง</h2>
              <a href="/teacher/dashboard"
                class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold border border-white/5 rounded-xl text-xs transition-all flex items-center justify-center">
                🔙 ย้อนกลับหน้าวิชา
              </a>
            </div>

            <form id="settingsForm" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase mb-2">ชื่อเว็บไซต์ระบบข้อสอบ (Website
                    Name)</label>
                  <input type="text" name="Website Name"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-sky-400 transition-colors"
                    required>
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase mb-2">ลิงก์ URL ของโลโก้
                    (ถ้ามี)</label>
                  <input type="text" name="Logo URL"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-sky-400 transition-colors">
                </div>
                <div class="col-span-1 md:col-span-2">
                  <label class="block text-xs font-bold text-pink-400 uppercase mb-2 font-extrabold">Google Client ID
                    (สำหรับล็อกอินระบบหลังบ้านคุณครู)</label>
                  <input type="text" name="Google Client ID"
                    class="w-full bg-slate-800/50 border border-pink-500/20 rounded-xl p-3 text-white font-bold outline-none focus:border-pink-500 transition-colors">
                </div>
                <div class="col-span-1 md:col-span-2">
                  <label class="block text-xs font-bold text-sky-400 uppercase mb-2 font-extrabold">Gemini API Key
                    (สำหรับตรวจคำตอบอัตนัยด้วย AI)</label>
                  <input type="text" name="Gemini API Key"
                    class="w-full bg-slate-800/50 border border-sky-500/20 rounded-xl p-3 text-white font-bold outline-none focus:border-sky-400 transition-colors"
                    placeholder="AI-generated Key...">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase mb-2">รหัสผ่านผู้ดูแลระบบสำรอง (Admin
                    Password)</label>
                  <input type="password" name="Admin Password"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl p-3 text-white font-bold outline-none focus:border-sky-400 transition-colors"
                    required>
                </div>
              </div>

              <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-pink-500 to-sky-400 hover:from-pink-600 hover:to-sky-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-pink-500/20">
                💾 บันทึกการตั้งค่าส่วนกลาง
              </button>
            </form>
          </div>
        </div>

        <!-- SECTION 7: USER MANUAL (คู่มือการใช้งานระบบ) -->
        <div id="section-manual" class="section-content hidden space-y-6">
          <!-- Manual Header Card -->
          <div class="glass-panel rounded-3xl p-6 sm:p-8 border border-white/5 relative overflow-hidden shadow-xl">
            <div class="absolute -right-12 -top-12 w-64 h-64 bg-gradient-to-br from-pink-500/10 via-purple-500/10 to-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
              <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-pink-500/20 to-sky-500/20 border border-pink-500/30 text-xs font-black text-pink-300">
                  <span>📖</span> คู่มือการใช้งานระบบฉบับสมบูรณ์ (Interactive User Manual)
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white">ระบบจัดการข้อสอบออนไลน์อัจฉริยะ</h2>
                <p class="text-xs sm:text-sm text-slate-350 max-w-2xl leading-relaxed">
                  รวบรวมขั้นตอนการทำงานทุกฟังก์ชันสำหรับคุณครูผู้สอนและผู้ดูแลระบบ ตั้งแต่การสร้างวิชา คลังข้อสอบ การคุมสอบสดแบบเรียลไทม์ การตรวจอัตนัยด้วย AI Gemini ไปจนถึงการออกผลคะแนนและป้องกันการทุจริต
                </p>
              </div>

              <div class="flex flex-wrap gap-2.5 shrink-0">
                <button onclick="printManualDocument()"
                  class="px-4 py-2.5 bg-sky-500/15 hover:bg-sky-500/25 text-sky-300 border border-sky-400/30 font-bold rounded-xl text-xs transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                  🖨️ สั่งพิมพ์คู่มือนี้ (Print)
                </button>
                <a href="/USER_MANUAL.md" target="_blank"
                  class="px-4 py-2.5 bg-pink-500/15 hover:bg-pink-500/25 text-pink-300 border border-pink-400/30 font-bold rounded-xl text-xs transition-all flex items-center gap-2 shadow-sm">
                  📄 ดูไฟล์ Markdown
                </a>
              </div>
            </div>

            <!-- Manual Search & Quick Filter -->
            <div class="mt-6 pt-6 border-t border-white/5 flex flex-col sm:flex-row items-center gap-4">
              <div class="relative w-full sm:w-80">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-xs">🔍</span>
                <input type="text" id="manualTopicSearch" oninput="filterManualTopics()"
                  class="w-full pl-9 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs font-semibold text-white placeholder-slate-500 focus:outline-none focus:border-pink-500 transition-colors"
                  placeholder="ค้นหาหัวข้อในคู่มือ เช่น ข้อสอบ, AI, ทุจริต, Excel...">
              </div>
              
              <!-- Quick Jump Pills -->
              <div class="flex items-center gap-1.5 flex-wrap overflow-x-auto w-full text-xs">
                <a href="#manual-sec-exams" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">🏫 1. จัดการวิชา</a>
                <a href="#manual-sec-questions" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">📝 2. คลังข้อสอบ</a>
                <a href="#manual-sec-monitor" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">🖥️ 3. คุมสอบสด</a>
                <a href="#manual-sec-ai-grade" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">🤖 4. ตรวจด้วย AI</a>
                <a href="#manual-sec-export" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">📊 5. พิมพ์ & Excel</a>
                <a href="#manual-sec-anti-cheat" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">🚨 6. จับทุจริต</a>
                <a href="#manual-sec-student" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">🎓 7. สำหรับนักเรียน</a>
                <a href="#manual-sec-faq" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition-colors font-bold whitespace-nowrap">❓ 8. FAQ ถาม-ตอบ</a>
              </div>
            </div>
          </div>

          <!-- MANUAL CONTENT CONTAINER -->
          <div id="manual-content-container" class="space-y-8">

            <!-- MODULE 1: การจัดการรายวิชาสอบ -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-exams" data-keywords="วิชา สร้างวิชา รอบสอบ กลางภาค ปลายภาค pokemon คัดลอก duplicate exam duration เวลา">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-pink-500/20 border border-pink-500/40 flex items-center justify-center text-lg text-pink-300">
                    🏫
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">1. การจัดการรายวิชาสอบ (Exam & Subject Management)</h3>
                    <p class="text-xs text-slate-400">สร้างรายวิชา กำหนดกติกาการสอบ รอบสอบ และคัดลอกวิชา</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-pink-500/10 text-pink-400 border border-pink-500/20">ครูผู้สอน / ผู้ดูแล</span>
              </div>

              <!-- CSS UI MOCKUP 1: Teacher Exam Cards Screen -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: แผงควบคุมรายวิชาทั้งหมด (Teacher Exam Lobby)</span>
                  </div>
                  <span class="text-[9px] text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded font-mono">UI Preview</span>
                </div>

                <!-- Mock Filter Bar -->
                <div class="p-2.5 bg-slate-900/90 rounded-xl border border-white/5 flex flex-wrap items-center justify-between gap-2 text-xs">
                  <div class="flex items-center gap-2 bg-black/40 border border-white/10 px-2.5 py-1 rounded-lg text-slate-400 text-[11px]">
                    <span>🔍</span> <span>ค้นหาชื่อวิชา รหัสวิชา...</span>
                  </div>
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-[11px] font-bold text-slate-400">ปีการศึกษา:</span>
                    <span class="bg-slate-800 text-pink-300 font-bold px-2 py-0.5 rounded border border-pink-500/30 text-[10px]">2569 (ล่าสุด) ▼</span>
                    <span class="px-2 py-0.5 rounded bg-white/20 text-white font-bold text-[10px]">🌟 ทั้งหมด (7)</span>
                    <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 font-bold text-[10px] border border-amber-500/30">🎯 สอบกลางภาค (5)</span>
                    <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-200 font-bold text-[10px] border border-purple-500/30">🏁 สอบปลายภาค (2)</span>
                  </div>
                </div>

                <!-- Mock Exam Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                  <!-- Mock Card 1 -->
                  <div class="p-3.5 bg-slate-900/80 rounded-2xl border border-amber-500/40 space-y-2 relative overflow-hidden">
                    <div class="flex justify-between items-center text-xs">
                      <span class="font-mono font-bold text-pink-400 text-[11px]">ว30291</span>
                      <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Started (กำลังสอบ)</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                      <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-amber-500/25 text-amber-300 border border-amber-400/50">🎯 สอบกลางภาค</span>
                      <span class="text-[10px] text-slate-400 font-bold">เทอม 1/2569</span>
                    </div>
                    <p class="font-black text-white text-sm">การสร้าง Web Application</p>
                    <div class="text-[11px] text-slate-400 space-y-0.5">
                      <p>📚 ในคลัง: ปรนัย 20 ข้อ / อัตนัย 2 ข้อ</p>
                      <p>🕒 สุ่มสอบ 20 ข้อ | เกณฑ์ผ่าน 50% | สิทธิ์สอบ 2 ครั้ง</p>
                    </div>
                    <div class="grid grid-cols-4 gap-1.5 pt-2 border-t border-white/5 text-[10px]">
                      <span class="col-span-2 py-1.5 bg-gradient-to-r from-pink-500 to-sky-400 text-white font-bold rounded-lg text-center">⚙️ จัดการข้อสอบ</span>
                      <span class="py-1.5 bg-sky-500/10 text-sky-400 border border-sky-500/30 font-bold rounded-lg text-center">📋 โคลน</span>
                      <span class="py-1.5 bg-white/5 text-slate-300 border border-white/10 font-bold rounded-lg text-center">✏️ แก้ไข</span>
                    </div>
                  </div>

                  <!-- Mock Card 2 -->
                  <div class="p-3.5 bg-slate-900/80 rounded-2xl border border-purple-500/40 space-y-2 relative overflow-hidden">
                    <div class="flex justify-between items-center text-xs">
                      <span class="font-mono font-bold text-pink-400 text-[11px]">ว20250</span>
                      <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-500/10 text-slate-400 border border-slate-500/20">Waiting (รอเปิดสอบ)</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                      <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-purple-500/25 text-purple-200 border border-purple-400/50">🏁 สอบปลายภาค</span>
                      <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 text-[9px] font-black border border-amber-500/30">⚡ Pokémon</span>
                    </div>
                    <p class="font-black text-white text-sm">หลักการเขียนโปรแกรม</p>
                    <div class="text-[11px] text-slate-400 space-y-0.5">
                      <p>📚 ในคลัง: ปรนัย 25 ข้อ / อัตนัย 1 ข้อ</p>
                      <p>🕒 สุ่มสอบ 20 ข้อ | เกณฑ์ผ่าน 50% | สิทธิ์สอบ 1 ครั้ง</p>
                    </div>
                    <div class="grid grid-cols-4 gap-1.5 pt-2 border-t border-white/5 text-[10px]">
                      <span class="col-span-2 py-1.5 bg-gradient-to-r from-pink-500 to-sky-400 text-white font-bold rounded-lg text-center">⚙️ จัดการข้อสอบ</span>
                      <span class="py-1.5 bg-sky-500/10 text-sky-400 border border-sky-500/30 font-bold rounded-lg text-center">📋 โคลน</span>
                      <span class="py-1.5 bg-white/5 text-slate-300 border border-white/10 font-bold rounded-lg text-center">✏️ แก้ไข</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="space-y-3 text-xs sm:text-sm text-slate-350 leading-relaxed pt-2">
                <h4 class="font-extrabold text-pink-300 text-xs uppercase tracking-wider flex items-center gap-1.5">
                  <span>📌</span> สรุปฟิลด์สำคัญในการสร้าง/ตั้งค่าวิชา:
                </h4>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                  <li><b>เวลารายข้อ vs เวลาสอบรวม:</b> สามารถตั้งเวลานับถอยหลังรายข้อ (ปรนัย/อัตนัย) เพื่อบังคับให้ทำทีละข้อ หรือตั้งเวลาสอบรวมทั้งฉบับ (นาที)</li>
                  <li><b>นโยบายการเข้าร่วมสอบ (Join Policy):</b> <code>anytime</code> (เข้าสอบได้ทันที) หรือ <code>lobby_first</code> (ต้องรอในห้องพักคอยจนกว่าครูจะกดปุ่ม Start)</li>
                  <li><b>โหมดการจัดสอบ:</b> เลือก <code>classic</code> สำหรับข้อสอบมาตรฐาน, <code>pokemon</code> สำหรับเกม RPG 2D หรือ <code>escape_room</code> สำหรับเกม Coin Quest กระโดดเก็บเหรียญและตอบคำถาม</li>
                </ul>
              </div>
            </div>

            <!-- MODULE 2: คลังข้อสอบและการนำเข้า -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-questions" data-keywords="ข้อสอบ ปรนัย อัตนัย นำเข้า excel import csv ตัวเลือก ช้อยส์ เขียนตอบ upload image รูปภาพ">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-sky-500/20 border border-sky-500/40 flex items-center justify-center text-lg text-sky-300">
                    📝
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">2. การจัดการคลังข้อสอบ & นำเข้าจาก Excel (Question Bank)</h3>
                    <p class="text-xs text-slate-400">สร้างข้อสอบปรนัย อัตนัย แนบรูปภาพ และนำเข้าข้อสอบชุดใหญ่ในคราวเดียว</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20">คลังคำถาม</span>
              </div>

              <!-- CSS UI MOCKUP 2: Question Bank Screen -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: รายการคลังข้อสอบ (Question Bank Workspace)</span>
                  </div>
                  <div class="flex gap-1.5">
                    <span class="px-2 py-0.5 bg-pink-500 text-white rounded text-[9px] font-bold">➕ เพิ่มข้อสอบ</span>
                    <span class="px-2 py-0.5 bg-sky-500/20 text-sky-300 border border-sky-500/30 rounded text-[9px] font-bold">📥 นำเข้าข้อสอบ</span>
                  </div>
                </div>

                <!-- Mock Stats Header -->
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                  <div class="p-2 bg-slate-900 rounded-xl border border-white/5">
                    <p class="text-[9px] text-slate-400 uppercase">คะแนนรวมในคลัง</p>
                    <p class="font-black text-pink-400 text-sm">25.0 คะแนน</p>
                  </div>
                  <div class="p-2 bg-slate-900 rounded-xl border border-white/5">
                    <p class="text-[9px] text-slate-400 uppercase">จำนวนข้อสอบ</p>
                    <p class="font-black text-sky-400 text-sm">20 ข้อ (ปรนัย 18 / อัตนัย 2)</p>
                  </div>
                  <div class="p-2 bg-slate-900 rounded-xl border border-white/5">
                    <p class="text-[9px] text-slate-400 uppercase">เกณฑ์การจัดสอบ</p>
                    <p class="font-black text-amber-400 text-sm">สุ่มสอบ 20 ข้อ (ผ่าน 50%)</p>
                  </div>
                </div>

                <!-- Mock Question Rows -->
                <div class="space-y-2 pt-1">
                  <!-- Choice Question Mock -->
                  <div class="p-3 bg-slate-900/90 rounded-xl border border-pink-500/20 text-xs space-y-1.5">
                    <div class="flex justify-between items-start">
                      <p class="font-bold text-white"><span class="text-pink-400 font-black">ข้อ 1 [ปรนัย]:</span> แท็กใดในภาษา HTML ใช้สำหรับสร้างฟอร์มรับข้อมูล?</p>
                      <span class="px-2 py-0.5 rounded bg-pink-500/20 text-pink-300 font-mono font-bold text-[10px]">1.0 คะแนน</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1 text-[11px] text-slate-300">
                      <span class="p-1.5 bg-black/40 rounded border border-white/5">A. &lt;input&gt;</span>
                      <span class="p-1.5 bg-emerald-500/20 rounded border border-emerald-500/40 text-emerald-300 font-bold">✅ B. &lt;form&gt; (เฉลย)</span>
                      <span class="p-1.5 bg-black/40 rounded border border-white/5">C. &lt;div&gt;</span>
                      <span class="p-1.5 bg-black/40 rounded border border-white/5">D. &lt;table&gt;</span>
                    </div>
                  </div>

                  <!-- Writing Question Mock -->
                  <div class="p-3 bg-slate-900/90 rounded-xl border border-amber-500/20 text-xs space-y-1.5">
                    <div class="flex justify-between items-start">
                      <p class="font-bold text-white"><span class="text-amber-400 font-black">ข้อ 2 [อัตนัย]:</span> จงอธิบายหลักการทำงานของสถาปัตยกรรม Model-View-Controller (MVC)</p>
                      <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono font-bold text-[10px]">5.0 คะแนน</span>
                    </div>
                    <div class="p-2 bg-slate-950/60 rounded border border-amber-500/20 text-[11px] text-slate-350">
                      <span class="text-amber-300 font-bold">เฉลยแนวทางอ้างอิง:</span> Model จัดการข้อมูลและติดต่อฐานข้อมูล, View รับหน้าที่แสดงผล UI หน้าจอ, Controller ทำหน้าที่รับ Request และประสานงานตรรกะ
                    </div>
                  </div>
                </div>
              </div>

              <!-- Excel Import Table Reference -->
              <div class="space-y-2 pt-2">
                <h4 class="font-extrabold text-amber-300 text-xs uppercase tracking-wider flex items-center gap-1.5">
                  <span>📥</span> รูปแบบตาราง 8 คอลัมน์สำหรับนำเข้าผ่าน Excel / Google Sheets:
                </h4>
                <div class="overflow-x-auto">
                  <table class="w-full text-xs text-left border-collapse border border-white/10 rounded-xl">
                    <thead class="bg-slate-900 text-slate-300 font-bold">
                      <tr>
                        <th class="p-2 border border-white/10">Col A (โจทย์)</th>
                        <th class="p-2 border border-white/10">Col B (ตัวเลือก A)</th>
                        <th class="p-2 border border-white/10">Col C (ตัวเลือก B)</th>
                        <th class="p-2 border border-white/10">Col D (ตัวเลือก C)</th>
                        <th class="p-2 border border-white/10">Col E (ตัวเลือก D)</th>
                        <th class="p-2 border border-white/10">Col F (เฉลย)</th>
                        <th class="p-2 border border-white/10">Col G (ประเภท)</th>
                        <th class="p-2 border border-white/10">Col H (คะแนน)</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-350">
                      <tr>
                        <td class="p-2 font-bold text-white border border-white/10">โจทย์คำถาม...</td>
                        <td class="p-2 border border-white/10">ตัวเลือก ก</td>
                        <td class="p-2 border border-white/10">ตัวเลือก ข</td>
                        <td class="p-2 border border-white/10">ตัวเลือก ค</td>
                        <td class="p-2 border border-white/10">ตัวเลือก ง</td>
                        <td class="p-2 font-bold text-emerald-400 border border-white/10">ข (หรือข้อความเฉลย)</td>
                        <td class="p-2 text-pink-400 border border-white/10">choice</td>
                        <td class="p-2 text-amber-400 border border-white/10">1</td>
                      </tr>
                      <tr>
                        <td class="p-2 font-bold text-white border border-white/10">โจทย์ข้อเขียน...</td>
                        <td class="p-2 border border-white/10 text-slate-600">-</td>
                        <td class="p-2 border border-white/10 text-slate-600">-</td>
                        <td class="p-2 border border-white/10 text-slate-600">-</td>
                        <td class="p-2 border border-white/10 text-slate-600">-</td>
                        <td class="p-2 font-bold text-emerald-400 border border-white/10">แนวคำตอบอ้างอิง</td>
                        <td class="p-2 text-pink-400 border border-white/10">writing</td>
                        <td class="p-2 text-amber-400 border border-white/10">5</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- MODULE 3: จอภาพควบคุมสด & ห้องพักคอย -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-monitor" data-keywords="จอภาพ ควบคุม monitor lobby ห้องพักคอย เริ่มสอบ pause start finish สด เรียลไทม์ 2d">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-lg text-emerald-300">
                    🖥️
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">3. จอภาพควบคุมการสอบสด (Live Monitor & Virtual Lobby)</h3>
                    <p class="text-xs text-slate-400">ติดตามนักเรียนในห้องพักคอย 2D สั่งเริ่มสอบ หยุดชั่วคราว หรือปิดการสอบ</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">เรียลไทม์</span>
              </div>

              <!-- CSS UI MOCKUP 3: Live Monitor Screen -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: จอภาพควบคุมสด (Live Exam Room Monitor)</span>
                  </div>
                  <span class="flex items-center gap-1 text-[9px] text-emerald-400 font-mono">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Live Syncing
                  </span>
                </div>

                <!-- Mock Control Buttons Bar -->
                <div class="p-2.5 bg-slate-900 rounded-xl border border-white/5 flex flex-wrap items-center justify-between gap-2 text-xs">
                  <div class="flex items-center gap-2">
                    <span class="font-black text-white">สถานะปัจจุบัน:</span>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30">🟢 กำลังสอบ (Started)</span>
                  </div>
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="px-3 py-1 bg-emerald-600 text-white font-bold rounded-lg text-xs shadow-md">🟢 เริ่มสอบ</span>
                    <span class="px-3 py-1 bg-amber-600/80 text-white font-bold rounded-lg text-xs">⏸️ หยุดชั่วคราว</span>
                    <span class="px-3 py-1 bg-red-600/80 text-white font-bold rounded-lg text-xs">🔴 ปิดสอบ</span>
                    <span class="px-3 py-1 bg-slate-800 text-slate-300 border border-white/10 font-bold rounded-lg text-xs">🔄 รีเซ็ต</span>
                  </div>
                </div>

                <!-- Mock 2D Room Grid & Table -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                  <!-- 2D Virtual Canvas Preview -->
                  <div class="p-3 bg-slate-950 rounded-xl border border-white/10 flex flex-col justify-between relative min-h-[140px] overflow-hidden" style="background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 16px 16px;">
                    <span class="text-[9px] font-mono text-slate-500">2D Lobby Room Canvas</span>
                    <!-- Mock Character Avatars -->
                    <div class="absolute top-8 left-8 flex items-center gap-1 bg-pink-500/30 border border-pink-400/50 px-1.5 py-0.5 rounded-full text-[9px] text-white">
                      <span>🧑‍🎓</span> <span>สมชาย (ม.4/1)</span>
                    </div>
                    <div class="absolute bottom-6 right-10 flex items-center gap-1 bg-sky-500/30 border border-sky-400/50 px-1.5 py-0.5 rounded-full text-[9px] text-white">
                      <span>🧑‍🎓</span> <span>กัญญา (ม.4/1)</span>
                    </div>
                    <div class="text-[10px] text-slate-400 text-center z-10">นักเรียนออนไลน์ในห้อง: 2 คน</div>
                  </div>

                  <!-- Live Monitor Table Preview -->
                  <div class="md:col-span-2 overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse border border-white/5 rounded-xl bg-slate-900/60">
                      <thead class="text-slate-400 border-b border-white/10 text-[10px]">
                        <tr>
                          <th class="p-2">ชื่อ - สกุล</th>
                          <th class="p-2">ห้อง/เลขที่</th>
                          <th class="p-2">ความก้าวหน้า</th>
                          <th class="p-2">ทุจริต (Strikes)</th>
                          <th class="p-2 text-right">คำสั่ง</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-white/5 text-[11px] text-slate-350">
                        <tr>
                          <td class="p-2 font-bold text-white">ด.ช.สมชาย ใจดี</td>
                          <td class="p-2">ม.4/1 #1</td>
                          <td class="p-2 text-emerald-400 font-bold">ข้อ 15/20</td>
                          <td class="p-2"><span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-bold">0 ครั้ง (ปกติ)</span></td>
                          <td class="p-2 text-right"><span class="text-red-400 font-bold cursor-pointer">เอาออก</span></td>
                        </tr>
                        <tr>
                          <td class="p-2 font-bold text-white">ด.ญ.กัญญา สุขใจ</td>
                          <td class="p-2">ม.4/1 #2</td>
                          <td class="p-2 text-sky-400 font-bold">ข้อ 18/20</td>
                          <td class="p-2"><span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 font-bold">1 ครั้ง (เตือน)</span></td>
                          <td class="p-2 text-right"><span class="text-red-400 font-bold cursor-pointer">เอาออก</span></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODULE 4: การตรวจคำตอบ & AI ช่วยตรวจ -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-ai-grade" data-keywords="ตรวจข้อสอบ ai gemini อัตนัย ข้อเขียน ให้คะแนน คะแนน feedback ประเมิน">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-purple-500/20 border border-purple-500/40 flex items-center justify-center text-lg text-purple-300">
                    🤖
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">4. การตรวจคำตอบอัตนัยด้วย AI (Google Gemini AI Grading)</h3>
                    <p class="text-xs text-slate-400">ตรวจคำตอบข้อเขียนเทียบกับเฉลยอย่างเป็นธรรม พร้อมรับเหตุผลประกอบจาก AI</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-purple-500/10 text-purple-400 border border-purple-500/20">AI อัจฉริยะ</span>
              </div>

              <!-- CSS UI MOCKUP 4: AI Grading Modal Screen -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: ป๊อปอัปตรวจข้อเขียนอัตโนมัติด้วย AI (Gemini Flash)</span>
                  </div>
                  <span class="px-2 py-0.5 rounded bg-purple-500/20 text-purple-300 border border-purple-400/40 font-mono text-[9px] font-bold">Google Gemini 2.0 Flash</span>
                </div>

                <div class="p-3 bg-slate-900 rounded-2xl border border-white/5 space-y-3 text-xs">
                  <div>
                    <p class="text-slate-400 font-bold text-[11px]">คำถามข้อที่ 2 (อัตนัย):</p>
                    <p class="text-white font-black text-sm">จงอธิบายหน้าที่ของ Controller ในสถาปัตยกรรม MVC</p>
                  </div>

                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-2.5 bg-slate-950 rounded-xl border border-white/10 space-y-1">
                      <span class="text-sky-400 font-bold text-[10px]">คำตอบของนักเรียน:</span>
                      <p class="text-slate-200 text-xs">"Controller ทำหน้าที่รับคำขอจากผู้ใช้ ประมวลผลตรรกะทางธุรกิจ และเรียก View มาแสดงผลลัพธ์"</p>
                    </div>
                    <div class="p-2.5 bg-slate-950 rounded-xl border border-white/10 space-y-1">
                      <span class="text-amber-400 font-bold text-[10px]">เฉลยแนวทางของคุณครู:</span>
                      <p class="text-slate-200 text-xs">"Controller เป็นตัวกลางรับ Request จากผู้ใช้ ประมวลผลตรรกะ และส่งต่อไปยัง Model และ View"</p>
                    </div>
                  </div>

                  <!-- AI Result Box with Glowing Border -->
                  <div class="p-3.5 bg-gradient-to-r from-purple-950/40 via-slate-900 to-indigo-950/40 rounded-xl border border-purple-500/40 space-y-2">
                    <div class="flex items-center justify-between">
                      <span class="text-purple-300 font-black flex items-center gap-1.5 text-xs">
                        <span>✨</span> ผลการวิเคราะห์จาก AI:
                      </span>
                      <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-mono font-bold text-xs">
                        คะแนนที่ประเมินได้: 5.0 / 5.0
                      </span>
                    </div>
                    <p class="text-slate-300 text-xs leading-relaxed">
                      "✅ คำตอบของนักเรียนมีความถูกต้องและครบถ้วนตามหลักการ MVC โดยระบุหน้าที่หลักของ Controller ทั้งการรับ Request ประมวลผลตรรกะ และการส่งต่อข้อมูลไปยัง View ได้อย่างถูกต้องตรงตามเฉลยแนวทาง"
                    </p>
                    <div class="flex justify-end gap-2 pt-1">
                      <span class="px-3 py-1 bg-purple-600 text-white font-bold rounded-lg text-xs cursor-pointer">✨ ให้ AI ตรวจใหม่</span>
                      <span class="px-3 py-1 bg-emerald-600 text-white font-bold rounded-lg text-xs cursor-pointer">💾 บันทึกคะแนน</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODULE 5: พิมพ์ใบประกาศ & ส่งออก Excel -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-export" data-keywords="พิมพ์ ประกาศผล print a4 excel csv export ส่งออก ดาวน์โหลด คะแนน สรุปผล">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-lg text-indigo-300">
                    📊
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">5. การพิมพ์ประกาศผลสอบ และส่งออกเป็น Excel/CSV</h3>
                    <p class="text-xs text-slate-400">พิมพ์เอกสารทางการขนาด A4 และดาวน์โหลดคะแนนเพื่อนำไปกรอก ปพ. ได้ทันที</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">รายงาน & ผลสอบ</span>
              </div>

              <!-- CSS UI MOCKUP 5: Results Table & A4 Official Report Preview -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: เมนูพิมพ์ใบประกาศ & ส่งออกไฟล์ Excel (CSV UTF-8)</span>
                  </div>
                  <div class="flex gap-2">
                    <span class="px-2.5 py-1 bg-sky-500/20 text-sky-300 border border-sky-400/30 rounded text-[10px] font-bold">🖨️ พิมพ์ประกาศผลสอบ</span>
                    <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 rounded text-[10px] font-bold">📥 ส่งออกคะแนน (Excel/CSV)</span>
                  </div>
                </div>

                <!-- Mock Miniature A4 Report Sheet -->
                <div class="max-w-xl mx-auto bg-white text-slate-900 rounded-xl p-5 shadow-lg border border-slate-300 space-y-3 text-xs font-sans">
                  <div class="text-center border-b border-slate-200 pb-2 space-y-1">
                    <p class="font-extrabold text-sm text-black">ประกาศผลคะแนนการสอบออนไลน์</p>
                    <p class="font-bold text-xs text-slate-800">รายวิชา ว30291 การสร้าง Web Application (สอบกลางภาค)</p>
                    <p class="text-[11px] text-slate-600">ปีการศึกษา 2569 ภาคเรียนที่ 1 | เกณฑ์ผ่าน: 50% (10 คะแนนเต็ม 20)</p>
                  </div>

                  <table class="w-full text-[11px] text-left border-collapse border border-slate-300">
                    <thead class="bg-slate-100 font-bold text-slate-700">
                      <tr>
                        <th class="p-1.5 border border-slate-300">ลำดับ</th>
                        <th class="p-1.5 border border-slate-300">เลขประจำตัว</th>
                        <th class="p-1.5 border border-slate-300">ชื่อ - สกุล</th>
                        <th class="p-1.5 border border-slate-300">ห้อง/เลขที่</th>
                        <th class="p-1.5 border border-slate-300 text-center">ปรนัย</th>
                        <th class="p-1.5 border border-slate-300 text-center">อัตนัย</th>
                        <th class="p-1.5 border border-slate-300 text-center">รวม</th>
                        <th class="p-1.5 border border-slate-300 text-center">ผลสอบ</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                      <tr>
                        <td class="p-1.5 border border-slate-300 text-center">1</td>
                        <td class="p-1.5 border border-slate-300 font-mono">12345</td>
                        <td class="p-1.5 border border-slate-300 font-bold">ด.ช.สมชาย ใจดี</td>
                        <td class="p-1.5 border border-slate-300">ม.4/1 #1</td>
                        <td class="p-1.5 border border-slate-300 text-center">15</td>
                        <td class="p-1.5 border border-slate-300 text-center">5</td>
                        <td class="p-1.5 border border-slate-300 text-center font-bold text-black">20</td>
                        <td class="p-1.5 border border-slate-300 text-center text-emerald-600 font-bold">ผ่าน (Pass)</td>
                      </tr>
                      <tr>
                        <td class="p-1.5 border border-slate-300 text-center">2</td>
                        <td class="p-1.5 border border-slate-300 font-mono">12346</td>
                        <td class="p-1.5 border border-slate-300 font-bold">ด.ญ.กัญญา สุขใจ</td>
                        <td class="p-1.5 border border-slate-300">ม.4/1 #2</td>
                        <td class="p-1.5 border border-slate-300 text-center">14</td>
                        <td class="p-1.5 border border-slate-300 text-center">4</td>
                        <td class="p-1.5 border border-slate-300 text-center font-bold text-black">18</td>
                        <td class="p-1.5 border border-slate-300 text-center text-emerald-600 font-bold">ผ่าน (Pass)</td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="text-right text-[11px] text-slate-600 pt-2">
                    <p>ลงชื่อ..........................................................ครูผู้สอน</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODULE 6: ระบบป้องกันการทุจริต -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-anti-cheat" data-keywords="ทุจริต สลับหน้าจอ cheat anti-cheating strike devtools f12 logs บันทึกความเสี่ยง">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-red-500/20 border border-red-500/40 flex items-center justify-center text-lg text-red-300">
                    🚨
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">6. ระบบตรวจจับและป้องกันการทุจริต (Anti-Cheating Engine)</h3>
                    <p class="text-xs text-slate-400">การรักษาความปลอดภัยขั้นสูง บันทึกพฤติกรรมเสี่ยง และระงับสิทธิ์อัตโนมัติ</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-red-500/10 text-red-400 border border-red-500/20">ความปลอดภัย</span>
              </div>

              <!-- CSS UI MOCKUP 6: Anti-Cheating Alert Modal Screen -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: หน้าต่างเตือนทุจริตแบบเรียลไทม์ (เมื่อนักเรียนสลับแท็บ/ย่อจอ)</span>
                  </div>
                  <span class="px-2 py-0.5 rounded bg-red-500/20 text-red-300 border border-red-500/30 text-[9px] font-bold">Strike Alert</span>
                </div>

                <!-- Mock Red Warning Modal -->
                <div class="max-w-md mx-auto p-5 bg-gradient-to-b from-red-950/80 to-slate-900 rounded-2xl border-2 border-red-500/80 shadow-[0_0_30px_rgba(239,68,68,0.25)] text-center space-y-3">
                  <div class="w-12 h-12 rounded-full bg-red-500/20 border border-red-500/50 flex items-center justify-center text-2xl mx-auto animate-bounce">
                    🚨
                  </div>
                  <h4 class="text-base font-black text-red-400">⚠️ ตรวจพบการพยายามสลับหน้าจอสอบ!</h4>
                  <div class="inline-block px-3 py-1 rounded-full bg-red-500/30 border border-red-400/50 text-xs font-mono font-black text-white">
                    จำนวนครั้งที่ผิดกฎ: 1 / 3 ครั้ง (Strike 1)
                  </div>
                  <p class="text-xs text-slate-300 leading-relaxed">
                    ระบบตรวจพบว่าคุณได้สลับแท็บหรือคลิกออกนอกหน้าต่างข้อสอบ ระบบได้บันทึกเวลาและแจ้งเตือนไปยังครูผู้คุมสอบแล้ว <b class="text-red-400">หากทำผิดกฎครบ 3 ครั้ง ระบบจะส่งข้อสอบและระงับสิทธิ์ทันที</b>
                  </p>
                  <span class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-black text-xs rounded-xl shadow-lg cursor-pointer">
                    รับทราบและกลับสู่ข้อสอบ 🔒
                  </span>
                </div>
              </div>
            </div>

            <!-- MODULE 7: สำหรับนักเรียน -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-student" data-keywords="นักเรียน student ลงทะเบียน ทำข้อสอบ pokemon rpg ผลสอบ lobby เกม">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-lg text-amber-300">
                    🎓
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">7. คู่มือสำหรับนักเรียนผู้เข้าสอบ (Student Portal - 2 Modes)</h3>
                    <p class="text-xs text-slate-400">คำแนะนำการทำข้อสอบทั้งแบบโหมดมาตรฐาน (Classic) และโหมดเกม RPG 2D</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20">ฝั่งนักเรียน</span>
              </div>

              <!-- CSS UI MOCKUP 7: Side-by-Side Exam Modes -->
              <div class="rounded-2xl bg-[#090d16] border border-white/10 p-3 sm:p-4 shadow-2xl space-y-3">
                <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                  <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-[10px] text-slate-400 font-mono">🖥️ ตัวอย่างหน้าจอ: โหมดข้อสอบมาตรฐาน (Classic) VS โหมดเกม RPG (Pokémon)</span>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                  <!-- Mode 1: Classic Exam UI -->
                  <div class="p-3 bg-slate-900 rounded-2xl border border-sky-500/30 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-[10px] text-slate-400 pb-1 border-b border-white/5">
                      <span class="font-bold text-sky-400">📝 โหมดมาตรฐาน (Classic Exam)</span>
                      <span class="font-mono text-amber-300 font-bold">⏱️ 00:48</span>
                    </div>
                    <!-- Mock Progress Bar -->
                    <div class="space-y-1">
                      <div class="flex justify-between text-[10px] text-slate-400">
                        <span>ข้อที่ 5 จาก 20 ข้อ</span>
                        <span>25%</span>
                      </div>
                      <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-pink-500 rounded-full" style="width: 25%"></div>
                      </div>
                    </div>
                    <!-- Mock Question Box -->
                    <p class="font-bold text-white text-xs pt-1">5. สัญลักษณ์ใดใน HTML ใช้เขียนคำอธิบายโค้ด (Comment)?</p>
                    <div class="space-y-1 text-[11px]">
                      <div class="p-1.5 bg-black/40 rounded border border-white/5 text-slate-300 flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded-full border border-slate-500 inline-block"></span>
                        <span>A. // ข้อความ</span>
                      </div>
                      <div class="p-1.5 bg-pink-500/20 rounded border border-pink-500/40 text-white font-bold flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded-full border border-pink-400 bg-pink-500 inline-block"></span>
                        <span>B. &lt;!-- ข้อความ --&gt;</span>
                      </div>
                      <div class="p-1.5 bg-black/40 rounded border border-white/5 text-slate-300 flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded-full border border-slate-500 inline-block"></span>
                        <span>C. /* ข้อความ */</span>
                      </div>
                    </div>
                    <span class="block w-full py-1.5 bg-gradient-to-r from-pink-500 to-sky-400 text-white font-bold text-[10px] rounded-lg text-center mt-2">ข้อถัดไป ➡️</span>
                  </div>

                  <!-- Mode 2: Pokemon RPG Adventure UI -->
                  <div class="p-3 bg-slate-900 rounded-2xl border border-amber-500/30 space-y-2 text-xs">
                    <div class="flex justify-between items-center text-[10px] text-slate-400 pb-1 border-b border-white/5">
                      <span class="font-bold text-amber-300 flex items-center gap-1"><span>⚡</span> โหมดผจญภัย (Pokémon RPG)</span>
                      <span class="font-mono text-emerald-400 font-bold">HP: 100/100</span>
                    </div>
                    <!-- Mock 2D Map with Pixels -->
                    <div class="h-28 bg-emerald-950/60 rounded-xl border border-emerald-500/30 relative flex flex-col justify-between p-2 overflow-hidden" style="background-image: radial-gradient(rgba(16,185,129,0.15) 1px, transparent 1px); background-size: 14px 14px;">
                      <div class="flex justify-between items-start">
                        <span class="px-2 py-0.5 rounded bg-black/70 text-[9px] text-amber-300 border border-amber-400/40">⚔️ Battle Arena</span>
                        <div class="text-right">
                          <span class="text-[10px] font-bold text-white">⚡ NPC อาจารย์</span>
                          <div class="h-1 w-14 bg-red-500 rounded-full mt-0.5"></div>
                        </div>
                      </div>
                      <!-- Trainer Sprite & Enemy Sprite Mock -->
                      <div class="flex justify-between items-end px-4">
                        <span class="text-2xl">🏃‍♂️</span>
                        <span class="text-2xl animate-pulse">👾</span>
                      </div>
                    </div>
                    <!-- Mock Battle Command Dialog -->
                    <div class="p-2 bg-slate-950 rounded-xl border border-white/10 space-y-1">
                      <p class="text-[10px] text-amber-300 font-bold">"อาจารย์ท้าดวลข้อที่ 5! เลือกท่าโจมตี (คำตอบ) ที่ถูกต้อง!"</p>
                      <div class="grid grid-cols-2 gap-1 text-[10px] font-mono">
                        <span class="p-1 bg-slate-800 rounded border border-white/10 text-center text-slate-200">[A] ท่าโจมตี 1</span>
                        <span class="p-1 bg-amber-500/20 rounded border border-amber-400/30 text-center text-amber-200 font-bold">[B] ท่าโจมตี 2 (เลือก)</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODULE 8: คำถามที่พบบ่อย -->
            <div class="manual-card glass-panel rounded-3xl p-6 sm:p-7 border border-white/5 space-y-5" id="manual-sec-faq" data-keywords="faq ถามตอบ ปัญหา error เข้าไม่ได้ google key รหัสผ่าน สอบใหม่">
              <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-2xl bg-teal-500/20 border border-teal-500/40 flex items-center justify-center text-lg text-teal-300">
                    ❓
                  </div>
                  <div>
                    <h3 class="text-base sm:text-lg font-black text-white">8. คำถามที่พบบ่อย & การแก้ปัญหา (FAQ & Troubleshooting)</h3>
                    <p class="text-xs text-slate-400">รวมแนวทางการแก้ปัญหาที่พบบ่อยในการจัดสอบจริง</p>
                  </div>
                </div>
                <span class="px-3 py-1 text-[10px] font-black rounded-lg bg-teal-500/10 text-teal-400 border border-teal-500/20">การแก้ปัญหา</span>
              </div>

              <div class="space-y-3 text-xs sm:text-sm text-slate-350 leading-relaxed">
                <div class="p-3.5 bg-slate-900/90 rounded-2xl border border-white/10 space-y-1">
                  <p class="font-bold text-white flex items-center gap-1.5"><span class="text-sky-400">Q:</span> ล็อกอินผ่าน Google ไม่ได้ เกิดข้อผิดพลาด Token?</p>
                  <p class="text-xs text-slate-400 pl-4"><span class="text-emerald-400 font-bold">A:</span> ต้องใช้อีเมลโดเมน <code>@skj.ac.th</code> เท่านั้น หรือคลิกลิงก์ <b>"🔑 หรือเข้าสู่ระบบด้วยรหัสผ่านผู้ดูแลระบบ"</b> ด้านล่างปุ่ม Google แล้วกรอกรหัสผ่าน Master Admin (`admin1234`)</p>
                </div>
                <div class="p-3.5 bg-slate-900/90 rounded-2xl border border-white/10 space-y-1">
                  <p class="font-bold text-white flex items-center gap-1.5"><span class="text-sky-400">Q:</span> นักเรียนเผลอสลับหน้าจอจนโดนระงับสิทธิ์สอบ (Disqualified)?</p>
                  <p class="text-xs text-slate-400 pl-4"><span class="text-emerald-400 font-bold">A:</span> ไปที่เมนู "📊 ผลสอบและตรวจอัตนัย" ค้นหาชื่อนักเรียน แล้วคลิก <b>"🗑️ ลบผลสอบ"</b> ระบบจะเคลียร์ประวัติผลสอบและ Log ความเสี่ยง ทำให้นักเรียนสามารถลงทะเบียนเข้าทำข้อสอบใหม่ได้ทันที</p>
                </div>
                <div class="p-3.5 bg-slate-900/90 rounded-2xl border border-white/10 space-y-1">
                  <p class="font-bold text-white flex items-center gap-1.5"><span class="text-sky-400">Q:</span> AI ตรวจข้อเขียนขึ้น Error หรือไม่ทำงาน?</p>
                  <p class="text-xs text-slate-400 pl-4"><span class="text-emerald-400 font-bold">A:</span> ตรวจสอบที่เมนู "⚙️ ตั้งค่าส่วนกลาง" ช่อง Gemini API Key ว่ากรอกคีย์ถูกต้องหรือไม่ (รับคีย์ฟรีจาก Google AI Studio) ทั้งนี้ระบบยังมีอัลกอริทึม Text Similarity ช่วยตรวจสำรองให้อัตโนมัติ</p>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- MODAL: Exam (Subject) Form -->
  <!-- MODAL: Exam (Subject) Form was removed, now uses SweetAlert2 dynamically -->

  <!-- MODAL: Question Edit Form -->
  <!-- MODAL: Question Edit Form was removed, now uses SweetAlert2 dynamically -->

  <!-- MODAL: Import Questions -->
  <div id="importModal"
    class="fixed inset-0 bg-black/85 backdrop-filter backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-white/10 rounded-3xl w-full max-w-xl p-6 shadow-2xl">
      <h3 class="text-lg font-black text-white mb-2">📥 นำเข้าข้อสอบ</h3>
      <p class="text-xs text-slate-400 mb-4 leading-relaxed">
        คัดลอกข้อมูลคำถามจาก Excel/Google Sheets มาวาง โดยคอลัมน์ต้องเรียงลำดับตามนี้:<br>
        <b>[โจทย์คำถาม, ตัวเลือก A, ตัวเลือก B, ตัวเลือก C, ตัวเลือก D, คำตอบ (เฉลย), ประเภท (choice/writing),
          คะแนน]</b>
      </p>
      <textarea id="import-data"
        class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white font-mono text-xs outline-none focus:border-sky-400"
        rows="8" placeholder="โจทย์	ก	ข	ค	ง	ก	choice	1"></textarea>

      <div class="flex items-center gap-2 mt-4">
        <input type="checkbox" id="import-clear-existing" class="w-4 h-4 rounded border-white/10 text-pink-500">
        <label for="import-clear-existing"
          class="text-xs text-slate-350 select-none">ลบข้อสอบรายวิชานี้ที่เคยมีออกทั้งหมดก่อนนำเข้า</label>
      </div>

      <div class="flex gap-3 justify-end pt-6 border-t border-white/5 mt-4">
        <button type="button" onclick="closeImportModal()"
          class="px-4 py-2 bg-slate-850 hover:bg-slate-800 text-slate-300 font-bold rounded-xl text-sm">ยกเลิก</button>
        <button type="button" onclick="submitImport()"
          class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl text-sm">นำเข้าข้อมูล</button>
      </div>
    </div>
  </div>



  <script>
    var teacherSession = {
      name: "<?= esc($teacherName) ?>",
      email: "<?= esc($teacherEmail) ?>",
      learningArea: "<?= esc($teacherLearning) ?>"
    };

    var currentTab = '<?= esc($currentTab ?? "questions") ?>';
    var activeMonitorInterval;
    var currentEditingResultId = null;
    var globalExamsList = [];
    var globalQuestionsList = [];
    var questionsMap = {};
    var activeExamId = '<?= esc($activeExamId ?? "") ?>';
    var isInWorkspace = <?= (isset($isInWorkspace) && $isInWorkspace) ? 'true' : 'false' ?>;

    window.onload = async () => {
      await loadGlobalExams();
      if (isInWorkspace) {
        if (currentTab === 'manual') {
          enterManualTab();
        } else if (activeExamId === 'global') {
          enterGlobalSettings();
        } else {
          enterWorkspace(activeExamId, currentTab);
        }
      } else {
        renderLobbyScreen();
      }
    };

    async function loadGlobalExams() {
      try {
        const response = await fetch('/api/teacher/exams');
        const res = await response.json();
        if (res.success && res.exams) {
          globalExamsList = res.exams;
          initTeacherYearFilter();
        }
      } catch (e) {
        console.error("Failed to load exams list", e);
      }
    }

    var teacherExamFilter = 'all';
    var teacherSelectedYear = 'latest';

    function initTeacherYearFilter() {
      const yearSelect = document.getElementById('teacherYearFilter');
      if (!yearSelect) return;

      // Extract unique academic years and sort descending
      const years = [];
      globalExamsList.forEach(e => {
        const y = (e.academic_year || '').trim();
        if (y && !years.includes(y)) years.push(y);
      });
      years.sort().reverse();

      if (years.length === 0) {
        yearSelect.innerHTML = '<option value="all">ทุกปีการศึกษา</option>';
        teacherSelectedYear = 'all';
        return;
      }

      const latestYear = years[0];
      if (teacherSelectedYear === 'latest' || !years.includes(teacherSelectedYear)) {
        teacherSelectedYear = latestYear;
      }

      let optHtml = '';
      years.forEach((y, idx) => {
        const isSel = (teacherSelectedYear === y) ? 'selected' : '';
        optHtml += `<option value="${escapeHtml(y)}" ${isSel}>${escapeHtml(y)}${idx === 0 ? ' (ล่าสุด)' : ''}</option>`;
      });
      optHtml += `<option value="all" ${teacherSelectedYear === 'all' ? 'selected' : ''}>🌟 ทุกปีการศึกษา</option>`;
      yearSelect.innerHTML = optHtml;
    }

    function onTeacherYearFilterChange() {
      const yearSelect = document.getElementById('teacherYearFilter');
      if (yearSelect) {
        teacherSelectedYear = yearSelect.value;
      }
      renderLobbyScreen();
    }

    function escapeHtml(str) {
      if (str === null || str === undefined) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
    }

    function setTeacherExamFilter(type) {
      teacherExamFilter = type;
      ['all', 'midterm', 'final', 'other'].forEach(t => {
        const btn = document.getElementById('t-filter-' + t);
        if (btn) {
          btn.classList.remove('bg-white/20', 'text-white', 'shadow-md', 'border-white/30');
          if (t === 'all') {
            btn.classList.remove('bg-white/20', 'border-white/20');
            btn.classList.add('bg-white/5', 'text-slate-300', 'border-white/10');
          }
        }
      });
      const targetId = type === 'all' ? 't-filter-all' : (type === 'กลางภาค' ? 't-filter-midterm' : (type === 'ปลายภาค' ? 't-filter-final' : 't-filter-other'));
      const targetBtn = document.getElementById(targetId);
      if (targetBtn) {
        targetBtn.classList.remove('bg-white/5', 'text-slate-300', 'border-white/10');
        targetBtn.classList.add('bg-white/20', 'text-white', 'shadow-md', 'border-white/30');
      }
      renderLobbyScreen();
    }

    function renderExamCardHTML(e) {
      let statusColor = 'bg-slate-500/10 text-slate-400 border border-slate-500/20';
      if (e.exam_status === 'Started') {
        statusColor = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
      } else if (e.exam_status === 'Finished') {
        statusColor = 'bg-red-500/10 text-red-400 border border-red-500/20';
      }

      const isMidterm = (e.exam_type || '').includes('กลางภาค');
      const isFinal = (e.exam_type || '').includes('ปลายภาค');

      let typeBadgeClass = 'bg-sky-500/20 text-sky-300 border border-sky-400/50 shadow-[0_0_10px_rgba(14,165,233,0.15)]';
      let typeIcon = '📝';
      let cardBorderAccent = 'hover:border-pink-500/30';

      if (isMidterm) {
        typeBadgeClass = 'bg-gradient-to-r from-amber-500/25 via-orange-500/20 to-amber-500/25 text-amber-300 border border-amber-400/60 shadow-[0_0_12px_rgba(245,158,11,0.25)]';
        typeIcon = '🎯';
        cardBorderAccent = 'hover:border-amber-400/60 hover:shadow-amber-500/10';
      } else if (isFinal) {
        typeBadgeClass = 'bg-gradient-to-r from-purple-500/30 via-fuchsia-500/25 to-pink-500/25 text-purple-200 border border-purple-400/60 shadow-[0_0_12px_rgba(168,85,247,0.25)]';
        typeIcon = '🏁';
        cardBorderAccent = 'hover:border-purple-400/60 hover:shadow-purple-500/10';
      }

      return `
        <div class="glass-panel rounded-3xl p-6 border border-white/5 ${cardBorderAccent} transition-all flex flex-col justify-between hover:scale-[1.02] shadow-lg group">
          <div>
            <div class="flex justify-between items-start gap-2 mb-2">
              <span class="text-xs font-bold text-pink-400 font-mono">${escapeHtml(e.subject_code || '')}</span>
              <span class="px-2 py-0.5 text-[10px] font-bold rounded-lg ${statusColor}">${escapeHtml(e.exam_status || 'Draft')}</span>
            </div>

            <!-- Highly Prominent Exam Type Badge & Semester Tag -->
            <div class="flex items-center justify-between gap-2 mb-3">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-black rounded-xl ${typeBadgeClass}">
                <span>${typeIcon}</span> ${escapeHtml(e.exam_type || 'ทั่วไป')}
              </span>
              <div class="flex items-center gap-1.5 flex-wrap justify-end">
                ${e.exam_mode === 'pokemon'
                  ? `<span class="px-2 py-0.5 text-[10px] font-black rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm flex items-center gap-1" title="รูปแบบการสอบ: เดินเล่นเกม Pokémon RPG"><span>⚡</span> Pokémon</span>`
                  : (e.exam_mode === 'escape_room'
                    ? `<span class="px-2 py-0.5 text-[10px] font-black rounded-lg bg-violet-500/20 text-violet-300 border border-violet-500/40 shadow-sm flex items-center gap-1" title="รูปแบบการสอบ: Coin Quest"><span>🪙</span> Coin Quest</span>`
                    : `<span class="px-2 py-0.5 text-[10px] font-bold rounded-lg bg-slate-700/60 text-slate-300 border border-white/5" title="รูปแบบการสอบ: ข้อสอบมาตรฐาน"><span>📝</span> ปกติ</span>`)
                }
                <span class="text-[10px] text-slate-300 font-bold bg-white/5 border border-white/10 px-2 py-0.5 rounded-lg">
                  เทอม ${escapeHtml(e.semester || '-')}/${escapeHtml(e.academic_year || '-')}
                </span>
              </div>
            </div>

            <h3 class="text-lg font-black text-white mb-2 truncate group-hover:text-pink-400 transition-colors" title="${escapeHtml(e.subject_name || '')}">
              ${escapeHtml(e.subject_name || '')}
            </h3>
            <div class="space-y-1 text-xs text-slate-400 font-medium">
              <p>🏫 กลุ่มสาระฯ: ${escapeHtml(e.learning_area || '-')}</p>
              <p>🧑‍🏫 ผู้สอน: ${escapeHtml(e.teacher_name || '-')}</p>
              <p>📚 ในคลัง: ปรนัย ${e.choice_count || 0} ข้อ &nbsp;/&nbsp; อัตนัย ${e.writing_count || 0} ข้อ</p>
              <p>🕒 สุ่มสอบ: ${e.num_questions || 0} ข้อ (สิทธิ์สอบ ${e.max_attempts || 1} ครั้ง)</p>
            </div>
          </div>
          <div class="grid grid-cols-4 gap-2 mt-6 pt-4 border-t border-white/5">
            <a href="/teacher/questions?exam_id=${e.id}" class="col-span-2 py-2.5 bg-gradient-to-r from-pink-500 to-sky-400 hover:from-pink-600 hover:to-sky-500 text-white font-bold rounded-xl text-xs transition-all text-center flex items-center justify-center gap-1">
              ⚙️ จัดการข้อสอบ
            </a>
            <button onclick='duplicateExamModal(${JSON.stringify(e)})' class="py-2.5 bg-sky-500/10 hover:bg-sky-500/25 text-sky-400 hover:text-sky-300 font-bold rounded-xl text-xs border border-sky-500/30 transition-all text-center flex items-center justify-center gap-1 cursor-pointer" title="คัดลอกวิชาสอบนี้ (เปลี่ยนเทอม/ปี/ประเภท)">
              📋
            </button>
            <button onclick='editExam(${JSON.stringify(e)})' class="py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold rounded-xl text-xs border border-white/10 transition-all text-center flex items-center justify-center cursor-pointer" title="แก้ไขวิชา">
              ✏️
            </button>
          </div>
        </div>
      `;
    }

    function renderLobbyScreen() {
      isInWorkspace = false;
      clearInterval(activeMonitorInterval);
      document.getElementById('lobby-screen').classList.remove('hidden');
      document.getElementById('workspace-screen').classList.add('hidden');

      const grid = document.getElementById('exam-cards-grid');

      // 1. Filter exams by selected academic year
      const yearFilteredList = globalExamsList.filter(e => {
        const cardYear = (e.academic_year || '').trim();
        if (teacherSelectedYear !== 'all' && teacherSelectedYear !== 'latest') {
          return cardYear === teacherSelectedYear;
        }
        return true;
      });

      // 2. Filter exams list by search keyword
      const searchInput = document.getElementById('teacherExamSearch');
      const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';

      const searchedList = yearFilteredList.filter(e => {
        if (!searchVal) return true;
        const cardYear = (e.academic_year || '').trim();
        return (e.subject_name || '').toLowerCase().includes(searchVal) ||
               (e.subject_code || '').toLowerCase().includes(searchVal) ||
               (e.teacher_name || '').toLowerCase().includes(searchVal) ||
               (e.exam_type || '').toLowerCase().includes(searchVal) ||
               cardYear.includes(searchVal) ||
               (e.learning_area || '').toLowerCase().includes(searchVal);
      });

      // 3. Partition into Midterm, Final, and Other exams
      const midtermExams = searchedList.filter(e => (e.exam_type || '').includes('กลางภาค'));
      const finalExams = searchedList.filter(e => (e.exam_type || '').includes('ปลายภาค'));
      const otherExams = searchedList.filter(e => !(e.exam_type || '').includes('กลางภาค') && !(e.exam_type || '').includes('ปลายภาค'));

      // 4. Update count badges on teacher filter bar
      const btnAll = document.getElementById('t-filter-all');
      const btnMid = document.getElementById('t-filter-midterm');
      const btnFin = document.getElementById('t-filter-final');
      const btnOth = document.getElementById('t-filter-other');

      if (btnAll) btnAll.innerHTML = `🌟 ทั้งหมด <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 font-mono font-bold">${searchedList.length}</span>`;
      if (btnMid) btnMid.innerHTML = `🎯 สอบกลางภาค <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-500/30 text-amber-200 font-mono font-bold">${midtermExams.length}</span>`;
      if (btnFin) btnFin.innerHTML = `🏁 สอบปลายภาค <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-purple-500/30 text-purple-200 font-mono font-bold">${finalExams.length}</span>`;
      if (btnOth) {
        btnOth.innerHTML = `📝 สอบอื่นๆ <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-sky-500/30 text-sky-200 font-mono font-bold">${otherExams.length}</span>`;
        btnOth.style.display = otherExams.length > 0 ? 'inline-flex' : 'none';
      }

      // 5. Handle empty list state
      if (searchedList.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full py-16 text-center glass-panel rounded-3xl border border-white/5">
            <span class="text-4xl block mb-2">📚</span>
            <h3 class="text-lg font-bold text-white">ไม่พบรายวิชาสอบที่ตรงกับเงื่อนไข</h3>
            <p class="text-xs text-slate-400 mt-2">${globalExamsList.length === 0 ? 'กรุณากดปุ่ม "➕ เพิ่มวิชาสอบใหม่" ด้านบนเพื่อเริ่มใช้งาน' : 'กรุณาลองเปลี่ยนคำค้นหา หรือสลับปีการศึกษา'}</p>
          </div>
        `;
        return;
      }

      // 6. Build separated sections HTML
      let sectionsHtml = '';

      // Section: Midterm Exams (🎯 สอบกลางภาค)
      if (teacherExamFilter === 'all' || teacherExamFilter === 'กลางภาค') {
        const hasCards = midtermExams.length > 0;
        sectionsHtml += `
          <section class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-amber-500/30 flex-wrap gap-2">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-500/25 to-orange-500/20 border border-amber-500/40 flex items-center justify-center text-xl shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                  🎯
                </div>
                <div>
                  <div class="flex items-center gap-2.5">
                    <h3 class="text-lg sm:text-xl font-black text-white tracking-wide">รายวิชาสอบกลางภาค (Midterm Exams)</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm">
                      ${midtermExams.length} วิชา
                    </span>
                  </div>
                  <p class="text-xs text-slate-400 mt-0.5">การสอบวัดผลสัมฤทธิ์ทางการเรียนช่วงกลางภาคเรียน</p>
                </div>
              </div>
            </div>

            ${hasCards ? `
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                ${midtermExams.map(e => renderExamCardHTML(e)).join('')}
              </div>
            ` : `
              <div class="py-10 text-center glass-panel rounded-2xl border border-white/5 text-slate-400 text-xs">
                ยังไม่มีรายวิชาสำหรับการสอบกลางภาคในปีการศึกษานี้
              </div>
            `}
          </section>
        `;
      }

      // Section: Final Exams (🏁 สอบปลายภาค)
      if (teacherExamFilter === 'all' || teacherExamFilter === 'ปลายภาค') {
        const hasCards = finalExams.length > 0;
        sectionsHtml += `
          <section class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-purple-500/30 flex-wrap gap-2">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-500/25 to-fuchsia-500/20 border border-purple-500/40 flex items-center justify-center text-xl shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                  🏁
                </div>
                <div>
                  <div class="flex items-center gap-2.5">
                    <h3 class="text-lg sm:text-xl font-black text-white tracking-wide">รายวิชาสอบปลายภาค (Final Exams)</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-purple-500/20 text-purple-200 border border-purple-500/40 shadow-sm">
                      ${finalExams.length} วิชา
                    </span>
                  </div>
                  <p class="text-xs text-slate-400 mt-0.5">การสอบประเมินผลสัมฤทธิ์ปลายภาคเรียน</p>
                </div>
              </div>
            </div>

            ${hasCards ? `
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                ${finalExams.map(e => renderExamCardHTML(e)).join('')}
              </div>
            ` : `
              <div class="py-10 text-center glass-panel rounded-2xl border border-white/5 text-slate-400 text-xs">
                ยังไม่มีรายวิชาสำหรับการสอบปลายภาคในปีการศึกษานี้
              </div>
            `}
          </section>
        `;
      }

      // Section: Other Exams (📝 สอบเก็บคะแนน / อื่นๆ)
      if ((teacherExamFilter === 'all' && otherExams.length > 0) || teacherExamFilter === 'other') {
        sectionsHtml += `
          <section class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-sky-500/30 flex-wrap gap-2">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-sky-500/25 to-indigo-500/20 border border-sky-500/40 flex items-center justify-center text-xl shadow-[0_0_15px_rgba(14,165,233,0.2)]">
                  📝
                </div>
                <div>
                  <div class="flex items-center gap-2.5">
                    <h3 class="text-lg sm:text-xl font-black text-white tracking-wide">แบบทดสอบเก็บคะแนน / อื่นๆ</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-sky-500/20 text-sky-300 border border-sky-500/40 shadow-sm">
                      ${otherExams.length} วิชา
                    </span>
                  </div>
                  <p class="text-xs text-slate-400 mt-0.5">แบบทดสอบก่อนเรียน หลังเรียน หรือสอบเก็บคะแนนย่อย</p>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              ${otherExams.map(e => renderExamCardHTML(e)).join('')}
            </div>
          </section>
        `;
      }

      grid.innerHTML = sectionsHtml;
    }

    function enterWorkspace(examId, tabName = 'questions') {
      const exam = globalExamsList.find(e => e.id === examId);
      if (!exam) return;

      activeExamId = examId;
      isInWorkspace = true;

      document.getElementById('lobby-screen').classList.add('hidden');
      document.getElementById('workspace-screen').classList.remove('hidden');

      // Render Active Subject Info in Workspace Sidebar
      document.getElementById('workspace-subject-code').textContent = exam.subject_code;
      document.getElementById('workspace-subject-name').textContent = exam.subject_name;
      document.getElementById('workspace-subject-status').textContent = exam.exam_status;
      document.getElementById('workspace-subject-area').textContent = `${exam.learning_area} (${exam.academic_year || '-'}/${exam.semester || '-'})`;

      const typeEl = document.getElementById('workspace-subject-type-badge');
      if (typeEl) {
        const isMidterm = (exam.exam_type || '').includes('กลางภาค');
        const isFinal = (exam.exam_type || '').includes('ปลายภาค');
        if (isMidterm) {
          typeEl.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-amber-500/20 text-amber-300 border border-amber-400/60 shadow-sm">🎯 ${exam.exam_type}</span>`;
        } else if (isFinal) {
          typeEl.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-purple-500/20 text-purple-200 border border-purple-400/60 shadow-sm">🏁 ${exam.exam_type}</span>`;
        } else {
          typeEl.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-sky-500/20 text-sky-300 border border-sky-400/40">📝 ${exam.exam_type}</span>`;
        }
      }

      // Set status badge style
      const statusEl = document.getElementById('workspace-subject-status');
      statusEl.className = "px-2 py-0.5 text-[10px] font-bold rounded ";
      if (exam.exam_status === 'Started') {
        statusEl.className += "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20";
      } else if (exam.exam_status === 'Finished') {
        statusEl.className += "bg-red-500/10 text-red-400 border border-red-500/20";
      } else {
        statusEl.className += "bg-slate-500/10 text-slate-400 border border-slate-500/20";
      }

      switchTab(tabName);
    }

    function exitWorkspace() {
      window.location.href = '/teacher/dashboard';
    }

    function enterGlobalSettings() {
      activeExamId = 'global';
      isInWorkspace = true;

      document.getElementById('lobby-screen').classList.add('hidden');
      document.getElementById('workspace-screen').classList.remove('hidden');

      document.getElementById('workspace-subject-code').textContent = "SYSTEM";
      document.getElementById('workspace-subject-name').textContent = "การตั้งค่าระบบส่วนกลาง";
      document.getElementById('workspace-subject-status').textContent = "Global";
      document.getElementById('workspace-subject-area').textContent = "Settings";

      switchTab('settings');
    }

    function enterManualTab() {
      isInWorkspace = true;
      document.getElementById('lobby-screen').classList.add('hidden');
      document.getElementById('workspace-screen').classList.remove('hidden');

      if (!activeExamId || activeExamId === 'global') {
        activeExamId = 'global';
        document.getElementById('workspace-subject-code').textContent = "MANUAL";
        document.getElementById('workspace-subject-name').textContent = "คู่มือการใช้งานระบบ";
        document.getElementById('workspace-subject-status').textContent = "Guide";
        document.getElementById('workspace-subject-area').textContent = "Documentation";
      }

      switchTab('manual');
    }

    function filterManualTopics() {
      const searchInput = document.getElementById('manualTopicSearch');
      const val = searchInput ? searchInput.value.toLowerCase().trim() : '';
      const cards = document.querySelectorAll('.manual-card');
      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const keywords = (card.getAttribute('data-keywords') || '').toLowerCase();
        if (!val || text.includes(val) || keywords.includes(val)) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
    }

    function printManualDocument() {
      const printWin = window.open('', '_blank');
      const contentEl = document.getElementById('section-manual');
      if (!contentEl) return;

      const printHtml = `
      <!DOCTYPE html>
      <html lang="th">
      <head>
        <meta charset="utf-8">
        <title>คู่มือการใช้งานระบบจัดการข้อสอบออนไลน์</title>
        <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
          body { font-family: 'Sarabun', sans-serif; margin: 1.5cm; color: #111; line-height: 1.6; }
          h2, h3, h4 { color: #000; margin-bottom: 8px; }
          .manual-card { border: 1px solid #ddd; border-radius: 12px; padding: 20px; margin-bottom: 20px; page-break-inside: avoid; }
          table { width: 100%; border-collapse: collapse; margin: 10px 0; }
          th, td { border: 1px solid #999; padding: 6px 10px; font-size: 12px; }
          th { background: #eee; font-weight: bold; }
          code { background: #f0f0f0; padding: 2px 4px; border-radius: 4px; font-size: 11px; }
          @media print {
            button, input, a { display: none !important; }
            .manual-card { border-color: #aaa; }
          }
        </style>
      </head>
      <body>
        <div style="text-align: right; margin-bottom: 20px;">
          <button onclick="window.print()" style="padding: 8px 18px; background: #0284c7; color: #fff; font-weight: bold; border-radius: 6px; cursor: pointer; border: none; font-size: 14px;">🖨️ สั่งพิมพ์ (Print)</button>
        </div>
        <div style="text-align: center; margin-bottom: 30px;">
          <h1 style="margin: 0; font-size: 24px;">คู่มือการใช้งานระบบจัดการข้อสอบออนไลน์</h1>
          <p style="margin: 4px 0 0 0; font-size: 14px; color: #555;">สำหรับคุณครูผู้สอนและผู้ดูแลระบบ | โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์</p>
        </div>
        ${contentEl.innerHTML}
      </body>
      </html>
      `;

      printWin.document.write(printHtml);
      printWin.document.close();
    }

    function switchTab(tabName) {
      currentTab = tabName;
      document.querySelectorAll('.section-content').forEach(s => s.classList.add('hidden'));
      const targetSection = document.getElementById(`section-${tabName}`);
      if (targetSection) {
        targetSection.classList.remove('hidden');
      }

      document.querySelectorAll('[id^="tab-btn-"]').forEach(b => b.classList.remove('active-tab'));
      const activeBtn = document.getElementById(`tab-btn-${tabName}`);
      if (activeBtn) activeBtn.classList.add('active-tab');

      clearInterval(activeMonitorInterval);

      if (tabName === 'settings') {
        loadSettings();
      } else if (tabName === 'monitor') {
        loadMonitor();
        activeMonitorInterval = setInterval(loadMonitor, 3000);
      } else if (tabName === 'questions') {
        loadQuestions();
      } else if (tabName === 'results') {
        loadResults();
      } else if (tabName === 'logs') {
        loadLogs();
      } else if (tabName === 'exam-settings') {
        loadActiveExamSettings();
      } else if (tabName === 'manual') {
        const m = document.getElementById('section-manual');
        if (m) m.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    async function logout() {
      try {
        await fetch('/api/teacher/logout', { method: 'POST' });
        window.location.href = '/teacher';
      } catch (e) {
        window.location.href = '/teacher';
      }
    }

    function openExamModal() {
      showExamModalSwal({
        id: '',
        subject_code: '',
        subject_name: '',
        academic_year: '',
        semester: '',
        learning_area: teacherSession.learningArea || 'วิทยาศาสตร์และเทคโนโลยี',
        teacher_name: teacherSession.name || '',
        exam_type: 'สอบกลางภาค',
        exam_status: 'Pending',
        num_questions: '20',
        max_attempts: '1',
        passing_percentage: '50',
        time_limit_choice: '60',
        time_limit_writing: '300',
        exam_duration: '0',
        exam_round: '1',
        anti_cheating: '1',
        max_strikes: '3',
        exam_mode: 'classic'
      }, '🏫 เพิ่มรายวิชาสอบใหม่');
    }

    function editExam(e) {
      if (window.event) window.event.stopPropagation();
      showExamModalSwal(e, '🏫 แก้ไขรายวิชาสอบ');
    }

    function duplicateCurrentExam() {
      const exam = globalExamsList.find(e => e.id === activeExamId);
      if (exam) {
        duplicateExamModal(exam);
      } else {
        Swal.fire({ icon: 'error', title: 'ข้อผิดพลาด', text: 'ไม่พบข้อมูลวิชาที่เลือก' });
      }
    }

    function duplicateExamModal(e) {
      if (window.event) window.event.stopPropagation();
      const choiceCount = parseInt(e.choice_count) || 0;
      const writingCount = parseInt(e.writing_count) || 0;
      const totalQuestions = choiceCount + writingCount;

      const isMidterm = (e.exam_type || '').includes('กลางภาค');
      const isFinal = (e.exam_type || '').includes('ปลายภาค');
      const typeBadgeClass = isMidterm 
        ? 'bg-amber-500/20 text-amber-300 border-amber-500/40' 
        : (isFinal ? 'bg-purple-500/20 text-purple-300 border-purple-500/40' : 'bg-sky-500/20 text-sky-300 border-sky-500/40');
      const typeBadgeIcon = isMidterm ? '🎯' : (isFinal ? '🏁' : '📝');

      Swal.fire({
        title: '📋 คัดลอกรายวิชาสอบ (Duplicate Exam)',
        html: `
          <div class="text-left space-y-4 max-h-[75vh] overflow-y-auto w-full pr-1 font-sans">
            
            <!-- Source Exam Info Card -->
            <div class="bg-slate-800/90 border-2 border-slate-700/80 rounded-2xl p-4 shadow-inner">
              <div class="flex items-center justify-between gap-2 mb-2 flex-wrap">
                <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                  <span>📄</span> วิชาต้นฉบับที่จะคัดลอก
                </span>
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black border ${typeBadgeClass}">
                    <span>${typeBadgeIcon}</span>
                    <span>${escapeHtml(e.exam_type || 'ทั่วไป')}</span>
                  </span>
                  <span class="text-xs font-bold text-slate-300 bg-slate-700/80 px-2 py-0.5 rounded-md border border-slate-600">
                    เทอม ${escapeHtml(e.semester || '-')}/${escapeHtml(e.academic_year || '-')}
                  </span>
                </div>
              </div>
              <h4 class="text-base font-black text-white leading-snug">${escapeHtml(e.subject_name)}</h4>
              <div class="flex items-center gap-3 mt-1 text-xs text-slate-300 font-semibold flex-wrap">
                <span class="text-sky-400 font-bold font-mono">รหัส: ${escapeHtml(e.subject_code || '-')}</span>
                <span class="text-slate-500">•</span>
                <span>กลุ่มสาระฯ: ${escapeHtml(e.learning_area || '-')}</span>
              </div>
              <div class="mt-2 pt-2 border-t border-slate-700/60 flex items-center justify-between text-xs">
                <span class="text-emerald-400 font-bold flex items-center gap-1">
                  <span>📊</span> คลังข้อสอบเดิม: ${totalQuestions > 0 ? `${totalQuestions} ข้อ (ปรนัย ${choiceCount} / อัตนัย ${writingCount})` : 'ยังไม่มีข้อสอบ'}
                </span>
                <span class="text-slate-400 text-[11px]">เกณฑ์ผ่าน ${escapeHtml(e.passing_percentage || '50')}%</span>
              </div>
            </div>

            <!-- New Subject Info -->
            <div>
              <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                <span class="text-pink-400">✏️</span> ชื่อวิชาใหม่ <span class="text-rose-400 font-bold">*</span>
              </label>
              <input type="text" id="swal-dup-name" value="${escapeHtml(e.subject_name)} (คัดลอก)" 
                class="w-full bg-slate-800 border-2 border-slate-600 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold outline-none transition-all placeholder:text-slate-500" 
                placeholder="ระบุชื่อวิชาสำหรับวิชาใหม่" required>
            </div>

            <!-- Subject Code & Area -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                  <span class="text-sky-400">🔖</span> รหัสวิชา <span class="text-rose-400 font-bold">*</span>
                </label>
                <input type="text" id="swal-dup-code" value="${escapeHtml(e.subject_code || '')}" 
                  class="w-full bg-slate-800 border-2 border-slate-600 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold outline-none transition-all placeholder:text-slate-500 font-mono" 
                  placeholder="เช่น ค31101" required>
              </div>
              <div>
                <label class="block text-xs font-extrabold text-slate-400 mb-1.5 flex items-center gap-1">
                  <span>🏢</span> กลุ่มสาระฯ (เดิม)
                </label>
                <input type="text" value="${escapeHtml(e.learning_area || '')}" disabled 
                  class="w-full bg-slate-800/50 border border-slate-700/80 rounded-xl px-3.5 py-2.5 text-sm text-slate-400 font-semibold cursor-not-allowed">
              </div>
            </div>

            <!-- Academic Year & Semester -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                  <span class="text-amber-400">📅</span> ปีการศึกษา <span class="text-rose-400 font-bold">*</span>
                </label>
                <input type="text" id="swal-dup-year" value="${escapeHtml(e.academic_year || '')}" 
                  class="w-full bg-slate-800 border-2 border-slate-600 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold outline-none transition-all placeholder:text-slate-500 font-mono" 
                  placeholder="เช่น 2569" required>
              </div>
              <div>
                <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                  <span class="text-amber-400">🗓️</span> ภาคเรียนที่ (เทอม) <span class="text-rose-400 font-bold">*</span>
                </label>
                <select id="swal-dup-semester" 
                  class="w-full bg-slate-800 border-2 border-slate-600 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold outline-none transition-all cursor-pointer">
                  <option value="1" ${String(e.semester) === '1' ? 'selected' : ''}>ภาคเรียนที่ 1</option>
                  <option value="2" ${String(e.semester) === '2' ? 'selected' : ''}>ภาคเรียนที่ 2</option>
                  <option value="ฤดูร้อน" ${String(e.semester) === 'ฤดูร้อน' ? 'selected' : ''}>ภาคฤดูร้อน</option>
                </select>
              </div>
            </div>

            <!-- Exam Type & Round -->
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                  <span class="text-purple-400">🎯</span> ประเภทการสอบ <span class="text-rose-400 font-bold">*</span>
                </label>
                <select id="swal-dup-type" 
                  class="w-full bg-slate-800 border-2 border-slate-600 focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-black outline-none transition-all cursor-pointer">
                  <option value="สอบกลางภาค" ${e.exam_type === 'สอบกลางภาค' ? 'selected' : ''}>🎯 สอบกลางภาค (Midterm)</option>
                  <option value="สอบปลายภาค" ${e.exam_type === 'สอบปลายภาค' ? 'selected' : ''}>🏁 สอบปลายภาค (Final)</option>
                  <option value="สอบเก็บคะแนนย่อย" ${e.exam_type === 'สอบเก็บคะแนนย่อย' ? 'selected' : ''}>📝 สอบเก็บคะแนนย่อย</option>
                  <option value="ทดสอบก่อนเรียน" ${e.exam_type === 'ทดสอบก่อนเรียน' ? 'selected' : ''}>📖 ทดสอบก่อนเรียน</option>
                  <option value="ทดสอบหลังเรียน" ${e.exam_type === 'ทดสอบหลังเรียน' ? 'selected' : ''}>📘 ทดสอบหลังเรียน</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                  <span class="text-purple-400">🔄</span> รอบการสอบ
                </label>
                <input type="text" id="swal-dup-round" value="${escapeHtml(e.exam_round || '1')}" 
                  class="w-full bg-slate-800 border-2 border-slate-600 focus:border-purple-400 focus:ring-2 focus:ring-purple-400/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-bold outline-none transition-all placeholder:text-slate-500 font-mono" 
                  placeholder="เช่น 1, 2">
              </div>
            </div>

            <!-- Exam Mode (รูปแบบการสอบ) -->
            <div>
              <label class="block text-xs font-extrabold text-slate-200 mb-1.5 flex items-center gap-1">
                <span class="text-amber-400">🎮</span> รูปแบบการสอบ (Exam Mode)
              </label>
              <select id="swal-dup-mode" 
                class="w-full bg-slate-800 border-2 border-slate-600 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 rounded-xl px-3.5 py-2.5 text-sm text-white font-black outline-none transition-all cursor-pointer">
                <option value="classic" ${(!e.exam_mode || e.exam_mode === 'classic') ? 'selected' : ''}>📝 แบบมาตรฐาน (Classic Mode)</option>
                <option value="pokemon" ${e.exam_mode === 'pokemon' ? 'selected' : ''}>⚡ เดินเกม Pokémon RPG (Gamified Mode)</option>
                <option value="escape_room" ${e.exam_mode === 'escape_room' ? 'selected' : ''}>🪙 Coin Quest (กระโดดเก็บเหรียญ ตอบคำถาม)</option>
              </select>
            </div>

            <!-- Copy Questions Option (Card) -->
            <div class="p-4 bg-gradient-to-br from-sky-950/70 via-slate-850 to-slate-900 border-2 border-sky-500/50 rounded-2xl shadow-lg shadow-sky-950/30">
              <label class="flex items-start gap-3 cursor-pointer select-none">
                <input type="checkbox" id="swal-dup-copy-q" checked 
                  class="mt-1 w-5 h-5 rounded-md text-sky-500 focus:ring-sky-400 focus:ring-offset-slate-900 bg-slate-900 border-slate-500 cursor-pointer accent-sky-500">
                <div class="flex-1">
                  <div class="flex items-center gap-2">
                    <span class="text-sm font-black text-sky-300">คัดลอกข้อสอบทั้งหมดในคลังมาด้วย</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-500/25 text-sky-300 border border-sky-500/40">แนะนำ</span>
                  </div>
                  <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                    ${totalQuestions > 0 
                      ? `ระบบจะโคลนข้อสอบทั้งหมด <strong class="text-white font-black">${totalQuestions} ข้อ</strong> (ปรนัย ${choiceCount} ข้อ, อัตนัย ${writingCount} ข้อ) พร้อมตัวเลือกและเฉลยไปยังวิชาใหม่อัตโนมัติ` 
                      : 'หากเลือกตัวเลือกนี้ ระบบจะโคลนข้อสอบทั้งหมดไปยังวิชาใหม่'}
                  </p>
                </div>
              </label>
            </div>

            <!-- Info footnote -->
            <div class="flex items-center gap-2 text-xs text-slate-300 bg-slate-800/80 px-3.5 py-2.5 rounded-xl border border-slate-700">
              <span class="text-base">💡</span>
              <span>วิชาที่คัดลอกใหม่จะอยู่ในสถานะ <strong class="text-amber-300 font-extrabold">"ฉบับร่าง (Draft)"</strong> เพื่อให้คุณครูตรวจทานหรือปรับแก้ข้อสอบก่อนเปิดสอบจริง</span>
            </div>

          </div>
        `,
        width: '680px',
        background: '#0f172a',
        color: '#ffffff',
        showCancelButton: true,
        confirmButtonText: '📋 ยืนยันคัดลอกวิชา',
        cancelButtonText: 'ยกเลิก',
        customClass: {
          popup: 'border-2 border-slate-700/80 rounded-3xl shadow-2xl backdrop-blur-xl bg-slate-900 text-white',
          title: 'text-lg font-black text-white text-left px-6 pt-5 pb-3 border-b border-slate-800 flex items-center gap-2',
          htmlContainer: 'text-left px-6 py-3',
          confirmButton: 'bg-gradient-to-r from-sky-500 via-indigo-600 to-indigo-700 hover:from-sky-400 hover:to-indigo-600 text-white font-extrabold rounded-xl px-6 py-3 border-none shadow-lg shadow-sky-500/25 cursor-pointer text-sm transition-all',
          cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl px-5 py-3 border border-slate-600 cursor-pointer text-sm transition-all'
        },
        focusConfirm: false,
        preConfirm: () => {
          const name = document.getElementById('swal-dup-name').value.trim();
          const code = document.getElementById('swal-dup-code').value.trim();
          const year = document.getElementById('swal-dup-year').value.trim();
          const semester = document.getElementById('swal-dup-semester').value.trim();
          const type = document.getElementById('swal-dup-type').value;
          const round = document.getElementById('swal-dup-round').value.trim() || '1';
          const mode = document.getElementById('swal-dup-mode')?.value || 'classic';
          const copyQ = document.getElementById('swal-dup-copy-q').checked;

          if (!name || !code || !year || !semester) {
            Swal.showValidationMessage('กรุณากรอกชื่อวิชา, รหัสวิชา, ปีการศึกษา และภาคเรียนให้ครบถ้วน');
            return false;
          }
          return { name, code, year, semester, type, round, mode, copyQ };
        }
      }).then(async (res) => {
        if (!res.isConfirmed || !res.value) return;

        Swal.fire({
          title: 'กำลังคัดลอกวิชาสอบ...',
          text: 'กรุณารอสักครู่ ระบบกำลังสร้างรายวิชาและโคลนข้อสอบ',
          allowOutsideClick: false,
          background: '#0f172a',
          color: '#ffffff',
          customClass: {
            popup: 'border-2 border-slate-700/80 rounded-3xl shadow-2xl bg-slate-900',
            title: 'text-lg font-black text-white'
          },
          didOpen: () => Swal.showLoading()
        });

        try {
          const fd = new FormData();
          fd.append('source_id', e.id);
          fd.append('subject_name', res.value.name);
          fd.append('subject_code', res.value.code);
          fd.append('academic_year', res.value.year);
          fd.append('semester', res.value.semester);
          fd.append('exam_type', res.value.type);
          fd.append('exam_round', res.value.round);
          fd.append('exam_mode', res.value.mode);
          fd.append('copy_questions', res.value.copyQ ? 'true' : 'false');

          const response = await fetch('/api/teacher/exams/duplicate', {
            method: 'POST',
            body: fd
          });
          const data = await response.json();
          Swal.close();

          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'คัดลอกรายวิชาสำเร็จ! 🎉',
              html: `
                <div class="text-center py-2">
                  <p class="text-white text-base font-bold mb-2">${escapeHtml(data.message || 'สร้างวิชาใหม่เรียบร้อยแล้ว')}</p>
                  <p class="text-xs text-slate-300">สถานะวิชาใหม่เป็น <strong class="text-amber-300">"ฉบับร่าง (Draft)"</strong> คุณครูสามารถเข้าไปตรวจทานหรือแก้ไขข้อสอบได้ทันที</p>
                </div>
              `,
              background: '#0f172a',
              color: '#ffffff',
              showCancelButton: true,
              confirmButtonText: '⚙️ ไปจัดการข้อสอบวิชาใหม่',
              cancelButtonText: 'ดูรายการวิชาทั้งหมด',
              customClass: {
                popup: 'border-2 border-slate-700/80 rounded-3xl shadow-2xl bg-slate-900',
                title: 'text-xl font-black text-white',
                confirmButton: 'bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-400 hover:to-rose-500 text-white font-extrabold rounded-xl px-6 py-3 border-none shadow-lg shadow-pink-500/25 cursor-pointer text-sm',
                cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl px-5 py-3 border border-slate-600 cursor-pointer text-sm'
              }
            }).then(actionRes => {
              if (actionRes.isConfirmed && data.new_exam_id) {
                window.location.href = `/teacher/questions?exam_id=${data.new_exam_id}`;
              } else {
                loadGlobalExams().then(() => renderLobbyScreen());
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'ไม่สามารถคัดลอกวิชาได้',
              text: data.message || 'เกิดข้อผิดพลาดในการคัดลอกวิชา',
              background: '#0f172a',
              color: '#ffffff',
              customClass: {
                popup: 'border-2 border-slate-700/80 rounded-3xl shadow-2xl bg-slate-900',
                title: 'text-lg font-black text-white',
                confirmButton: 'bg-slate-800 text-white font-bold rounded-xl px-5 py-2.5 border border-slate-600'
              }
            });
          }
        } catch (err) {
          Swal.close();
          Swal.fire({ 
            icon: 'error', 
            title: 'เกิดข้อผิดพลาด', 
            text: err.message,
            background: '#0f172a',
            color: '#ffffff',
            customClass: {
              popup: 'border-2 border-slate-700/80 rounded-3xl shadow-2xl bg-slate-900',
              title: 'text-lg font-black text-white'
            }
          });
        }
      });
    }

    function showExamModalSwal(eData, title) {
      const html = `
        <div class="text-left space-y-4 max-h-[70vh] overflow-y-auto w-full pr-2">
          <input type="hidden" id="swal-e-id" value="${eData.id || ''}">
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">รหัสวิชา</label>
              <input type="text" id="swal-e-code" value="${escapeHtml(eData.subject_code || '')}" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required placeholder="เช่น ค31101">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">ชื่อวิชา</label>
              <input type="text" id="swal-e-name" value="${escapeHtml(eData.subject_name || '')}" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required placeholder="เช่น คณิตศาสตร์พื้นฐาน 1">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">ปีการศึกษา</label>
              <input type="text" id="swal-e-year" value="${escapeHtml(eData.academic_year || '')}" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required placeholder="เช่น 2569">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">ภาคเรียนที่ (เทอม)</label>
              <input type="text" id="swal-e-semester" value="${escapeHtml(eData.semester || '')}" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required placeholder="เช่น 1 หรือ ฤดูร้อน">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">กลุ่มสาระการเรียนรู้</label>
              <select id="swal-e-area" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                ${['ภาษาไทย', 'คณิตศาสตร์', 'วิทยาศาสตร์และเทคโนโลยี', 'สังคมศึกษา ศาสนา และวัฒนธรรม', 'สุขศึกษา และพลศึกษา', 'ศิลปะ', 'การงานอาชีพ', 'ภาษาต่างประเทศ', 'งานแนะแนว'].map(area => `
                  <option value="${area}" ${eData.learning_area === area ? 'selected' : ''}>${area}</option>
                `).join('')}
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">ชื่อครูผู้สอน</label>
              <input type="text" id="swal-e-teacher" value="${escapeHtml(eData.teacher_name || '')}" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">ประเภทการสอบ</label>
              <select id="swal-e-type" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <option value="สอบกลางภาค" ${eData.exam_type === 'สอบกลางภาค' ? 'selected' : ''}>สอบกลางภาค</option>
                <option value="สอบปลายภาค" ${eData.exam_type === 'สอบปลายภาค' ? 'selected' : ''}>สอบปลายภาค</option>
                <option value="สอบเก็บคะแนนย่อย" ${eData.exam_type === 'สอบเก็บคะแนนย่อย' ? 'selected' : ''}>สอบเก็บคะแนนย่อย</option>
                <option value="ทดสอบก่อนเรียน" ${eData.exam_type === 'ทดสอบก่อนเรียน' ? 'selected' : ''}>ทดสอบก่อนเรียน</option>
                <option value="ทดสอบหลังเรียน" ${eData.exam_type === 'ทดสอบหลังเรียน' ? 'selected' : ''}>ทดสอบหลังเรียน</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">สถานะ</label>
              <select id="swal-e-status" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <option value="Waiting" ${eData.exam_status === 'Waiting' ? 'selected' : ''}>⏳ เปิดห้องพักคอย (Waiting)</option>
                <option value="Started" ${eData.exam_status === 'Started' ? 'selected' : ''}>🟢 เปิดสอบอยู่ (Started)</option>
                <option value="Finished" ${eData.exam_status === 'Finished' ? 'selected' : ''}>🛑 ปิดการสอบแล้ว (Finished)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">รอบการสอบที่</label>
              <input type="text" id="swal-e-round" value="${eData.exam_round || '1'}" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required placeholder="เช่น 1, 2, แก้ตัว">
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">จำนวนข้อสอบ</label>
              <input type="number" id="swal-e-num" value="${eData.num_questions || '20'}" min="1" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">สิทธิ์สอบได้สูงสุด (ครั้ง)</label>
              <input type="number" id="swal-e-attempts" value="${eData.max_attempts || '1'}" min="1" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">เกณฑ์การผ่าน (%)</label>
              <input type="number" id="swal-e-pass" value="${eData.passing_percentage || '50'}" min="0" max="100" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">เวลาจำกัด/ข้อปรนัย (วิ)</label>
              <input type="number" id="swal-e-time-c" value="${eData.time_limit_choice || '60'}" min="5" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">เวลาจำกัด/ข้ออัตนัย (วิ)</label>
              <input type="number" id="swal-e-time-w" value="${eData.time_limit_writing || '300'}" min="10" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
            <div>
              <label class="block text-xs font-bold text-sky-400 mb-1">เวลาสอบรวมทั้งหมด (นาที)</label>
              <input type="number" id="swal-e-duration" value="${eData.exam_duration || '0'}" min="0" class="w-full bg-slate-800 border border-sky-500/20 rounded-xl p-3 text-white outline-none focus:border-sky-500" required placeholder="0 = ไม่จำกัดเวลา">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-amber-400 mb-1">ระบบป้องกันสลับหน้าจอ (จับโกง)</label>
              <select id="swal-e-anticheat" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
                <option value="1" ${String(eData.anti_cheating) === '1' ? 'selected' : ''}>🔒 เปิดใช้งาน (ตรวจจับสลับหน้าจอ)</option>
                <option value="0" ${String(eData.anti_cheating) === '0' ? 'selected' : ''}>🔓 ปิดใช้งาน</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-amber-400 mb-1">จำนวนครั้งสลับจอที่อนุญาต</label>
              <input type="number" id="swal-e-maxstrikes" value="${eData.max_strikes !== undefined && eData.max_strikes !== null ? eData.max_strikes : '3'}" min="1" max="10" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required>
            </div>
          </div>

          <!-- รูปแบบการสอบ (Exam Mode) -->
          <div class="p-3.5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-purple-500/10 to-sky-500/10 border border-amber-500/30">
            <label class="block text-xs font-black text-amber-300 uppercase tracking-wide mb-2 flex items-center justify-between">
              <span class="flex items-center gap-1.5"><span>🎮</span> รูปแบบการสอบ (Exam Mode)</span>
              <span class="text-[10px] text-slate-400 font-normal">กำหนดให้ผู้เรียนสอบในรูปแบบนี้</span>
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-white/10 bg-slate-800/90 cursor-pointer hover:border-sky-400 transition-all">
                <input type="radio" name="swal-e-mode" value="classic" ${(!eData.exam_mode || eData.exam_mode === 'classic') ? 'checked' : ''} class="accent-sky-500 w-4 h-4 cursor-pointer">
                <div>
                  <div class="text-xs font-bold text-white flex items-center gap-1"><span>📝</span> แบบมาตรฐาน</div>
                  <div class="text-[10px] text-slate-400">ข้อสอบทั่วไป ตัวเลือกเรียบง่าย</div>
                </div>
              </label>
              <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-white/10 bg-slate-800/90 cursor-pointer hover:border-amber-400 transition-all">
                <input type="radio" name="swal-e-mode" value="pokemon" ${eData.exam_mode === 'pokemon' ? 'checked' : ''} class="accent-amber-500 w-4 h-4 cursor-pointer">
                <div>
                  <div class="text-xs font-bold text-amber-300 flex items-center gap-1"><span>⚡</span> Pokémon RPG</div>
                  <div class="text-[10px] text-slate-400">เดินแผนที่ สู้โปเกมอน</div>
                </div>
              </label>
              <label class="flex items-center gap-2.5 p-2.5 rounded-xl border border-white/10 bg-slate-800/90 cursor-pointer hover:border-violet-400 transition-all">
                <input type="radio" name="swal-e-mode" value="escape_room" ${eData.exam_mode === 'escape_room' ? 'checked' : ''} class="accent-violet-500 w-4 h-4 cursor-pointer">
                <div>
                  <div class="text-xs font-bold text-violet-300 flex items-center gap-1"><span>🪙</span> Coin Quest</div>
                  <div class="text-[10px] text-slate-400">กระโดดเก็บเหรียญแล้วตอบคำถาม</div>
                </div>
              </label>
            </div>
          </div>
        </div>
      `;

      Swal.fire({
        title: title,
        html: html,
        width: '650px',
        background: '#0f172a',
        color: '#f8fafc',
        showCancelButton: true,
        confirmButtonText: 'บันทึกรายวิชา 💾',
        cancelButtonText: 'ยกเลิก',
        customClass: {
          title: 'text-lg font-black text-white text-left border-b border-white/5 pb-2',
          confirmButton: 'bg-pink-500 hover:bg-pink-600 font-bold rounded-xl px-6 py-2 border-none',
          cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-350 font-bold rounded-xl px-4 py-2 border border-white/10',
          popup: 'border border-white/10 rounded-3xl'
        },
        preConfirm: () => {
          const popup = Swal.getHtmlContainer();
          const id = popup.querySelector('#swal-e-id').value;
          const code = popup.querySelector('#swal-e-code').value.trim();
          const name = popup.querySelector('#swal-e-name').value.trim();
          const year = popup.querySelector('#swal-e-year').value.trim();
          const semester = popup.querySelector('#swal-e-semester').value.trim();
          const area = popup.querySelector('#swal-e-area').value;
          const teacher = popup.querySelector('#swal-e-teacher').value.trim();
          const type = popup.querySelector('#swal-e-type').value;
          const status = popup.querySelector('#swal-e-status').value;
          const round = popup.querySelector('#swal-e-round').value.trim();
          const num = popup.querySelector('#swal-e-num').value;
          const attempts = popup.querySelector('#swal-e-attempts').value;
          const pass = popup.querySelector('#swal-e-pass').value;
          const time_c = popup.querySelector('#swal-e-time-c').value;
          const time_w = popup.querySelector('#swal-e-time-w').value;
          const duration = popup.querySelector('#swal-e-duration').value;
          const anticheat = popup.querySelector('#swal-e-anticheat').value;
          const maxstrikes = popup.querySelector('#swal-e-maxstrikes').value;
          const modeRadio = popup.querySelector('input[name="swal-e-mode"]:checked');
          const mode = modeRadio ? modeRadio.value : 'classic';

          if (!code || !name || !year || !semester || !teacher || !round) {
            Swal.showValidationMessage('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน');
            return false;
          }

          return { id, code, name, year, semester, area, teacher, type, status, round, num, attempts, pass, time_c, time_w, duration, anticheat, maxstrikes, mode };
        }
      }).then(async (result) => {
        if (result.isConfirmed) {
          const data = result.value;
          const formData = new FormData();
          if (data.id) formData.append('id', data.id);
          formData.append('subject_code', data.code);
          formData.append('subject_name', data.name);
          formData.append('academic_year', data.year);
          formData.append('semester', data.semester);
          formData.append('learning_area', data.area);
          formData.append('teacher_name', data.teacher);
          formData.append('exam_type', data.type);
          formData.append('exam_status', data.status);
          formData.append('exam_round', data.round);
          formData.append('num_questions', data.num);
          formData.append('max_attempts', data.attempts);
          formData.append('passing_percentage', data.pass);
          formData.append('time_limit_choice', data.time_c);
          formData.append('time_limit_writing', data.time_w);
          formData.append('exam_duration', data.duration);
          formData.append('anti_cheating', data.anticheat);
          formData.append('max_strikes', data.maxstrikes);
          formData.append('exam_mode', data.mode);

          Swal.fire({ title: 'กำลังบันทึกรายวิชา...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          try {
            const response = await fetch('/api/teacher/exams/save', {
              method: 'POST',
              body: formData
            });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', 'บันทึกข้อมูลรายวิชาสอบเรียบร้อยแล้ว', 'success');
              await loadGlobalExams();
              if (isInWorkspace) {
                const updated = globalExamsList.find(item => item.id === activeExamId);
                if (updated) {
                  document.getElementById('workspace-subject-code').textContent = updated.subject_code;
                  document.getElementById('workspace-subject-name').textContent = updated.subject_name;
                  document.getElementById('workspace-subject-status').textContent = updated.exam_status;

                  const statusEl = document.getElementById('workspace-subject-status');
                  statusEl.className = "px-2 py-0.5 text-[10px] font-bold rounded ";
                  if (updated.exam_status === 'Started') {
                    statusEl.className += "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20";
                  } else if (updated.exam_status === 'Finished') {
                    statusEl.className += "bg-red-500/10 text-red-400 border border-red-500/20";
                  } else {
                    statusEl.className += "bg-slate-500/10 text-slate-400 border border-slate-500/20";
                  }
                }
              } else {
                renderLobbyScreen();
              }
            } else {
              Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
            }
          } catch (err) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดการเชื่อมต่อ', 'error');
          }
        }
      });
    }

    // --- Tab 1.5: Exam Settings (Workspace Specific) ---

    function loadActiveExamSettings() {
      const exam = globalExamsList.find(e => e.id === activeExamId);
      if (!exam) return;

      document.getElementById('workspace-e-id').value = exam.id;
      document.getElementById('workspace-e-code').value = exam.subject_code;
      document.getElementById('workspace-e-name').value = exam.subject_name;
      document.getElementById('workspace-e-year').value = exam.academic_year || '';
      document.getElementById('workspace-e-semester').value = exam.semester || '';
      document.getElementById('workspace-e-area').value = exam.learning_area;
      document.getElementById('workspace-e-teacher').value = exam.teacher_name;
      document.getElementById('workspace-e-type').value = exam.exam_type;
      document.getElementById('workspace-e-status').value = exam.exam_status;
      document.getElementById('workspace-e-num').value = exam.num_questions;
      document.getElementById('workspace-e-attempts').value = exam.max_attempts;
      document.getElementById('workspace-e-pass').value = exam.passing_percentage;
      document.getElementById('workspace-e-time-c').value = exam.time_limit_choice;
      document.getElementById('workspace-e-time-w').value = exam.time_limit_writing;
      document.getElementById('workspace-e-duration').value = exam.exam_duration || '0';
      document.getElementById('workspace-e-round').value = exam.exam_round || '1';
      document.getElementById('workspace-e-anticheat').value = exam.anti_cheating !== undefined ? String(exam.anti_cheating) : '1';
      document.getElementById('workspace-e-maxstrikes').value = exam.max_strikes !== undefined && exam.max_strikes !== null ? exam.max_strikes : '3';
      
      const currentMode = exam.exam_mode || 'classic';
      if (currentMode === 'pokemon') {
        const pokeRadio = document.getElementById('workspace-mode-pokemon');
        if (pokeRadio) pokeRadio.checked = true;
      } else if (currentMode === 'escape_room') {
        const escapeRadio = document.getElementById('workspace-mode-escape');
        if (escapeRadio) escapeRadio.checked = true;
      } else {
        const classicRadio = document.getElementById('workspace-mode-classic');
        if (classicRadio) classicRadio.checked = true;
      }
    }

    const activeExamSettingsFormEl = document.getElementById('activeExamSettingsForm');
    if (activeExamSettingsFormEl) {
      activeExamSettingsFormEl.onsubmit = async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        Swal.fire({ title: 'กำลังบันทึกตั้งค่าวิชาสอบ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        try {
          const response = await fetch('/api/teacher/exams/save', {
            method: 'POST',
            body: formData
          });
          const res = await response.json();
          Swal.close();
          if (res.success) {
            Swal.fire('สำเร็จ', 'บันทึกข้อมูลวิชาสอบเรียบร้อยแล้ว', 'success');
            await loadGlobalExams();

            // Update header details dynamically
            const updated = globalExamsList.find(item => item.id === activeExamId);
            if (updated) {
              document.getElementById('workspace-subject-code').textContent = updated.subject_code;
              document.getElementById('workspace-subject-name').textContent = updated.subject_name;
              document.getElementById('workspace-subject-status').textContent = updated.exam_status;

              // Set status badge style
              const statusEl = document.getElementById('workspace-subject-status');
              statusEl.className = "px-2 py-0.5 text-[10px] font-bold rounded ";
              if (updated.exam_status === 'Started') {
                statusEl.className += "bg-emerald-500/10 text-emerald-400 border border-emerald-500/20";
              } else if (updated.exam_status === 'Finished') {
                statusEl.className += "bg-red-500/10 text-red-400 border border-red-500/20";
              } else {
                statusEl.className += "bg-slate-500/10 text-slate-400 border border-slate-500/20";
              }
            }
          } else {
            Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
          }
        } catch (err) {
          Swal.close();
          Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        }
      };
    }

    // --- Tab 2: Settings (Global) ---
    async function loadSettings() {
      try {
        const response = await fetch('/api/teacher/settings');
        const res = await response.json();
        if (res.success && res.settings) {
          const form = document.getElementById('settingsForm');
          for (let key in res.settings) {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
              input.value = res.settings[key];
            }
          }
        }
      } catch (e) {
        console.error("Failed to load settings", e);
      }
    }

    document.getElementById('settingsForm').onsubmit = async (e) => {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      const settings = {};
      formData.forEach((value, key) => {
        settings[key] = value;
      });

      Swal.fire({ title: 'กำลังบันทึกการตั้งค่า...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

      const postData = new FormData();
      postData.append('settings', JSON.stringify(settings));

      try {
        const response = await fetch('/api/teacher/settings', {
          method: 'POST',
          body: postData
        });
        const res = await response.json();
        Swal.close();
        if (res.success) {
          Swal.fire('สำเร็จ', 'บันทึกการตั้งค่าเรียบร้อยแล้ว', 'success');
          loadSettings();
        } else {
          Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
        }
      } catch (err) {
        Swal.close();
        Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
      }
    };

    // Prevent default submit reload on question form
    const qFormEl = document.getElementById('questionForm');
    if (qFormEl) {
      qFormEl.onsubmit = (e) => {
        e.preventDefault();
        saveQuestion();
      };
    }

    function escapeHtml(text) {
      if (!text) return '';
      return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    function deleteExam(id) {
      event.stopPropagation();
      Swal.fire({
        title: 'ต้องการลบวิชาสอบนี้?',
        text: 'คำเตือน: การลบวิชาจะล้างประวัติการสอบ คำถาม และข้อมูลห้องสอบที่เกี่ยวข้องของวิชานี้ทั้งหมด!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ยืนยันลบวิชาสอบ',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('id', id);
          try {
            const response = await fetch('/api/teacher/exams/delete', { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success) {
              Swal.fire('สำเร็จ', 'ลบวิชาสอบเรียบร้อยแล้ว', 'success');
              await loadGlobalExams();
              if (isInWorkspace && activeExamId === id) {
                exitWorkspace();
              }
            } else {
              Swal.fire('ล้มเหลว', res.message || 'ลบไม่สำเร็จ', 'error');
            }
          } catch (e) {
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
          }
        }
      });
    }

    // --- Tab 3: Questions list ---
    async function loadQuestions() {
      try {
        const response = await fetch(`/api/teacher/questions?exam_id=${activeExamId}`);
        const res = await response.json();
        const choiceList = document.getElementById('choice-questions-list');
        const writingList = document.getElementById('writing-questions-list');

        if (!choiceList || !writingList) return;

        // Clear old map
        questionsMap = {};

        if (res.success && res.questions) {
          globalQuestionsList = res.questions;
          // Populate questionsMap (keep for backward compatibility if any other script relies on it)
          res.questions.forEach(q => {
            questionsMap[String(q.id)] = q;
          });

          const choiceQuestions = res.questions.filter(q => q.type === 'choice');
          const writingQuestions = res.questions.filter(q => q.type === 'writing');

          // Compute dashboard statistics
          let totalPoints = 0;
          let choicePoints = 0;
          let writingPoints = 0;

          res.questions.forEach(q => {
            const pts = parseFloat(q.points) || 0;
            totalPoints += pts;
            if (q.type === 'choice') {
              choicePoints += pts;
            } else {
              writingPoints += pts;
            }
          });

          // Update summary DOM elements
          const dashTotalPoints = document.getElementById('q-dash-total-points');
          const dashPointsBreakdown = document.getElementById('q-dash-points-breakdown');
          const dashTotalCount = document.getElementById('q-dash-total-count');
          const dashCountBreakdown = document.getElementById('q-dash-count-breakdown');
          const dashActiveSetting = document.getElementById('q-dash-active-setting');
          const dashActivePass = document.getElementById('q-dash-active-pass');

          if (dashTotalPoints) dashTotalPoints.textContent = `${totalPoints.toFixed(1)} คะแนน`;
          if (dashPointsBreakdown) dashPointsBreakdown.textContent = `ปรนัย: ${choicePoints.toFixed(1)} คะแนน | อัตนัย: ${writingPoints.toFixed(1)} คะแนน`;

          if (dashTotalCount) dashTotalCount.textContent = `${res.questions.length} ข้อ`;
          if (dashCountBreakdown) dashCountBreakdown.textContent = `ปรนัย: ${choiceQuestions.length} ข้อ | อัตนัย: ${writingQuestions.length} ข้อ`;

          // Find current exam config from globalExamsList
          const currentExam = globalExamsList.find(e => e.id === activeExamId);
          if (currentExam) {
            if (dashActiveSetting) dashActiveSetting.textContent = `สุ่มสอบ ${currentExam.num_questions} ข้อ`;
            if (dashActivePass) dashActivePass.textContent = `เกณฑ์ผ่าน ${currentExam.passing_percentage}% | เวลา ${currentExam.exam_duration || 'ไม่จำกัด'} นาที`;
          }

          // Render Choice Table
          if (choiceQuestions.length === 0) {
            choiceList.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-500">ไม่มีข้อสอบประเภทปรนัย (ตัวเลือก) ในวิชานี้</td></tr>`;
          } else {
            let html = '';
            choiceQuestions.forEach((q, index) => {
              let optionsHtml = '';
              if (q.choices) {
                const choices = JSON.parse(q.choices);
                optionsHtml = `<div class="mt-2 text-xs text-slate-400 space-y-1">`;
                choices.forEach((c) => {
                  const isCorrect = (c === q.correct_answer) ? 'text-emerald-400 font-extrabold' : 'text-slate-400';
                  optionsHtml += `<div class="${isCorrect}">- ${escapeHtml(c)}</div>`;
                });
                optionsHtml += `</div>`;
              }

              html += `
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4 text-white font-bold leading-relaxed max-w-xl">
                                    <div class="flex items-start gap-3">
                                        <span class="px-2 py-0.5 bg-slate-800 text-slate-400 rounded-lg text-[10px]">ข้อที่ ${index + 1}</span>
                                        <div>
                                            ${q.image_url ? `<img src="${q.image_url}" class="max-h-24 object-contain mb-2 block rounded" />` : ''}
                                            <span>${escapeHtml(q.question_text)}</span>
                                            ${optionsHtml}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-xs font-bold text-slate-350">${escapeHtml(q.correct_answer || '')}</td>
                                <td class="py-4 px-4">
                                    <input type="number" min="0" step="any" value="${q.points}"
                                        onchange="updateQuestionPoints('${q.id}', this.value)"
                                        class="w-16 bg-slate-800 border border-white/10 rounded-lg px-2 py-1 text-center text-white font-bold text-sm outline-none focus:border-pink-500 transition-colors" />
                                </td>
                                <td class="py-4 px-4 text-right space-x-2">
                                    <button onclick="editQuestion('${q.id}')" class="px-3 py-1 bg-sky-500/10 hover:bg-sky-500 text-sky-400 hover:text-white rounded-lg text-xs font-bold border border-sky-400/25 hover:border-transparent transition-all">
                                        แก้ไข ✏️
                                    </button>
                                    <button onclick="deleteQuestion('${q.id}')" class="px-3 py-1 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-lg text-xs font-bold border border-red-500/25 hover:border-transparent transition-all">
                                        ลบ 🗑️
                                    </button>
                                </td>
                            </tr>
                        `;
            });
            choiceList.innerHTML = html;
          }

          // Render Writing Table
          if (writingQuestions.length === 0) {
            writingList.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-500">ไม่มีข้อสอบประเภทอัตนัย (เขียนตอบ) ในวิชานี้</td></tr>`;
          } else {
            let html = '';
            writingQuestions.forEach((q, index) => {
              html += `
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4 text-white font-bold leading-relaxed max-w-xl">
                                    <div class="flex items-start gap-3">
                                        <span class="px-2 py-0.5 bg-slate-800 text-slate-400 rounded-lg text-[10px]">ข้อที่ ${index + 1}</span>
                                        <div>
                                            ${q.image_url ? `<img src="${q.image_url}" class="max-h-24 object-contain mb-2 block rounded" />` : ''}
                                            <span>${escapeHtml(q.question_text)}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-xs font-bold text-slate-350 max-w-sm leading-relaxed">${escapeHtml(q.correct_answer || '(ไม่มีเฉลยอ้างอิง)')}</td>
                                <td class="py-4 px-4">
                                    <input type="number" min="0" step="any" value="${q.points}"
                                        onchange="updateQuestionPoints('${q.id}', this.value)"
                                        class="w-16 bg-slate-800 border border-white/10 rounded-lg px-2 py-1 text-center text-white font-bold text-sm outline-none focus:border-pink-500 transition-colors" />
                                </td>
                                <td class="py-4 px-4 text-right space-x-2">
                                    <button onclick="editQuestion('${q.id}')" class="px-3 py-1 bg-sky-500/10 hover:bg-sky-500 text-sky-400 hover:text-white rounded-lg text-xs font-bold border border-sky-400/25 hover:border-transparent transition-all">
                                        แก้ไข ✏️
                                    </button>
                                    <button onclick="deleteQuestion('${q.id}')" class="px-3 py-1 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-lg text-xs font-bold border border-red-500/25 hover:border-transparent transition-all">
                                        ลบ 🗑️
                                    </button>
                                </td>
                            </tr>
                        `;
            });
            writingList.innerHTML = html;
          }
        }
      } catch (e) {
        console.error(e);
      }
    }

    function clearAllQuestions() {
      Swal.fire({
        title: 'ต้องการล้างคลังข้อสอบทั้งหมดของวิชานี้?',
        text: 'ข้อมูลคำถามทุกข้อภายใต้วิชานี้จะถูกลบถาวร!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ล้างข้อมูลทั้งหมด',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('exam_id', activeExamId);
          try {
            const response = await fetch('/api/teacher/questions/clear', { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success) {
              Swal.fire('สำเร็จ', 'ล้างคลังคำถามของวิชานี้แล้ว', 'success');
              loadQuestions();
            }
          } catch (e) {
            Swal.fire('ล้มเหลว', 'ล้มเหลว', 'error');
          }
        }
      });
    }

    function openQuestionModal(type = 'choice') {
      showQuestionModalSwal({
        id: '',
        exam_id: activeExamId,
        type: type,
        question_text: '',
        points: '1',
        correct_answer: '',
        image_url: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: ''
      }, '➕ เพิ่มคำถามข้อสอบ');
    }

    function editQuestion(id) {
      try {
        const q = globalQuestionsList.find(item => String(item.id) === String(id));
        if (!q) {
          alert('ไม่พบข้อมูลข้อสอบ ID: ' + id);
          return;
        }
        showQuestionModalSwal(q, '✏️ แก้ไขคำถามข้อสอบ');
      } catch (err) {
        console.error(err);
      }
    }

    function showQuestionModalSwal(q, title) {
      const html = `
        <div class="text-left space-y-4 max-h-[70vh] overflow-y-auto w-full pr-2">
          <input type="hidden" id="swal-q-id" value="${q.id || ''}">
          <input type="hidden" id="swal-q-exam-id" value="${q.exam_id || activeExamId}">
          
          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">ประเภทข้อสอบ</label>
            <select id="swal-q-type" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none">
              <option value="choice" ${q.type === 'choice' ? 'selected' : ''}>🔘 ปรนัย (เลือกตอบ)</option>
              <option value="writing" ${q.type === 'writing' ? 'selected' : ''}>📜 อัตนัย (ข้อเขียน)</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">โจทย์คำถาม</label>
            <textarea id="swal-q-question" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" rows="3" required>${escapeHtml(q.question_text || '')}</textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">อัปโหลดรูปภาพโจทย์ (ถ้ามี)</label>
            <input type="file" id="swal-q-file" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-600 file:text-white hover:file:bg-pink-700">
            <input type="hidden" id="swal-q-image-url" value="${q.image_url || ''}">
            <div id="swal-image-preview-container" class="mt-2 ${q.image_url ? '' : 'hidden'}">
              <img src="${q.image_url || ''}" id="swal-image-preview" class="max-w-[50%] h-auto max-h-64 object-contain rounded-lg border border-white/10">
              <button type="button" id="swal-btn-remove-image" class="text-xs text-red-400 hover:text-red-300 font-bold block mt-1">ลบรูปภาพ</button>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 mb-1">คะแนนของข้อนี้</label>
            <input type="number" min="0" step="any" id="swal-q-points" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none" required value="${(q.points !== undefined && q.points !== null && q.points !== '') ? q.points : 1}">
          </div>

          <!-- Choice options -->
          <div id="swal-choice-options-wrapper" class="space-y-3 ${q.type === 'choice' ? '' : 'hidden'}">
            <label class="block text-xs font-bold text-slate-400 mb-2">ตัวเลือก <span class="text-emerald-400">(คลิกวงกลมเพื่อเลือกข้อที่ถูกต้อง)</span></label>
            <div class="grid grid-cols-1 gap-3">
              ${['A', 'B', 'C', 'D'].map(letter => {
        const optVal = q[`option_${letter.toLowerCase()}`] || '';
        const isChecked = (q.correct_answer !== null && q.correct_answer !== undefined && String(q.correct_answer).trim() === String(optVal).trim() && optVal !== '') ||
          (q.correct_answer === letter);
        return `
                  <label class="flex items-center gap-3 p-3 bg-slate-800/60 border border-white/10 rounded-xl cursor-pointer hover:bg-slate-800 hover:border-white/20 transition-all group">
                    <input type="radio" name="swal_correct_choice" value="${letter}" ${isChecked ? 'checked' : ''} class="w-5 h-5 accent-emerald-500 cursor-pointer shrink-0">
                    <span class="text-xs font-black text-pink-400 shrink-0 w-6">${letter}.</span>
                    <input type="text" id="swal-q-opt-${letter.toLowerCase()}" value="${escapeHtml(optVal)}" class="flex-1 bg-transparent border-none text-white outline-none text-sm font-bold placeholder:text-slate-600" placeholder="กรอกตัวเลือก ${letter}">
                  </label>
                `;
      }).join('')}
            </div>
          </div>

          <!-- Writing options -->
          <div id="swal-writing-answer-wrapper" class="${q.type === 'writing' ? '' : 'hidden'}">
            <label class="block text-xs font-bold text-slate-400 mb-1">คำตอบอ้างอิงแนวทาง (สำหรับใช้ตรวจคีย์เวิร์ดเทียบความถูกต้อง)</label>
            <textarea id="swal-q-writing-answer" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white outline-none text-sm" rows="3" placeholder="กรอกเฉลยอ้างอิงสำหรับข้อเขียน">${escapeHtml(q.correct_answer || '')}</textarea>
          </div>
        </div>
      `;

      Swal.fire({
        title: title,
        html: html,
        width: '650px',
        background: '#0f172a',
        color: '#f8fafc',
        showCancelButton: true,
        confirmButtonText: 'บันทึกข้อสอบ 💾',
        cancelButtonText: 'ยกเลิก',
        customClass: {
          title: 'text-lg font-black text-white text-left border-b border-white/5 pb-2',
          confirmButton: 'bg-pink-500 hover:bg-pink-600 font-bold rounded-xl px-6 py-2 border-none',
          cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl px-4 py-2 border border-white/10',
          popup: 'border border-white/10 rounded-3xl'
        },
        didOpen: (popup) => {
          const typeSelect = popup.querySelector('#swal-q-type');
          const choiceWrapper = popup.querySelector('#swal-choice-options-wrapper');
          const writingWrapper = popup.querySelector('#swal-writing-answer-wrapper');

          typeSelect.addEventListener('change', (e) => {
            if (e.target.value === 'choice') {
              choiceWrapper.classList.remove('hidden');
              writingWrapper.classList.add('hidden');
            } else {
              choiceWrapper.classList.add('hidden');
              writingWrapper.classList.remove('hidden');
            }
          });

          const fileInput = popup.querySelector('#swal-q-file');
          fileInput.addEventListener('change', async (e) => {
            const input = e.target;
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];

            const reader = new FileReader();
            reader.onload = async function (ev) {
              const base64Data = ev.target.result;

              Swal.showLoading();

              const formData = new FormData();
              formData.append('image', base64Data);
              formData.append('fileName', file.name);

              try {
                const response = await fetch('/api/teacher/questions/upload-image', {
                  method: 'POST',
                  body: formData
                });
                const res = await response.json();

                if (res.success) {
                  popup.querySelector('#swal-q-image-url').value = res.url;
                  popup.querySelector('#swal-image-preview').src = res.url;
                  popup.querySelector('#swal-image-preview-container').classList.remove('hidden');
                  Swal.hideLoading();
                } else {
                  Swal.fire('ล้มเหลว', res.message || 'อัปโหลดไม่สำเร็จ', 'error');
                  input.value = '';
                }
              } catch (err) {
                Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                input.value = '';
              }
            };
            reader.readAsDataURL(file);
          });

          const removeImgBtn = popup.querySelector('#swal-btn-remove-image');
          removeImgBtn.addEventListener('click', async () => {
            const urlInput = popup.querySelector('#swal-q-image-url');
            if (urlInput.value) {
              const formData = new FormData();
              formData.append('url', urlInput.value);
              try {
                await fetch('/api/teacher/questions/delete-image', { method: 'POST', body: formData });
              } catch (e) {
                console.error("Failed to delete physical file", e);
              }
            }
            urlInput.value = '';
            popup.querySelector('#swal-image-preview').src = '';
            popup.querySelector('#swal-image-preview-container').classList.add('hidden');
            fileInput.value = '';
          });
        },
        preConfirm: () => {
          const popup = Swal.getHtmlContainer();
          const id = popup.querySelector('#swal-q-id').value;
          const examId = popup.querySelector('#swal-q-exam-id').value;
          const type = popup.querySelector('#swal-q-type').value;
          const question = popup.querySelector('#swal-q-question').value;
          const points = popup.querySelector('#swal-q-points').value;
          const imageUrl = popup.querySelector('#swal-q-image-url').value;

          if (points === '' || isNaN(parseFloat(points)) || parseFloat(points) < 0) {
            Swal.showValidationMessage('กรุณาระบุคะแนนของข้อนี้เป็นตัวเลขที่ถูกต้อง (เช่น 0.5 หรือ 1)');
            return false;
          }

          if (!question.trim()) {
            Swal.showValidationMessage('กรุณากรอกโจทย์คำถาม');
            return false;
          }

          let answer = '';
          let options = [];

          if (type === 'choice') {
            const selectedRadio = popup.querySelector('input[name="swal_correct_choice"]:checked');
            if (!selectedRadio) {
              Swal.showValidationMessage('กรุณาเลือกข้อที่ถูกต้อง');
              return false;
            }

            const optA = popup.querySelector('#swal-q-opt-a').value.trim();
            const optB = popup.querySelector('#swal-q-opt-b').value.trim();
            const optC = popup.querySelector('#swal-q-opt-c').value.trim();
            const optD = popup.querySelector('#swal-q-opt-d').value.trim();

            if (!optA || !optB || !optC || !optD) {
              Swal.showValidationMessage('กรุณากรอกตัวเลือกให้ครบทุกข้อ');
              return false;
            }

            options = [optA, optB, optC, optD];
            const letterMap = { 'A': optA, 'B': optB, 'C': optC, 'D': optD };
            answer = letterMap[selectedRadio.value];
          } else {
            answer = popup.querySelector('#swal-q-writing-answer').value;
          }

          return { id, examId, type, question, points, answer, imageUrl, options };
        }
      }).then(async (result) => {
        if (result.isConfirmed) {
          const data = result.value;
          const formData = new FormData();
          if (data.id) formData.append('id', data.id);
          formData.append('exam_id', data.examId);
          formData.append('type', data.type);
          formData.append('question', data.question);
          formData.append('points', data.points);
          formData.append('answer', data.answer);
          formData.append('image_url', data.imageUrl);
          if (data.type === 'choice') {
            formData.append('options', JSON.stringify(data.options));
          }

          Swal.fire({ title: 'กำลังบันทึกข้อสอบ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          try {
            const response = await fetch('/api/teacher/questions/save', {
              method: 'POST',
              body: formData
            });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', 'บันทึกคำถามเรียบร้อยแล้ว', 'success');
              loadQuestions();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'บันทึกไม่สำเร็จ', 'error');
            }
          } catch (e) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'ข้อผิดพลาดระบบ', 'error');
          }
        }
      });
    }

    function deleteQuestion(id) {
      Swal.fire({
        title: 'ต้องการลบคำถามข้อนี้?',
        text: 'การลบคำถามข้อนี้จะไม่สามารถกู้คืนได้!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ยืนยันลบ',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('id', id);
          try {
            const response = await fetch('/api/teacher/questions/delete', { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success) {
              Swal.fire('สำเร็จ', 'ลบข้อสอบเรียบร้อยแล้ว', 'success');
              loadQuestions();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'ลบไม่สำเร็จ', 'error');
            }
          } catch (e) {
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
          }
        }
      });
    }

    async function updateQuestionPoints(id, points) {
      const parsedPoints = parseFloat(points);
      if (isNaN(parsedPoints) || parsedPoints < 0) {
        Swal.fire('ข้อผิดพลาด', 'กรุณาระบุคะแนนเป็นตัวเลขที่ถูกต้อง (เช่น 0.5 หรือ 1)', 'warning');
        return;
      }
      const formData = new FormData();
      formData.append('id', id);
      formData.append('points', points);
      try {
        const response = await fetch('/api/teacher/questions/update-points', {
          method: 'POST',
          body: formData
        });
        const res = await response.json();
        if (!res.success) {
          Swal.fire('ล้มเหลว', res.message || 'อัปเดตคะแนนไม่สำเร็จ', 'error');
        } else {
          // Update in memory and refresh summary
          const targetQ = globalQuestionsList.find(q => String(q.id) === String(id));
          if (targetQ) {
            targetQ.points = parsedPoints;
          }
          if (typeof loadQuestions === 'function') {
            loadQuestions();
          }
        }
      } catch (e) {
        console.error(e);
      }
    }

    // --- Tab 4: Import Questions ---
    function openImportModal() {
      document.getElementById('import-data').value = '';
      document.getElementById('import-clear-existing').checked = false;
      document.getElementById('importModal').classList.remove('hidden');
    }

    function closeImportModal() {
      document.getElementById('importModal').classList.add('hidden');
    }

    async function submitImport() {
      const rawText = document.getElementById('import-data').value;
      const clearExisting = document.getElementById('import-clear-existing').checked;

      if (!rawText.trim()) {
        Swal.fire('คำเตือน', 'กรุณาวางข้อมูลดิบก่อนนำเข้า', 'warning');
        return;
      }

      const rows = rawText.split('\n').map(line => {
        let parts = [];
        if (line.includes('\t')) {
          parts = line.split('\t');
        } else if (line.includes(',')) {
          parts = line.split(',');
        } else {
          parts = [line];
        }
        return parts.map(col => col.trim());
      }).filter(r => r.length > 1 || (r.length === 1 && r[0] !== ''));
      Swal.fire({ title: 'กำลังนำเข้าข้อมูลคำถาม...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

      const formData = new FormData();
      formData.append('exam_id', activeExamId);
      formData.append('rows', JSON.stringify(rows));
      formData.append('clearExisting', clearExisting);

      try {
        const response = await fetch('/api/teacher/questions/import', {
          method: 'POST',
          body: formData
        });
        const res = await response.json();
        Swal.close();
        if (res.success) {
          Swal.fire('สำเร็จ', `นำเข้าข้อมูลเรียบร้อยแล้ว จำนวน ${res.count} ข้อ`, 'success');
          closeImportModal();
          loadQuestions();
        } else {
          Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
        }
      } catch (err) {
        Swal.close();
        Swal.fire('ล้มเหลว', 'การเชื่อมต่อผิดพลาด', 'error');
      }
    }

    // --- Tab 5: Lobby Monitor ---
    async function loadMonitor() {
      try {
        const response = await fetch(`/api/teacher/monitor?exam_id=${activeExamId}`);
        const res = await response.json();
        const container = document.getElementById('monitor-list');
        const countEl = document.getElementById('active-players-count');
        const badgeEl = document.getElementById('monitor-status-badge');


        // API ใช้ camelCase: joinPolicy / examMode / examStatus
        // ต้องใช้ค่าจากฐานข้อมูลทุกครั้ง เพื่อให้รีเฟรชแล้วยังแสดงค่าที่เลือกอยู่
        const currentJoinPolicy = res.joinPolicy || res.join_policy || 'anytime';
        const currentExamMode = res.examMode || res.exam_mode || 'classic';
        const currentExamStatus = res.examStatus || res.exam_status || 'Waiting';

        updateJoinPolicyUI(currentJoinPolicy);
        updateExamModeUI(currentExamMode);

        if (badgeEl && currentExamStatus) {
          if (currentExamStatus === 'Waiting') {
            badgeEl.className = "px-3.5 py-1.5 text-xs font-black rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-sm";
            badgeEl.textContent = "⏳ ห้องรอสอบ (พักคอย)";
          } else if (currentExamStatus === 'Started') {
            badgeEl.className = "px-3.5 py-1.5 text-xs font-black rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 animate-pulse shadow-sm";
            badgeEl.textContent = "🚀 กำลังสอบอยู่ (Exam Started)";
          } else if (currentExamStatus === 'Finished') {
            badgeEl.className = "px-3.5 py-1.5 text-xs font-black rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 shadow-sm";
            badgeEl.textContent = "🛑 ปิดระบบสอบแล้ว (Finished)";
          }
        }

        // Dynamically highlight active status button and dim others
        const btnStarted = document.querySelector("button[onclick*='Started']");
        const btnFinished = document.querySelector("button[onclick*='Finished']");
        const btnWaiting = document.querySelector("button[onclick*='Waiting']");

        if (btnStarted && btnFinished && btnWaiting) {
          // Reset classes
          btnStarted.className = "w-full py-4 px-6 rounded-2xl text-sm font-black transition-all cursor-pointer flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98]";
          btnFinished.className = "w-full py-4 px-6 rounded-2xl text-sm font-black transition-all cursor-pointer flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98]";
          btnWaiting.className = "w-full py-4 px-6 rounded-2xl text-sm font-black transition-all cursor-pointer flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-[0.98]";
          btnStarted.classList.remove('monitor-choice-active');
          btnFinished.classList.remove('monitor-choice-active');
          btnWaiting.classList.remove('monitor-choice-active');

          if (currentExamStatus === 'Started') {
            btnStarted.classList.add("bg-gradient-to-r", "from-emerald-500", "to-teal-500", "text-white", "shadow-lg", "shadow-emerald-500/30", "monitor-choice-active");
            btnFinished.classList.add("bg-white/5", "text-red-450/40", "border", "border-white/5", "opacity-40", "hover:opacity-90");
            btnWaiting.classList.add("bg-white/5", "text-amber-450/40", "border", "border-white/5", "opacity-40", "hover:opacity-90");
          } else if (currentExamStatus === 'Finished') {
            btnFinished.classList.add("bg-gradient-to-r", "from-red-500", "to-rose-600", "text-white", "shadow-lg", "shadow-red-500/25", "monitor-choice-active");
            btnStarted.classList.add("bg-white/5", "text-emerald-450/40", "border", "border-white/5", "opacity-40", "hover:opacity-90");
            btnWaiting.classList.add("bg-white/5", "text-amber-450/40", "border", "border-white/5", "opacity-40", "hover:opacity-90");
          } else { // Waiting
            btnWaiting.classList.add("bg-gradient-to-r", "from-amber-500", "to-orange-500", "text-white", "shadow-lg", "shadow-amber-500/30", "monitor-choice-active");
            btnStarted.classList.add("bg-white/5", "text-emerald-450/40", "border", "border-white/5", "opacity-40", "hover:opacity-90");
            btnFinished.classList.add("bg-white/5", "text-red-450/40", "border", "border-white/5", "opacity-40", "hover:opacity-90");
          }
        }

        if (res.success && res.students) {
          countEl.textContent = `${res.students.length} คนกำลังรอในห้อง`;
          if (res.students.length === 0) {
            container.innerHTML = `<tr><td colspan="5" class="py-12 text-center text-slate-500 font-bold">ไม่มีนักเรียนในหน้าพักคอยขณะนี้</td></tr>`;
            return;
          }

          let html = '';
          res.students.forEach(s => {
            const initials = s.name.substring(0, 2);
            html += `
                        <tr class="hover:bg-white/5 transition-all duration-300">
                            <!-- ชื่อ-นามสกุล -->
                            <td class="py-4 px-4 font-bold text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-pink-500/20 to-sky-500/20 border border-white/10 flex items-center justify-center text-xs font-black text-slate-200">
                                        ${initials}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-white">${s.name}</span>
                                        <span class="text-[10px] text-pink-400 font-extrabold font-mono mt-0.5">ID: ${s.student_code || '-'}</span>
                                    </div>
                                </div>
                            </td>
                            <!-- เลขที่/ห้อง -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20 text-xs font-extrabold">ม.${s.room}</span>
                                    <span class="px-2.5 py-1 rounded-xl bg-slate-800 text-slate-350 border border-white/5 text-xs font-extrabold">เลขที่ ${s.student_number}</span>
                                </div>
                            </td>
                            <!-- อีเมล -->
                            <td class="py-4 px-4 text-xs font-mono text-slate-400 font-semibold">${s.email}</td>
                            <!-- สถานะ -->
                            <td class="py-4 px-4 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-sm shadow-emerald-500"></span>
                                    <span class="text-[11px] text-emerald-450 font-bold tracking-wider">ออนไลน์</span>
                                </div>
                            </td>
                            <!-- ดำเนินการ -->
                            <td class="py-4 px-4 text-right">
                                <button onclick="deleteStudent('${s.email}')" class="px-3.5 py-1.5 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-xl text-xs font-bold border border-red-500/20 hover:border-transparent transition-all duration-200 flex items-center gap-1 ml-auto cursor-pointer">
                                    <span>เอาออก</span> 🗑️
                                </button>
                            </td>
                        </tr>
                    `;
          });
          container.innerHTML = html;
        }
      } catch (e) {
        console.error("Monitor error", e);
      }
    }

    async function changeJoinPolicy(policy) {
      const formData = new FormData();
      formData.append('id', activeExamId);
      formData.append('join_policy', policy);

      try {
        const response = await fetch('/api/teacher/exams/update-policy', {
          method: 'POST',
          body: formData
        });
        const res = await response.json();
        if (res.success) {
          updateJoinPolicyUI(policy);
          Swal.fire({
            icon: 'success',
            title: 'เปลี่ยนนโยบายการเข้าสอบสำเร็จ',
            text: policy === 'anytime' ? 'นักเรียนสามารถกดเข้าสอบได้โดยตรงตลอดเวลา' : 'นักเรียนต้องสแตนด์บายในห้องพักคอยก่อนสอบเท่านั้น',
            timer: 2000,
            showConfirmButton: false
          });
        } else {
          Swal.fire('ล้มเหลว', res.message || 'บันทึกไม่สำเร็จ', 'error');
        }
      } catch (e) {
        console.error(e);
        Swal.fire('ล้มเหลว', 'การเชื่อมต่อผิดพลาด', 'error');
      }
    }

    function updateJoinPolicyUI(policy) {
      const btnAnytime = document.getElementById('btn-policy-anytime');
      const btnLobby = document.getElementById('btn-policy-lobby');
      if (!btnAnytime || !btnLobby) return;

      if (policy === 'lobby_first') {
        btnLobby.className = "w-full py-3.5 px-5 bg-gradient-to-r from-pink-500 to-rose-500 text-white font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer border border-pink-500/20 shadow-lg shadow-pink-500/10 scale-[1.01] monitor-choice-active";
        btnAnytime.className = "w-full py-3.5 px-5 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
      } else {
        btnAnytime.className = "w-full py-3.5 px-5 bg-gradient-to-r from-sky-500 to-blue-500 text-white font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer border border-sky-500/20 shadow-lg shadow-sky-500/10 scale-[1.01] monitor-choice-active";
        btnLobby.className = "w-full py-3.5 px-5 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
      }
    }

    async function changeExamModeDirect(mode) {
      const formData = new FormData();
      formData.append('id', activeExamId);
      formData.append('exam_mode', mode);

      try {
        const response = await fetch('/api/teacher/exams/update-mode', {
          method: 'POST',
          body: formData
        });
        const res = await response.json();
        if (res.success) {
          updateExamModeUI(mode);
          Swal.fire({
            icon: 'success',
            title: 'เปลี่ยนรูปแบบการสอบสำเร็จ!',
            text: mode === 'pokemon'
              ? 'ปรับเป็นโหมดเดินเล่นเกม Pokémon RPG Adventure แล้ว'
              : (mode === 'escape_room'
                ? 'ปรับเป็นโหมด Coin Quest กระโดดเก็บเหรียญและตอบคำถามแล้ว'
                : 'ปรับเป็นโหมดข้อสอบมาตรฐาน (Classic) แล้ว'),
            timer: 2000,
            showConfirmButton: false
          });
        } else {
          Swal.fire('ล้มเหลว', res.message || 'บันทึกไม่สำเร็จ', 'error');
        }
      } catch (e) {
        console.error(e);
        Swal.fire('ล้มเหลว', 'การเชื่อมต่อผิดพลาด', 'error');
      }
    }

    function updateExamModeUI(mode) {
      const btnClassic = document.getElementById('btn-mode-classic');
      const btnPokemon = document.getElementById('btn-mode-pokemon');
      const btnEscape = document.getElementById('btn-mode-escape');
      const badge = document.getElementById('monitor-mode-badge');

      if (badge) {
        if (mode === 'escape_room') {
          badge.className = "px-3 py-1 text-xs font-black rounded-xl bg-violet-500/20 text-violet-300 border border-violet-500/40 shadow-sm";
          badge.innerHTML = "🪙 Coin Quest";
        } else if (mode === 'pokemon') {
          badge.className = "px-3 py-1 text-xs font-black rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/40 shadow-sm";
          badge.innerHTML = "⚡ Pokémon RPG";
        } else {
          badge.className = "px-3 py-1 text-xs font-black rounded-xl bg-sky-500/20 text-sky-300 border border-sky-500/40 shadow-sm";
          badge.innerHTML = "📝 แบบปกติ";
        }
      }

      if (!btnClassic || !btnPokemon) return;

      if (btnEscape) {
        btnEscape.className = mode === 'escape_room'
          ? "w-full py-3.5 px-4 bg-gradient-to-r from-violet-600 to-indigo-700 text-white font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer border border-violet-400/40 shadow-lg shadow-violet-500/20 scale-[1.01] monitor-choice-active"
          : "w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
      }
      if (mode === 'escape_room') {
        btnClassic.className = "w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
        btnPokemon.className = "w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
        return;
      }

      if (mode === 'pokemon') {
        btnPokemon.className = "w-full py-3.5 px-4 bg-gradient-to-r from-amber-500/30 via-red-500/25 to-purple-500/30 text-amber-200 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer border border-amber-500/50 shadow-lg shadow-amber-500/20 scale-[1.01] monitor-choice-active";
        btnClassic.className = "w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
      } else {
        btnClassic.className = "w-full py-3.5 px-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer border border-sky-500/30 shadow-lg shadow-sky-500/20 scale-[1.01] monitor-choice-active";
        btnPokemon.className = "w-full py-3.5 px-4 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-350 font-bold rounded-2xl text-xs transition-all text-left flex flex-col gap-1 cursor-pointer opacity-50 hover:opacity-90";
      }
    }

    async function deleteStudent(email) {
      Swal.fire({
        title: 'เอาผู้เรียนออกจากคิว?',
        text: 'การกระทำนี้จะลบผู้ใช้ออกจากห้องสอบรายวิชานี้ชั่วคราว',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'เอาออก',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('email', email);
          formData.append('exam_id', activeExamId);
          try {
            const response = await fetch('/api/teacher/monitor/delete', { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success) {
              Swal.fire('สำเร็จ', 'นำนักเรียนออกจากห้องพักคอยแล้ว', 'success');
              loadMonitor();
            }
          } catch (e) {
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาด', 'error');
          }
        }
      });
    }

    async function changeExamStatusDirect(status) {
      let statusText = 'เริ่มการสอบ';
      let statusDesc = 'นักเรียนในคิวทุกคนจะเริ่มการนับถอยหลังและเข้าทำข้อสอบทันที!';
      let confirmBtn = 'เริ่มสอบเลย 🚀';

      if (status === 'Finished') {
        statusText = 'ปิดระบบสอบ';
        statusDesc = 'นักเรียนจะไม่สามารถกดส่งคำตอบเพิ่มหรือเข้าระบบสอบได้อีก!';
        confirmBtn = 'ปิดการสอบ 🛑';
      } else if (status === 'Waiting') {
        statusText = 'เปิดห้องพักคอย/รีเซ็ต';
        statusDesc = 'ระบบจะล้างรายชื่อนักเรียนที่กำลังรออยู่ในล็อบบี้ทั้งหมดเพื่อให้เข้ามาใหม่!';
        confirmBtn = 'ยืนยัน ⏳';
      }

      Swal.fire({
        title: `ยืนยันต้องการ "${statusText}"?`,
        text: statusDesc,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: status === 'Finished' ? '#ef4444' : '#10b981',
        confirmButtonText: confirmBtn,
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('id', activeExamId);
          formData.append('status', status);

          Swal.fire({ title: 'กำลังปรับปรุงสถานะ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          try {
            const response = await fetch('/api/teacher/exams/update-status', {
              method: 'POST',
              body: formData
            });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', `อัปเดตสถานะเป็น "${statusText}" เรียบร้อยแล้ว`, 'success');
              loadMonitor();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
            }
          } catch (e) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
          }
        }
      });
    }

    async function cancelExamDirect() {
      Swal.fire({
        title: '⚠️ ยืนยันต้องการยกเลิกการสอบทันที?',
        text: 'การกระทำนี้จะเปลี่ยนสถานะการสอบกลับเป็น "พักคอย (Waiting)" เพื่อเตะผู้เรียนทุกคนที่กำลังอยู่ในห้องสอบและห้องพักคอยออกจากระบบสอบวิชานี้ทันที!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ใช่, ยกเลิกและเตะทุกคนออก 🚨',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('id', activeExamId);
          formData.append('status', 'Waiting');

          Swal.fire({ title: 'กำลังดำเนินการยกเลิกการสอบ...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          try {
            const response = await fetch('/api/teacher/exams/update-status', {
              method: 'POST',
              body: formData
            });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', 'ยกเลิกการสอบวิชานี้และคัดผู้เรียนออกจากห้องสอบทั้งหมดแล้ว', 'success');
              loadMonitor();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
            }
          } catch (e) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
          }
        }
      });
    }

    // --- Tab 6: Results List & AI Grading ---
    var resultsData = [];
    async function loadResults() {
      const exam = globalExamsList.find(e => e.id === activeExamId);
      if (exam) {
        const h2 = document.querySelector('#section-results h2');
        if (h2) {
          h2.innerHTML = `📊 ผลการสอบและตรวจคำตอบอัตนัย <span class="text-xs font-normal text-slate-400 block sm:inline sm:ml-2">ปีการศึกษา ${exam.academic_year || '-'} ภาคเรียนที่ ${exam.semester || '-'}</span>`;
        }
      }
      try {
        const response = await fetch(`/api/teacher/results?exam_id=${activeExamId}`);
        const res = await response.json();

        if (res.success && res.results) {
          // Sort results by Room (ASC) and then Student Number (ASC)
          res.results.sort((a, b) => {
            // Compare Rooms (handle rooms like '1/1' or simple numbers)
            const roomA = String(a.room || '');
            const roomB = String(b.room || '');
            const roomCompare = roomA.localeCompare(roomB, undefined, { numeric: true, sensitivity: 'base' });

            if (roomCompare !== 0) {
              return roomCompare;
            }

            // If rooms are same, compare numbers
            const numA = parseInt(a.student_number) || 0;
            const numB = parseInt(b.student_number) || 0;
            return numA - numB;
          });

          resultsData = res.results;
          updateRoomFilterOptions(res.results);
          updateRoundFilterOptions(res.results);
          renderResultsTable();
        }
      } catch (e) {
        console.error(e);
      }
    }

    function updateRoomFilterOptions(results) {
      const filterSelect = document.getElementById('results-filter-room');
      if (!filterSelect) return;
      const currentVal = filterSelect.value;

      // Find unique rooms
      const rooms = [];
      results.forEach(r => {
        const room = String(r.room || '').trim();
        if (room && !rooms.includes(room)) {
          rooms.push(room);
        }
      });

      // Sort rooms naturally (e.g. 1/1, 1/2, 1/10)
      rooms.sort((a, b) => a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' }));

      // Clear and rebuild options
      filterSelect.innerHTML = '<option value="ALL">แสดงทุกห้อง</option>';
      rooms.forEach(room => {
        const opt = document.createElement('option');
        opt.value = room;
        opt.textContent = `ห้อง ม.${room}`;
        filterSelect.appendChild(opt);
      });

      // Restore value if it still exists
      const optionsArray = Array.from(filterSelect.options).map(o => o.value);
      if (optionsArray.includes(currentVal)) {
        filterSelect.value = currentVal;
      } else {
        filterSelect.value = 'ALL';
      }
    }

    function updateRoundFilterOptions(results) {
      const filterSelect = document.getElementById('results-filter-round');
      if (!filterSelect) return;
      const currentVal = filterSelect.value;

      // Find unique rounds
      const rounds = ['ALL'];
      results.forEach(r => {
        const rnd = r.exam_round || '1';
        if (!rounds.includes(rnd)) {
          rounds.push(rnd);
        }
      });

      // Clear and rebuild options
      filterSelect.innerHTML = '';
      rounds.forEach(rnd => {
        const opt = document.createElement('option');
        opt.value = rnd;
        opt.textContent = rnd === 'ALL' ? 'แสดงทุกรอบ' : `รอบที่ ${rnd}`;
        filterSelect.appendChild(opt);
      });

      // Restore value if still exists
      if (rounds.includes(currentVal)) {
        filterSelect.value = currentVal;
      } else {
        filterSelect.value = 'ALL';
      }
    }

    function renderResultsTable() {
      try {
        const roundFilterSelect = document.getElementById('results-filter-round');
        const selectedRound = roundFilterSelect ? roundFilterSelect.value : 'ALL';

        const roomFilterSelect = document.getElementById('results-filter-room');
        const selectedRoom = roomFilterSelect ? roomFilterSelect.value : 'ALL';

        const container = document.getElementById('results-list');
        if (!container) return;

        const exam = globalExamsList.find(e => e.id === activeExamId);
        const maxAttempts = exam ? parseInt(exam.max_attempts) || 1 : 1;

        const filtered = resultsData.filter(r => {
          const matchRound = selectedRound === 'ALL' || (r.exam_round || '1') === selectedRound;
          const matchRoom = selectedRoom === 'ALL' || String(r.room || '').trim() === selectedRoom;
          return matchRound && matchRoom;
        });

        if (filtered.length === 0) {
          container.innerHTML = `<tr><td colspan="9" class="py-8 text-center text-slate-500">ไม่มีผู้สอบส่งคำตอบในเงื่อนไขที่เลือก</td></tr>`;
          return;
        }

        let html = '';
        filtered.forEach(r => {
          const answers = JSON.parse(r.answers_json || '[]');
          let needsGrading = false;
          let choiceScore = 0;
          let choiceTotal = 0;
          let writingScore = 0;
          let writingTotal = 0;

          answers.forEach(a => {
            const pts = parseFloat(a.points) || 0;
            if (a.type === 'choice') {
              choiceTotal += pts;
              if (a.isCorrect === 'ถูกต้อง' || a.isCorrect === true) {
                choiceScore += pts;
              }
            } else if (a.type === 'writing') {
              writingTotal += pts;
              if (a.isCorrect === 'รอตรวจ') {
                needsGrading = true;
              } else {
                writingScore += parseFloat(a.isCorrect) || 0;
              }
            }
          });

          const gradingBadge = needsGrading ?
            '<span class="px-2 py-0.5 text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded font-bold ml-2">รอตรวจ</span>' :
            '<span class="px-2 py-0.5 text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded font-bold ml-2">ครบแล้ว</span>';

          let cheatingStatus = '<span class="text-emerald-400 font-bold">ปกติ</span>';
          if (parseInt(r.cheating_count) >= 3 || r.cheating_flag === 'YES') {
            cheatingStatus = '<span class="text-red-400 font-black">สุ่มเสี่ยง / ระงับ</span>';
          }

          let writingColContent = '-';
          if (writingTotal > 0) {
            const btnClass = needsGrading ?
              'bg-amber-500 hover:bg-amber-600 text-slate-900 animate-pulse font-extrabold shadow-md shadow-amber-500/20' :
              'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/25';
            writingColContent = `
            <div class="flex flex-col items-center">
              <span class="font-bold text-amber-400">${writingScore} / ${writingTotal}</span>
              <button onclick="openGradingModal('${r.id}')" class="mt-1 px-2.5 py-1 ${btnClass} rounded-lg text-[10px] transition-all flex items-center gap-1 cursor-pointer">
                ตรวจอัตนัย ✍️
              </button>
            </div>
          `;
          }

          html += `
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4 font-bold text-white">
                        ${r.name || ''}
                        <span class="block text-[10px] text-pink-400 font-extrabold font-mono mt-0.5">ID: ${r.student_code || '-'}</span>
                        <span class="block text-xs text-slate-500 font-mono">${r.email || ''}</span>
                    </td>
                    <td class="py-4 px-4 text-xs font-bold text-slate-350">
                        ม.${r.room || ''} เลขที่ ${r.student_number || ''}
                    </td>
                    <td class="py-4 px-4 font-bold text-pink-400">
                        ${r.score} / ${choiceTotal + writingTotal}  ${gradingBadge}
                    </td>
                    <td class="py-4 px-4 font-bold text-sky-400">
                        <div class="flex flex-col items-center">
                          <span>${choiceScore} / ${choiceTotal} </span>
                          <button onclick="viewAnswersModal('${r.id}')" class="mt-1 px-2.5 py-1 bg-sky-500/10 hover:bg-sky-500 text-sky-400 hover:text-white rounded-lg text-[10px] border border-sky-500/25 hover:border-transparent transition-all flex items-center gap-1 cursor-pointer">
                            ดูคำตอบ 👁️
                          </button>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        ${writingColContent}
                    </td>
                    <td class="py-4 px-4 text-sky-400 font-bold">ครั้งที่ ${r.attempt_number || '1'} (รอบ ${r.exam_round || '1'})</td>
                    <td class="py-4 px-4 text-slate-400 font-bold">${r.total_time_spent} วินาที</td>
                    <td class="py-4 px-4 text-xs font-bold">
                        ${cheatingStatus} (${r.cheating_count} ครั้ง)
                    </td>
                    <td class="py-4 px-4 text-right space-x-1.5">
                        <button onclick="editScoreDirect('${r.id}')" class="px-3 py-1 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 rounded-lg text-xs font-bold border border-sky-400/25 transition-all">
                            แก้ไขข้อมูล ✏️
                        </button>
                        <button onclick="deleteResult('${r.id}')" class="px-3 py-1 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg text-xs font-bold border border-red-500/25 transition-all">
                            ลบ 🗑️
                        </button>
                    </td>
                </tr>
            `;
        });
        container.innerHTML = html;
      } catch (err) {
        console.error('renderResultsTable error:', err);
      }
    }


    function printExamResults() {
      const exam = globalExamsList.find(e => e.id === activeExamId);
      if (!exam) {
        Swal.fire('ข้อผิดพลาด', 'ไม่พบรายละเอียดวิชาสอบ', 'error');
        return;
      }

      const filterSelect = document.getElementById('results-filter-round');
      const selectedRound = filterSelect ? filterSelect.value : 'ALL';

      const filtered = resultsData.filter(r => {
        if (selectedRound === 'ALL') return true;
        return (r.exam_round || '1') === selectedRound;
      });

      if (filtered.length === 0) {
        Swal.fire('ข้อผิดพลาด', 'ไม่มีข้อมูลผลการสอบที่จะพิมพ์สำหรับรอบนี้', 'warning');
        return;
      }

      const printWindow = window.open('', '_blank');

      let globalMaxChoice = 0;
      let globalMaxWriting = 0;

      filtered.forEach(r => {
        let cPts = 0;
        let wPts = 0;
        try {
          const answers = JSON.parse(r.answers_json || '[]');
          answers.forEach(a => {
            const pts = parseFloat(a.points || 0);
            if (a.type === 'choice') cPts += pts;
            if (a.type === 'writing') wPts += pts;
          });
        } catch (e) { }
        if (cPts > globalMaxChoice) globalMaxChoice = cPts;
        if (wPts > globalMaxWriting) globalMaxWriting = wPts;
      });

      const choiceMaxText = globalMaxChoice > 0 ? ` (เต็ม ${globalMaxChoice})` : '';
      const writingMaxText = globalMaxWriting > 0 ? ` (เต็ม ${globalMaxWriting})` : '';

      let htmlContent = `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <title>ประกาศผลคะแนนสอบ - ${exam.subject_code} ${exam.subject_name}</title>
        <style>
          @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700;800&display=swap');
          body {
            font-family: 'Sarabun', sans-serif;
            background: #fff;
            color: #000;
            margin: 2cm;
            line-height: 1.6;
          }
          .header {
            text-align: center;
            margin-bottom: 25px;
          }
          .header h1 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 5px 0;
          }
          .header h2 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 5px 0;
          }
          .header p {
            font-size: 13px;
            margin: 0;
          }
          .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 14px;
            border-collapse: collapse;
          }
          .meta-table td {
            padding: 4px 0;
          }
          .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 30px;
          }
          .results-table th, .results-table td {
            border: 1px solid #000;
            padding: 4px 4px;
            text-align: center;
            word-wrap: break-word;
          }
          .results-table th {
            background-color: #f2f2f2;
            font-weight: 700;
          }
          .results-table td.name {
            text-align: left;
            white-space: nowrap;
          }
          .passed {
            font-weight: 600;
            color: #10b981;
          }
          .failed {
            color: #ef4444;
          }
          .footer {
            margin-top: 50px;
            float: right;
            width: 300px;
            text-align: center;
            font-size: 14px;
          }
          @media print {
            @page {
              size: A4 portrait;
              margin: 1cm;
            }
            body {
              margin: 0;
            }
            .no-print {
              display: none;
            }
            .passed {
              color: #000 !important;
              font-weight: 800;
            }
            .failed {
              color: #000 !important;
            }
          }
        </style>
      </head>
      <body>
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
          <button onclick="window.print();" style="padding: 10px 20px; background-color: #0284c7; color: white; border: none; font-weight: bold; border-radius: 8px; cursor: pointer; font-family: inherit; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">🖨️ สั่งพิมพ์ใบประกาศผลสอบ (Print)</button>
        </div>

        <div class="header">
          <h1>ประกาศผลคะแนนการสอบออนไลน์</h1>
          <h2>รายวิชา ${exam.subject_code} ${exam.subject_name}</h2>
          <p>กลุ่มสาระการเรียนรู้ ${exam.learning_area} | ประเภทการสอบ: ${exam.exam_type}</p>
        </div>

        <table class="meta-table">
          <tr>
            <td style="width: 50%"><strong>ปีการศึกษา:</strong> ${exam.academic_year || '-'} <strong>ภาคเรียนที่:</strong> ${exam.semester || '-'}</td>
            <td style="width: 50%; text-align: right;"><strong>ผู้สอน:</strong> ${exam.teacher_name}</td>
          </tr>
          <tr>
            <td style="width: 50%"><strong>รอบการสอบ:</strong> ${selectedRound === 'ALL' ? 'ทุกรอบการสอบ' : 'รอบที่ ' + selectedRound}</td>
            <td style="width: 50%; text-align: right;"><strong>สิทธิ์สอบสูงสุด:</strong> ${exam.max_attempts || '1'} ครั้ง</td>
          </tr>
          <tr>
            <td style="width: 50%"><strong>เกณฑ์ผ่าน:</strong> ${exam.passing_percentage}% (${(globalMaxChoice + globalMaxWriting > 0 ? (globalMaxChoice + globalMaxWriting) : exam.num_questions) * (exam.passing_percentage || 50) / 100} / ${globalMaxChoice + globalMaxWriting > 0 ? (globalMaxChoice + globalMaxWriting) : exam.num_questions} คะแนน)</td>
            <td style="width: 50%; text-align: right;"></td>
          </tr>
        </table>

        <table class="results-table">
          <thead>
            <tr>
              <th style="width: 6%">ลำดับ</th>
              <th style="width: 12%">เลขประจำตัว</th>
              <th>ชื่อ - นามสกุล</th>
              <th style="width: 14%">ห้อง / เลขที่</th>
              <th style="width: 12%">คะแนนปรนัย${choiceMaxText}</th>
              <th style="width: 12%">คะแนนอัตนัย${writingMaxText}</th>
              <th style="width: 12%">คะแนนรวม (เต็ม ${exam.num_questions})</th>
              <th style="width: 14%">ผลการประเมิน</th>
            </tr>
          </thead>
          <tbody>
      `;

      filtered.forEach((r, index) => {
        let choiceScore = 0;
        let writingScore = 0;
        let totalMaxPoints = 0;

        try {
          const answers = JSON.parse(r.answers_json || '[]');
          if (Array.isArray(answers)) {
            answers.forEach(ans => {
              totalMaxPoints += parseFloat(ans.points || 0);
              if (ans.type === 'choice') {
                if (ans.isCorrect === 'ถูกต้อง') {
                  choiceScore += parseFloat(ans.points || 0);
                }
              } else if (ans.type === 'writing') {
                if (ans.isCorrect !== 'รอตรวจ') {
                  writingScore += parseFloat(ans.isCorrect || 0);
                }
              }
            });
          }
        } catch (e) {
          console.error("Error parsing answers_json:", e);
        }

        const fullScore = totalMaxPoints > 0 ? totalMaxPoints : parseInt(exam.num_questions || 0);
        const passPercent = parseFloat(exam.passing_percentage) || 50;
        const passScore = (fullScore * passPercent) / 100;
        const studentScore = parseFloat(r.score) || 0;
        const isPassed = studentScore >= passScore;
        const evaluation = isPassed ? '<span class="passed">ผ่าน (Pass)</span>' : '<span class="failed">ไม่ผ่าน (Fail)</span>';

        htmlContent += `
            <tr>
              <td>${index + 1}</td>
              <td>${r.student_code || '-'}</td>
              <td class="name">${r.name}</td>
              <td>ม.${r.room || ''} | เลขที่ ${r.student_number || ''}</td>
              <td>${choiceScore}</td>
              <td>${writingScore}</td>
              <td><strong>${r.score}</strong></td>
              <td>${evaluation}</td>
            </tr>
        `;
      });

      htmlContent += `
          </tbody>
        </table>

        <div class="footer">
          <p>ลงชื่อ..........................................................ผู้สอน</p>
          <p style="margin-top: 10px;">( ${exam.teacher_name} )</p>
          <p style="margin-top: 5px;">วันที่พิมพ์ประกาศ: ${new Date().toLocaleDateString('th-TH')}</p>
        </div>
      </body>
      </html>
      `;

      printWindow.document.write(htmlContent);
      printWindow.document.close();
    }

    function exportResultsCSV() {
      const exam = globalExamsList.find(e => e.id === activeExamId);
      if (!exam) {
        Swal.fire('ข้อผิดพลาด', 'ไม่พบรายละเอียดวิชาสอบ', 'error');
        return;
      }
      const roomFilter = document.getElementById('results-filter-room') ? document.getElementById('results-filter-room').value : 'ALL';
      const roundFilter = document.getElementById('results-filter-round') ? document.getElementById('results-filter-round').value : 'ALL';

      const filtered = resultsData.filter(r => {
        if (roomFilter !== 'ALL' && String(r.room || '').trim() !== String(roomFilter).trim()) return false;
        if (roundFilter !== 'ALL' && String(r.exam_round || '1').trim() !== String(roundFilter).trim()) return false;
        return true;
      });

      if (filtered.length === 0) {
        Swal.fire('ไม่มีข้อมูล', 'ไม่มีข้อมูลผลการสอบที่จะส่งออกตามเงื่อนไขที่เลือก', 'warning');
        return;
      }

      // Sort by room then student_number
      filtered.sort((a, b) => {
        const roomA = String(a.room || '');
        const roomB = String(b.room || '');
        if (roomA !== roomB) return roomA.localeCompare(roomB, 'th', { numeric: true });
        const numA = parseInt(a.student_number || 0) || 0;
        const numB = parseInt(b.student_number || 0) || 0;
        return numA - numB;
      });

      // UTF-8 BOM for Thai language display in Microsoft Excel
      let csv = '\uFEFF';
      csv += 'ลำดับ,เลขประจำตัว,ชื่อ-นามสกุล,ห้อง,เลขที่,คะแนนรวม,คะแนนปรนัย,คะแนนอัตนัย,ผลการประเมิน,รอบสอบ,เวลาที่ใช้(วินาที),สลับหน้าจอ(ครั้ง),สถานะทุจริต,เวลาที่ส่งข้อสอบ\n';

      const clean = (v) => `"${String(v ?? '').replace(/"/g, '""')}"`;

      filtered.forEach((r, idx) => {
        let cScore = 0;
        let wScore = 0;
        try {
          const ansList = JSON.parse(r.answers_json || '[]');
          ansList.forEach(a => {
            if (a.type === 'choice') {
              if (a.isCorrect === true || a.isCorrect === 'ถูกต้อง') {
                cScore += parseFloat(a.points || 1);
              }
            } else if (a.type === 'writing') {
              if (isFinite(a.isCorrect)) {
                wScore += parseFloat(a.isCorrect);
              }
            }
          });
        } catch(e) {}

        const isCheated = (r.cheating_flag === 'YES' || (parseInt(r.cheating_count || 0) >= (parseInt(exam.max_strikes) || 3)));
        const passPercent = parseFloat(exam.passing_percentage) || 50;
        const totalMaxQ = parseFloat(exam.num_questions) || 10;
        const sScore = parseFloat(r.score || 0);
        const passMinScore = (totalMaxQ * passPercent) / 100;
        const isPass = sScore >= passMinScore;
        const evalStatus = isCheated ? 'ระงับสิทธิ์สอบ (ทุจริต)' : (isPass ? 'ผ่าน' : 'ไม่ผ่าน');

        csv += [
          idx + 1,
          clean(r.student_code || '-'),
          clean(r.name || ''),
          clean(r.room ? 'ม.' + r.room : '-'),
          clean(r.student_number || '-'),
          sScore,
          cScore,
          wScore,
          clean(evalStatus),
          clean(r.exam_round || '1'),
          r.total_time_spent || 0,
          r.cheating_count || 0,
          clean(r.cheating_flag || 'NO'),
          clean(r.submitted_at || '')
        ].join(',') + '\n';
      });

      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const link = document.createElement('a');
      const safeSubj = (exam.subject_code || 'EXAM').replace(/[^a-zA-Z0-9ก-๙_-]/g, '_');
      const safeRoom = roomFilter === 'ALL' ? 'ทุกห้อง' : ('ห้อง_' + roomFilter.replace(/[^a-zA-Z0-9ก-๙_-]/g, '_'));
      const safeDate = new Date().toISOString().slice(0, 10);
      link.setAttribute('href', url);
      link.setAttribute('download', `ผลคะแนน_${safeSubj}_${safeRoom}_${safeDate}.csv`);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);

      Swal.fire({
        icon: 'success',
        title: 'ส่งออกคะแนนสำเร็จ',
        text: `ดาวน์โหลดไฟล์ผลสอบจำนวน ${filtered.length} รายการเรียบร้อยแล้ว`,
        timer: 2000,
        showConfirmButton: false
      });
    }

    async function openGradingModal(resultId) {
      console.log('--- openGradingModal triggered ---');
      console.log('Result ID:', resultId);
      currentEditingResultId = resultId;
      const record = resultsData.find(r => r.id == resultId);
      console.log('Record found:', record);
      if (!record) {
        console.error('Record not found in resultsData');
        Swal.fire('ข้อผิดพลาด', 'ไม่พบข้อมูลผลสอบ', 'error');
        return;
      }

      const answers = JSON.parse(record.answers_json || '[]');

      // Older results may have saved the writing reference answer as blank.
      // Reload the question bank so the grading modal always shows the reference answer.
      try {
        const qResponse = await fetch(`/api/teacher/questions?exam_id=${encodeURIComponent(record.exam_id || activeExamId)}`);
        const qRes = await qResponse.json();
        if (qRes.success && Array.isArray(qRes.questions)) {
          const questionMap = new Map(qRes.questions.map(q => [String(q.id), q]));
          answers.forEach(a => {
            if (a.type === 'writing' && (!a.correct || !String(a.correct).trim())) {
              const sourceQuestion = questionMap.get(String(a.questionId));
              if (sourceQuestion && sourceQuestion.correct_answer != null) {
                a.correct = String(sourceQuestion.correct_answer);
              }
            }
          });
        }
      } catch (e) {
        console.warn('ไม่สามารถโหลดเฉลยอ้างอิงจากคลังข้อสอบ:', e);
      }

      const writingAnswers = answers.filter(a => a.type === 'writing');

      let contentHtml = '';
      if (writingAnswers.length === 0) {
        contentHtml = '<p class="text-slate-500 py-6 text-center">รายวิชานี้ไม่มีข้อสอบประเภทอัตนัย (ข้อเขียน)</p>';
      } else {
        contentHtml = '<div id="swal-grading-list" class="space-y-6 text-left max-h-[60vh] overflow-y-auto w-full pr-2">';
        writingAnswers.forEach((wa, index) => {
          const currentPoints = wa.isCorrect === 'รอตรวจ' ? 0 : parseFloat(wa.isCorrect || 0);

          contentHtml += `
            <div class="p-5 bg-white/5 rounded-2xl border border-white/5 relative" data-q-id="${wa.questionId}" data-max-points="${wa.points}">
                <div class="flex justify-between items-start mb-2">
                    <span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 text-[10px] font-bold rounded">
                        คำถามข้อเขียนที่ ${index + 1}
                    </span>
                    <span class="text-xs text-slate-400 font-bold">คะแนนเต็ม ${wa.points} คะแนน</span>
                </div>
                <p class="text-white font-bold text-sm mb-4 leading-relaxed">${escapeHtml(wa.question || '')}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="p-3 bg-red-500/5 border border-red-500/15 rounded-xl">
                        <span class="text-[10px] font-bold text-red-400 block mb-1">คำตอบของนักเรียน:</span>
                        <p class="text-white text-xs font-bold" style="white-space: pre-wrap; word-break: break-word; tab-size: 4; -moz-tab-size: 4;">${escapeHtml(wa.selected || '(ไม่ได้ตอบ)')}</p>
                    </div>
                    <div class="p-3 bg-emerald-500/5 border border-emerald-500/15 rounded-xl">
                        <span class="text-[10px] font-bold text-emerald-400 block mb-1">เฉลยอ้างอิง:</span>
                        <p class="text-white text-xs font-bold" style="white-space: pre-wrap; word-break: break-word; tab-size: 4; -moz-tab-size: 4;">${escapeHtml(wa.correct || '(ไม่มีคำเฉลยอ้างอิง)')}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="text-xs font-bold text-slate-400">ให้คะแนนที่ทำได้:</label>
                    <input type="number" step="0.5" max="${wa.points}" value="${currentPoints}" class="w-24 bg-slate-800 border border-white/10 rounded-lg p-2 text-white font-bold text-center outline-none focus:border-pink-500" data-score-input>
                    <span class="text-slate-500 text-xs">/ ${wa.points} คะแนน</span>
                    
                    <button type="button" onclick="autoGradeWithGemini(this, '${wa.questionId}')" class="ml-auto px-3.5 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:scale-102 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 transition-all shadow-md shadow-purple-950/40">
                        ✨ ใช้ AI (Gemini) ช่วยตรวจ
                    </button>
                </div>
                ${wa.aiFeedback ? `
                <div class="ai-feedback-container mt-4 p-3 rounded-xl border text-sm bg-emerald-500/10 border-emerald-500/20 text-emerald-400">
                    <strong>✨ ผลการวิเคราะห์จาก AI:</strong>
                    <div class="whitespace-pre-wrap mt-1">${escapeHtml(wa.aiFeedback)}</div>
                </div>
                <input type="hidden" data-ai-feedback="true" value="${escapeHtml(wa.aiFeedback)}">
                ` : ''}
            </div>
          `;
        });
        contentHtml += '</div>';
      }

      try {
        console.log('Calling Swal.fire for Grading...');
        Swal.fire({
          title: '✍️ ตรวจและบันทึกคะแนนข้อสอบข้อเขียน (อัตนัย)',
          html: `
            <div class="text-left mb-4 pb-4 border-b border-white/10">
              <p class="text-sm text-slate-300 font-bold">
                นักเรียน: <span class="text-white">${record.name}</span> | 
                ห้อง: <span class="text-white">ม.${record.room} เลขที่ ${record.student_number}</span>
              </p>
            </div>
            ${contentHtml}
          `,
          width: '800px',
          background: '#0f172a',
          color: '#f8fafc',
          showCancelButton: true,
          showConfirmButton: writingAnswers.length > 0,
          confirmButtonText: '💾 บันทึกคะแนนตรวจทั้งหมด',
          cancelButtonText: 'ปิดหน้านี้',
          customClass: {
            title: 'text-lg font-black text-white text-left border-b border-white/5 pb-2',
            confirmButton: 'bg-pink-500 hover:bg-pink-600 font-bold rounded-xl px-6 py-2 border-none',
            cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl px-4 py-2 border border-white/10',
            popup: 'border border-white/10 rounded-3xl'
          }
        }).then((result) => {
          console.log('Swal closed. Result:', result);
          if (result.isConfirmed) {
            submitGrading();
          }
        });
      } catch (err) {
        console.error('Error in openGradingModal Swal.fire:', err);
        alert('เกิดข้อผิดพลาดในการแสดงผล Modal: ' + err.message);
      }
    }

    async function submitGrading() {
      if (!currentEditingResultId) return;

      const items = document.querySelectorAll('#swal-grading-list > div[data-q-id]');
      const updates = [];

      items.forEach(el => {
        const qId = el.getAttribute('data-q-id');
        const maxPoints = parseFloat(el.getAttribute('data-max-points'));
        const input = el.querySelector('input[data-score-input]');
        const aiFeedbackInput = el.querySelector('input[data-ai-feedback]');
        let score = parseFloat(input.value);

        if (isNaN(score)) score = 0;
        if (score < 0) score = 0;
        if (score > maxPoints) score = maxPoints;

        updates.push({
          questionId: qId,
          score: score,
          aiFeedback: aiFeedbackInput ? aiFeedbackInput.value : null
        });
      });

      if (updates.length === 0) {
        Swal.fire('คำเตือน', 'ไม่มีข้อสอบที่ต้องตรวจ', 'warning');
        return;
      }

      Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

      try {
        const formData = new FormData();
        formData.append('id', currentEditingResultId);
        formData.append('updates', JSON.stringify(updates));

        const response = await fetch('/api/teacher/results/grade', { method: 'POST', body: formData });
        const res = await response.json();

        if (res.success) {
          Swal.fire('สำเร็จ', 'บันทึกคะแนนเรียบร้อยแล้ว', 'success');
          loadResults();
        } else {
          Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
        }
      } catch (e) {
        Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
      }
    }

    function viewAnswersModal(resultId) {
      console.log('--- viewAnswersModal triggered ---');
      console.log('Result ID:', resultId);
      const record = resultsData.find(r => r.id == resultId);
      console.log('Record found:', record);
      if (!record) {
        console.error('Record not found in resultsData');
        Swal.fire('ข้อผิดพลาด', 'ไม่พบข้อมูลประวัติการสอบ', 'error');
        return;
      }

      const answers = JSON.parse(record.answers_json || '[]');

      let choiceScore = 0;
      let choiceTotal = 0;
      let writingScore = 0;
      let writingTotal = 0;
      let needsGrading = false;

      answers.forEach(a => {
        const pts = parseFloat(a.points) || 0;
        if (a.type === 'choice') {
          choiceTotal += pts;
          if (a.isCorrect === 'ถูกต้อง' || a.isCorrect === true) {
            choiceScore += pts;
          }
        } else if (a.type === 'writing') {
          writingTotal += pts;
          if (a.isCorrect === 'รอตรวจ') {
            needsGrading = true;
          } else {
            writingScore += parseFloat(a.isCorrect) || 0;
          }
        }
      });

      const gradingBadge = needsGrading ?
        '<span class="px-2 py-0.5 text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded font-bold ml-2">รอตรวจ</span>' :
        '<span class="px-2 py-0.5 text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded font-bold ml-2">ครบแล้ว</span>';

      let html = '';
      if (answers.length === 0) {
        html = '<p class="text-slate-500 py-6 text-center">ไม่มีข้อมูลประวัติการตอบ</p>';
      } else {
        html = '<div class="space-y-4 text-left max-h-[60vh] overflow-y-auto w-full pr-2">';
        answers.forEach((ans, index) => {
          let badgeHtml = '';
          let cardBorder = 'border-white/5 bg-white/5';

          if (ans.type === 'choice') {
            if (ans.isCorrect === true || ans.isCorrect === 'ถูกต้อง') {
              badgeHtml = '<span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 font-bold text-[10px] rounded border border-emerald-500/20">✅ ถูกต้อง</span>';
              cardBorder = 'border-emerald-500/20 bg-emerald-500/5';
            } else {
              badgeHtml = '<span class="px-2 py-0.5 bg-red-500/20 text-red-400 font-bold text-[10px] rounded border border-red-500/20">❌ ผิด</span>';
              cardBorder = 'border-red-500/20 bg-red-500/5';
            }
          } else {
            badgeHtml = '<span class="px-2 py-0.5 bg-sky-500/20 text-sky-400 font-bold text-[10px] rounded border border-sky-500/20">📝 ข้อเขียน</span>';
          }

          html += `
            <div class="p-4 rounded-xl border ${cardBorder} relative">
              <p class="text-white font-bold text-sm mb-3">${escapeHtml(ans.question)}</p>
              
              <div class="flex items-center gap-2 mb-1">
                  <span class="text-[10px] text-slate-400 font-bold">ตอบ:</span>
                  <span class="text-white font-black text-xs" style="white-space: pre-wrap; word-break: break-word; tab-size: 4; -moz-tab-size: 4;">${escapeHtml(ans.selected || '(ไม่ได้ตอบ)')}</span>
              </div>
              <div class="flex items-center gap-2">
                  <span class="text-[10px] text-slate-400 font-bold">เฉลย:</span>
                  <span class="text-white font-black text-xs" style="white-space: pre-wrap; word-break: break-word; tab-size: 4; -moz-tab-size: 4;">${escapeHtml(ans.correct || '(ไม่มีคำเฉลยอ้างอิง)')}</span>
              </div>
              
              ${ans.aiFeedback ? `<p class="text-[10px] text-purple-400 font-bold mt-2 whitespace-pre-wrap">✨ คำแนะนำ AI: \n${escapeHtml(ans.aiFeedback)}</p>` : ''}
              
              <div class="absolute top-4 right-4">
                  ${badgeHtml}
              </div>
            </div>
          `;
        });
        html += '</div>';
      }

      try {
        console.log('Calling Swal.fire for viewAnswers...');
        Swal.fire({
          title: '👁️ รายละเอียดและประวัติการตอบ',
          html: `
            <div class="text-left mb-4 pb-4 border-b border-white/10 font-sans">
              <p class="text-sm text-slate-300 font-bold">
                นักเรียน: <span class="text-white">${record.name}</span> | 
                ห้อง: <span class="text-white">ม.${record.room} เลขที่ ${record.student_number}</span><br>
                คะแนนรวม: <span class="text-pink-400 font-bold">${record.score} คะแนน</span> ${gradingBadge}
                <span class="text-slate-400 text-xs font-normal">
                  (ปรนัย: <span class="text-sky-400 font-bold">${choiceScore}/${choiceTotal}</span> | 
                  อัตนัย: <span class="text-amber-400 font-bold">${writingScore}/${writingTotal}</span>)
                </span> | 
                รอบที่สอบ: <span class="text-sky-400 font-bold">${record.exam_round || '1'}</span>
              </p>
            </div>
            ${html}
          `,
          width: '800px',
          background: '#0f172a',
          color: '#f8fafc',
          showCloseButton: true,
          showConfirmButton: false,
          customClass: {
            title: 'text-lg font-black text-white text-left border-b border-white/5 pb-2',
            popup: 'border border-white/10 rounded-3xl',
            closeButton: 'text-slate-400 hover:text-white'
          }
        });
      } catch (err) {
        console.error('Error in viewAnswersModal Swal.fire:', err);
        alert('เกิดข้อผิดพลาดในการแสดงผล Modal: ' + err.message);
      }
    }

    function deleteResult(id) {
      Swal.fire({
        title: 'ต้องการลบผลการสอบนี้?',
        text: 'การลบผลการสอบจะล้างสิทธิ์ครั้งนั้นทำให้นักเรียนสอบใหม่ได้',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ลบผลสอบ',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('id', id);
          try {
            const response = await fetch('/api/teacher/results/delete', { method: 'POST', body: formData });
            const res = await response.json();
            if (res.success) {
              Swal.fire('สำเร็จ', 'ลบผลการสอบเรียบร้อยแล้ว', 'success');
              loadResults();
            }
          } catch (e) {
            Swal.fire('ล้มเหลว', 'ล้มเหลว', 'error');
          }
        }
      });
    }

    function populateLogsExamFilter() {
      const select = document.getElementById('logs-exam-filter');
      if (!select) return;

      const currentSelected = select.value || (activeExamId && activeExamId !== 'global' ? activeExamId : 'ALL');

      select.innerHTML = '<option value="ALL">แสดงทุกวิชาสอบ</option>';

      globalExamsList.forEach(e => {
        const option = document.createElement('option');
        option.value = e.id;
        option.textContent = `${e.subject_code} - ${e.subject_name}`;
        if (currentSelected === e.id) option.selected = true;
        select.appendChild(option);
      });
    }

    async function loadLogs() {
      try {
        populateLogsExamFilter();

        const filterEl = document.getElementById('logs-exam-filter');
        const filterVal = filterEl ? filterEl.value : (activeExamId && activeExamId !== 'global' ? activeExamId : 'ALL');

        const response = await fetch(`/api/teacher/logs?exam_id=${filterVal}`);
        const res = await response.json();

        // Destroy existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#logsTable')) {
          $('#logsTable').DataTable().destroy();
        }

        const container = document.getElementById('logs-list');
        if (res.success && res.logs) {
          if (res.logs.length === 0) {
            container.innerHTML = `<tr><td colspan="4" class="py-8 text-center text-slate-500">ไม่มีประวัติความเสี่ยงในระบบขณะนี้</td></tr>`;
            return;
          }

          let html = '';
          res.logs.forEach(l => {
            const subjectBadge = (filterVal === 'ALL' && l.subject_code) ? `<br><span class="text-[10px] text-slate-400 font-normal">วิชา: ${l.subject_code} ${l.subject_name}</span>` : '';
            html += `
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 text-xs font-mono text-slate-400">${l.timestamp}</td>
                            <td class="py-3 px-4 font-bold text-white">${l.student_email}${subjectBadge}</td>
                            <td class="py-3 px-4 text-xs text-red-400 font-bold">${l.error_message}</td>
                            <td class="py-3 px-4 text-[10px] text-slate-500 font-mono">${l.screen_resolution || 'ไม่ทราบ'}</td>
                        </tr>
                    `;
          });
          container.innerHTML = html;

          // Re-initialize DataTable
          $('#logsTable').DataTable({
            "order": [[0, "desc"]], // Sort by timestamp desc
            "language": {
              "search": "ค้นหาในบันทึก:",
              "lengthMenu": "แสดง _MENU_ รายการ",
              "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
              "infoEmpty": "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
              "zeroRecords": "ไม่พบข้อมูลที่ค้นหา",
              "paginate": {
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
              }
            }
          });
        }
      } catch (e) {
        console.error(e);
      }
    }

    async function clearLogs() {
      const filterEl = document.getElementById('logs-exam-filter');
      const filterVal = filterEl ? filterEl.value : (activeExamId && activeExamId !== 'global' ? activeExamId : 'ALL');
      const isAll = (filterVal === 'ALL');

      const titleText = isAll ? 'ต้องการล้างบันทึกความเสี่ยงทั้งหมด?' : 'ต้องการล้างบันทึกความเสี่ยงของวิชานี้?';
      const bodyText = isAll ? 'ข้อมูลประวัติความเสี่ยงและพฤติกรรมน่าสงสัยทั้งหมดจะถูกลบถาวร!' : 'ข้อมูลประวัติความเสี่ยงของรายวิชานี้จะถูกลบถาวร!';

      Swal.fire({
        title: titleText,
        text: bodyText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'ล้างข้อมูล',
        cancelButtonText: 'ยกเลิก'
      }).then(async (result) => {
        if (result.isConfirmed) {
          Swal.fire({ title: 'กำลังล้างบันทึกความเสี่ยง...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
          try {
            const formData = new FormData();
            formData.append('exam_id', filterVal);
            const response = await fetch('/api/teacher/logs/clear', { method: 'POST', body: formData });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', res.message || 'ล้างบันทึกความเสี่ยงเรียบร้อยแล้ว', 'success');
              loadLogs();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
            }
          } catch (e) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
          }
        }
      });
    }

    // --- Tab 4: Import Questions ---
    function openImportModal() {
      const html = `
        <div class="text-left space-y-4 w-full">
          <p class="text-xs text-slate-400 leading-relaxed">
            คัดลอกข้อมูลคำถามจาก Excel/Google Sheets มาวาง โดยคอลัมน์ต้องเรียงลำดับตามนี้:<br>
            <b>[โจทย์คำถาม, ตัวเลือก A, ตัวเลือก B, ตัวเลือก C, ตัวเลือก D, คำตอบ (เฉลย), ประเภท (choice/writing), คะแนน]</b>
          </p>
          <textarea id="swal-import-data" class="w-full bg-slate-800 border border-white/10 rounded-xl p-3 text-white font-mono text-xs outline-none focus:border-sky-400" rows="8" placeholder="โจทย์\tก\tข\tค\tง\tก\tchoice\t1"></textarea>

          <div class="flex items-center gap-2 mt-4">
            <input type="checkbox" id="swal-import-clear-existing" class="w-4 h-4 rounded border-white/10 text-pink-500">
            <label for="swal-import-clear-existing" class="text-xs text-slate-350 select-none">ลบข้อสอบรายวิชานี้ที่เคยมีออกทั้งหมดก่อนนำเข้า</label>
          </div>
        </div>
      `;

      Swal.fire({
        title: '📥 นำเข้าข้อสอบ',
        html: html,
        width: '550px',
        background: '#0f172a',
        color: '#f8fafc',
        showCancelButton: true,
        confirmButtonText: 'นำเข้าข้อมูล 🚀',
        cancelButtonText: 'ยกเลิก',
        customClass: {
          title: 'text-lg font-black text-white text-left border-b border-white/5 pb-2',
          confirmButton: 'bg-sky-500 hover:bg-sky-600 font-bold rounded-xl px-6 py-2 border-none',
          cancelButton: 'bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl px-4 py-2 border border-white/10',
          popup: 'border border-white/10 rounded-3xl'
        },
        preConfirm: () => {
          const popup = Swal.getHtmlContainer();
          const rawText = popup.querySelector('#swal-import-data').value;
          const clearExisting = popup.querySelector('#swal-import-clear-existing').checked;

          if (!rawText.trim()) {
            Swal.showValidationMessage('กรุณาวางข้อมูลดิบก่อนนำเข้า');
            return false;
          }

          return { rawText, clearExisting };
        }
      }).then(async (result) => {
        if (result.isConfirmed) {
          const { rawText, clearExisting } = result.value;
          const rows = rawText.split('\n').map(line => {
            let parts = [];
            if (line.includes('\t')) {
              parts = line.split('\t');
            } else if (line.includes(',')) {
              parts = line.split(',');
            } else {
              parts = [line];
            }
            return parts.map(col => col.trim());
          }).filter(r => r.length > 1 || (r.length === 1 && r[0] !== ''));

          Swal.fire({ title: 'กำลังนำเข้าข้อมูลคำถาม...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          const formData = new FormData();
          formData.append('exam_id', activeExamId);
          formData.append('rows', JSON.stringify(rows));
          formData.append('clearExisting', clearExisting);

          try {
            const response = await fetch('/api/teacher/questions/import', {
              method: 'POST',
              body: formData
            });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', `นำเข้าข้อมูลเรียบร้อยแล้ว จำนวน ${res.count} ข้อ`, 'success');
              loadQuestions();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'เกิดข้อผิดพลาด', 'error');
            }
          } catch (err) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'การเชื่อมต่อผิดพลาด', 'error');
          }
        }
      });
    }



    function editScoreDirect(id) {
      const record = resultsData.find(r => r.id == id);
      if (!record) {
        Swal.fire('ข้อผิดพลาด', 'ไม่พบข้อมูลประวัติการสอบ', 'error');
        return;
      }
      const studentName = record.name || '';
      const currentScore = record.score;

      Swal.fire({
        title: 'แก้ไขข้อมูลผู้สอบ',
        html: `
          <div class="space-y-4 text-left font-sans">
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">ชื่อ-นามสกุล</label>
              <input id="swal-edit-name" type="text" class="w-full bg-slate-800 border border-white/10 rounded-xl p-2 text-white font-bold outline-none focus:border-sky-500" value="${escapeHtml(record.name || '')}">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">ห้อง</label>
                <input id="swal-edit-room" type="text" class="w-full bg-slate-800 border border-white/10 rounded-xl p-2 text-white font-bold outline-none focus:border-sky-500" value="${escapeHtml(record.room || '')}">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">เลขที่</label>
                <input id="swal-edit-number" type="text" class="w-full bg-slate-800 border border-white/10 rounded-xl p-2 text-white font-bold outline-none focus:border-sky-500" value="${escapeHtml(record.student_number || '')}">
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 mb-1">รหัสนักเรียน</label>
              <input id="swal-edit-code" type="text" class="w-full bg-slate-800 border border-white/10 rounded-xl p-2 text-white font-bold outline-none focus:border-sky-500" value="${escapeHtml(record.student_code || '')}">
            </div>
          </div>
        `,
        width: '500px',
        background: '#0f172a',
        color: '#f8fafc',
        showCancelButton: true,
        confirmButtonText: 'บันทึก 💾',
        cancelButtonText: 'ยกเลิก',
        preConfirm: () => {
          const popup = Swal.getHtmlContainer();
          const name = popup.querySelector('#swal-edit-name').value.trim();
          const room = popup.querySelector('#swal-edit-room').value.trim();
          const number = popup.querySelector('#swal-edit-number').value.trim();
          const code = popup.querySelector('#swal-edit-code').value.trim();
          
          if (!name) {
            Swal.showValidationMessage('กรุณากรอกชื่อ');
            return false;
          }
          return { name, room, number, code };
        }
      }).then(async (result) => {
        if (result.isConfirmed) {
          const { name, room, number, code } = result.value;
          const formData = new FormData();
          formData.append('id', id);
          formData.append('score', currentScore);
          formData.append('name', name);
          formData.append('room', room);
          formData.append('student_number', number);
          formData.append('student_code', code);

          Swal.fire({ title: 'กำลังบันทึกข้อมูล...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

          try {
            const response = await fetch('/api/teacher/results/update-score-direct', {
              method: 'POST',
              body: formData
            });
            const res = await response.json();
            Swal.close();
            if (res.success) {
              Swal.fire('สำเร็จ', 'อัปเดตข้อมูลเรียบร้อยแล้ว', 'success');
              loadResults();
            } else {
              Swal.fire('ล้มเหลว', res.message || 'บันทึกไม่สำเร็จ', 'error');
            }
          } catch (e) {
            Swal.close();
            Swal.fire('ล้มเหลว', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
          }
        }
      });
    }
    // Old modals removed
    async function autoGradeWithGemini(btn, questionId) {
      const parent = btn.closest('[data-q-id]');
      const studentAnswer = parent.querySelector('.bg-red-500\\/5 p').textContent;
      const correctAnswer = parent.querySelector('.bg-emerald-500\\/5 p').textContent;
      const questionText = parent.querySelector('p.text-white').textContent;
      const maxPoints = parseFloat(parent.getAttribute('data-max-points'));
      const scoreInput = parent.querySelector('[data-score-input]');

      btn.disabled = true;
      const originalText = btn.innerHTML;
      btn.innerHTML = '⚡ กำลังตรวจด้วย AI...';

      // Find or create feedback container
      let feedbackEl = parent.querySelector('.ai-feedback-container');
      if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.className = 'ai-feedback-container mt-4 p-3 rounded-xl border text-sm';
        // Insert it right after the button container
        const flexContainer = parent.querySelector('.flex.items-center.gap-3');
        flexContainer.insertAdjacentElement('afterend', feedbackEl);
      }

      feedbackEl.classList.remove('hidden', 'bg-emerald-500/10', 'border-emerald-500/20', 'text-emerald-400', 'bg-red-500/10', 'border-red-500/20', 'text-red-400');
      feedbackEl.classList.add('bg-slate-800', 'border-white/10', 'text-slate-300');
      feedbackEl.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <span>✨ ระบบกำลังให้ AI วิเคราะห์คำตอบ กรุณารอสักครู่...</span>
            <span class="text-xs font-mono font-bold text-indigo-400"><span data-progress-text>0</span>%</span>
        </div>
        <div class="h-1.5 w-full bg-slate-900 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full transition-all duration-300 ease-out" style="width: 0%" data-progress-bar></div>
        </div>
      `;

      let progress = 0;
      const progressInterval = setInterval(() => {
        if (progress < 95) {
          progress += (95 - progress) * 0.08;
          const displayProgress = Math.floor(progress);
          const bar = feedbackEl.querySelector('[data-progress-bar]');
          const txt = feedbackEl.querySelector('[data-progress-text]');
          if (bar) bar.style.width = displayProgress + '%';
          if (txt) txt.textContent = displayProgress;
        }
      }, 300);

      try {
        const formData = new FormData();
        formData.append('question', questionText);
        formData.append('studentAnswer', studentAnswer);
        formData.append('correctAnswer', correctAnswer);
        formData.append('maxPoints', maxPoints);

        const response = await fetch('/api/exam/ai-grade', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

        clearInterval(progressInterval);
        const bar = feedbackEl.querySelector('[data-progress-bar]');
        const txt = feedbackEl.querySelector('[data-progress-text]');
        if (bar) bar.style.width = '100%';
        if (txt) txt.textContent = '100';

        // Wait a tiny bit so the user sees 100% before it switches to success/error
        await new Promise(resolve => setTimeout(resolve, 400));

        btn.disabled = false;
        btn.innerHTML = '✨ ให้ AI ตรวจใหม่';

        if (data.success) {
          scoreInput.value = data.score;

          let aiFeedbackInput = parent.querySelector('input[data-ai-feedback]');
          if (!aiFeedbackInput) {
            aiFeedbackInput = document.createElement('input');
            aiFeedbackInput.type = 'hidden';
            aiFeedbackInput.setAttribute('data-ai-feedback', 'true');
            parent.appendChild(aiFeedbackInput);
          }
          aiFeedbackInput.value = data.explanation || 'ให้คะแนนสำเร็จ';

          feedbackEl.classList.remove('bg-slate-800', 'border-white/10', 'text-slate-300');
          feedbackEl.classList.add('bg-emerald-500/10', 'border-emerald-500/20', 'text-emerald-400');
          feedbackEl.innerHTML = `<strong>✨ ผลการวิเคราะห์จาก AI:</strong><div class="whitespace-pre-wrap mt-1">${escapeHtml(data.explanation || 'ให้คะแนนสำเร็จ')}</div>`;
        } else {
          feedbackEl.classList.remove('bg-slate-800', 'border-white/10', 'text-slate-300');
          feedbackEl.classList.add('bg-red-500/10', 'border-red-500/20', 'text-red-400');
          feedbackEl.innerHTML = `<strong>❌ ล้มเหลว:</strong> ${escapeHtml(data.message || 'AI ไม่สามารถตอบคำถามได้')}`;
        }
      } catch (err) {
        btn.disabled = false;
        btn.innerHTML = '✨ ลองอีกครั้ง';
        feedbackEl.classList.remove('animate-pulse', 'bg-slate-800', 'border-white/10', 'text-slate-300');
        feedbackEl.classList.add('bg-red-500/10', 'border-red-500/20', 'text-red-400');
        feedbackEl.innerHTML = '<strong>❌ ข้อผิดพลาด:</strong> ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้';
      }
    }

    // Old functions removed

    // --- Tab 7: Logs ---
    // Duplicate removed - using the one declared above with filtering support
  </script>
</body>

</html>
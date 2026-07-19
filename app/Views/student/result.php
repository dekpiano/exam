<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>ผลการสอบ |
    <?= esc($websiteName) ?>
  </title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    html {
      scroll-behavior: smooth;
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
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
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
      filter: blur(60px);
      opacity: 0.35;
      animation: move 15s infinite alternate ease-in-out;
    }

    .blob-1 {
      width: 300px;
      height: 300px;
      background: #ec4899;
      top: -10%;
      left: -10%;
    }

    .blob-2 {
      width: 400px;
      height: 400px;
      background: #0ea5e9;
      bottom: -10%;
      right: -10%;
      animation-delay: -5s;
    }

    @keyframes move {
      from {
        transform: translate(0, 0) scale(1);
      }

      to {
        transform: translate(40px, 40px) scale(1.2);
      }
    }

    .main-wrapper {
      width: 100%;
      max-width: 500px;
      margin: auto;
    }

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

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .result-score {
      font-size: 5rem;
      font-weight: 900;
      line-height: 1;
      margin: 1.5rem 0;
    }
  </style>
</head>

<body>
  <?= view('common/loader') ?>

  <div class="bg-animation">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
  </div>

  <div class="main-wrapper">
    <div class="glass-card text-center">

      <!-- Header -->
      <div class="flex flex-col items-center mb-6">
        <?php if (!empty($logoUrl)): ?>
          <img src="<?= esc($logoUrl) ?>" alt="logo" class="h-16 mb-4">
        <?php endif; ?>
        <h1 class="text-2xl font-extrabold bg-gradient-to-r from-pink-500 to-sky-400 bg-clip-text text-transparent">
          <?= esc($websiteName) ?>
        </h1>
        <p class="text-sky-400 font-bold text-sm">
          <?= esc($examType) ?>
        </p>
        <p class="text-slate-500 text-xs mt-1">ผู้สอบ:
          <?= esc($studentName) ?>
        </p>
      </div>

      <?php
      if ($lastResult) {
        $score = (float) $lastResult['score'];
        $total = (float) $lastResult['total_questions'];
        $cheatingCount = (int) $lastResult['cheating_count'];
        $cheatingFlag = $lastResult['cheating_flag'];
        $timeSpent = (int) $lastResult['total_time_spent'];

        // Parse JSON answers to count parts
        $answers = json_decode($lastResult['answers_json'], true) ?? [];
        $part1Score = 0;
        $part1Total = 0;
        $part2Total = 0;

        foreach ($answers as $ans) {
          if (($ans['type'] ?? 'choice') === 'choice') {
            $part1Total += (float) ($ans['points'] ?? 1);
            if (($ans['isCorrect'] ?? '') === 'ถูกต้อง') {
              $part1Score += (float) ($ans['points'] ?? 1);
            }
          } else {
            $part2Total += (float) ($ans['points'] ?? 5);
          }
        }

        $passPercentVal = $passPercent ?? 50;
        $passCriteria = $part1Total * ($passPercentVal / 100);
        $isPassed = ($part1Score >= $passCriteria);

        $isDisqualified = ($cheatingFlag === 'YES');
      } else {
        $score = 0;
        $total = 0;
        $cheatingCount = 0;
        $cheatingFlag = 'NO';
        $timeSpent = 0;
        $part1Score = 0;
        $part1Total = 0;
        $part2Total = 0;
        $isPassed = false;
        $isDisqualified = false;
      }
      ?>

      <!-- Results Display -->
      <?php if ($isDisqualified): ?>
        <div class="text-6xl mb-4">🚫</div>
        <h2 class="text-2xl font-black text-white">ตรวจสอบพฤติกรรม</h2>
        <div
          class="mx-auto w-fit px-6 py-2 rounded-full font-black text-sm uppercase tracking-widest mt-2 mb-4 bg-red-600 text-white shadow-lg animate-pulse">
          DISQUALIFIED
        </div>
        <div class="p-4 rounded-xl text-sm font-bold mb-8 bg-red-100/10 text-red-400 border border-red-500/30">
          ⚠️ พบการสลับหน้าจอหรือพฤติกรรมสุ่มเสี่ยงทั้งหมด <b>
            <?= $cheatingCount ?> ครั้ง
          </b><br>
          ผลการสอบของคุณถูกระงับเพื่อรอให้คุณครูผู้สอนตรวจสอบพฤติกรรม
        </div>
      <?php else: ?>
        <div class="text-6xl mb-4">
          <?= $isPassed ? '🌟' : '📚' ?>
        </div>
        <h2 class="text-2xl font-black text-white">
          <?= $isPassed ? 'ยินดีด้วย! คุณสอบผ่าน' : 'พยายามใหม่อีกครั้ง' ?>
        </h2>
        <div
          class="mx-auto w-fit px-6 py-2 rounded-full font-black text-sm uppercase tracking-widest mt-2 mb-4 <?= $isPassed ? 'bg-emerald-500 text-white shadow-lg' : 'bg-orange-500 text-white shadow-sm' ?>">
          <?= $isPassed ? 'PASSED' : 'FAILED' ?>
        </div>

        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest opacity-60">คะแนนที่คุณทำได้คือ</p>
        <div class="result-score">
          <span class="<?= $isPassed ? 'text-emerald-400' : 'text-orange-400' ?>">
            <?= $part1Score ?>
          </span>
        </div>
        <p class="text-pink-500 font-black text-lg mb-6 uppercase tracking-widest">จากทั้งหมด
          <?= $part1Total ?> คะแนน
        </p>

        <div class="space-y-3 mb-8">
          <div class="flex items-center justify-between p-4 bg-pink-500/10 border border-pink-500/20 rounded-2xl">
            <div class="text-left">
              <p class="text-[10px] font-black text-pink-500 uppercase tracking-widest">ตอนที่ 1: ปรนัย</p>
              <p class="text-xs text-slate-400 font-bold">การเลือกตอบตัวเลือก</p>
            </div>
            <div class="text-right">
              <span class="text-2xl font-black text-white">
                <?= $part1Score ?>
              </span>
              <span class="text-xs text-pink-500 font-bold">/
                <?= $part1Total ?>
              </span>
            </div>
          </div>

          <?php if ($part2Total > 0): ?>
            <div class="flex items-center justify-between p-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl">
              <div class="text-left">
                <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest">ตอนที่ 2: อัตนัย</p>
                <p class="text-xs text-slate-400 font-bold italic">รอครูตรวจ/ยังไม่คิดคะแนน</p>
              </div>
              <div class="text-right">
                <span class="text-xl font-black text-amber-400">รอตรวจ</span>
                <span class="text-xs text-amber-400 font-bold opacity-50">/
                  <?= $part2Total ?>
                </span>
              </div>
            </div>
            <p class="text-[10px] text-amber-400/60 italic font-bold text-center">* คะแนนรวมปัจจุบันคิดเฉพาะตอนที่ 1 (ปรนัย)
              เท่านั้น</p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
      <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
        <p class="text-slate-500 text-[10px] font-bold uppercase">เวลาที่ใช้</p>
        <p class="text-white font-black text-lg">
          <?= $timeSpent ?> วินาที
        </p>
      </div>
      <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
        <p class="text-slate-500 text-[10px] font-bold uppercase">การสลับหน้าจอ</p>
        <p class="text-white font-black text-lg">
          <?= $cheatingCount ?> ครั้ง
        </p>
      </div>
    </div>

    <button onclick="window.location.href='/'"
      class="w-full py-4 text-sky-400 font-bold hover:text-pink-500 transition-colors border-t border-white/5 mt-4">
      🔄 กลับหน้าแรก (หรือทำใหม่ถ้ามีสิทธิ์)
    </button>

  </div>
  </div>
</body>

</html>
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

    html, body {
      overscroll-behavior-y: none; /* Prevent pull-to-refresh on mobile */
    }
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

    /* Submission state must remain visibly active even while the button is disabled. */
    .btn-submit.is-processing {
      background: linear-gradient(135deg, #059669, #047857) !important;
      color: #ffffff !important;
      border-color: rgba(255, 255, 255, 0.16) !important;
      opacity: 1 !important;
      cursor: wait !important;
      box-shadow: 0 8px 28px rgba(16, 185, 129, 0.28) !important;
    }

    .submit-spinner {
      width: 1.05rem;
      height: 1.05rem;
      border: 3px solid rgba(255, 255, 255, 0.28);
      border-top-color: #ffffff;
      border-radius: 50%;
      display: inline-block;
      animation: submitSpinner 0.75s linear infinite;
      flex: 0 0 auto;
    }

    @keyframes submitSpinner {
      to { transform: rotate(360deg); }
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

    /* ============================================================
       POKÉMON RPG ADVENTURE & BATTLE STAGE STYLES
       ============================================================ */
    :root {
      --poke-yellow: #ffcb05;
      --poke-blue: #2a75bb;
      --poke-dark-blue: #1b325f;
      --poke-red: #ff3a3a;
      --poke-dark: #0f172a;
    }

    #pokemonExamView {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0.5rem 0.25rem 3rem;
      user-select: none;
    }
    @media (min-width: 640px) {
      #pokemonExamView { padding: 0.75rem 1rem 3rem; }
    }

    /* Top HUD in Pokemon Mode */
    .poke-hud-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.4rem;
      background: rgba(15, 23, 42, 0.9);
      border: 2px solid rgba(255, 203, 5, 0.4);
      border-radius: 12px;
      padding: 0.4rem 0.5rem;
      margin-bottom: 0.4rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4), 0 0 10px rgba(255, 203, 5, 0.15);
      flex-wrap: wrap;
    }
    .poke-route-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8rem;
      font-weight: 800;
      color: var(--poke-yellow);
    }
    .poke-stage-switch {
      display: inline-flex;
      background: rgba(0,0,0,0.5);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 10px;
      padding: 2px;
      gap: 2px;
    }
    .poke-stage-btn {
      padding: 0.25rem 0.5rem;
      border-radius: 8px;
      font-size: 0.7rem;
      font-weight: 800;
      color: #94a3b8;
      background: transparent;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
    }
    .poke-stage-btn.active {
      background: linear-gradient(135deg, #ffcb05, #f59e0b);
      color: #1e1b4b;
      box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
    }

    /* OVERWORLD MAP STAGE */
    .poke-map-card {
      background: #0f172a;
      border: 3px solid #334155;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0,0,0,0.5);
      position: relative;
    }
    .poke-map-header {
      background: linear-gradient(90deg, #1e293b, #0f172a);
      border-bottom: 2px solid #334155;
      padding: 0.5rem 0.75rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.7rem;
      font-weight: 800;
      color: #cbd5e1;
    }
    #pokeMapCanvas {
      width: 100%;
      height: auto;
      display: block;
      background: #52a82e;
      cursor: crosshair;
      touch-action: none;
      image-rendering: -webkit-optimize-contrast;
      image-rendering: crisp-edges;
      image-rendering: pixelated;
    }

    /* Map Controls Bar & Mobile D-Pad */
    .poke-map-footer {
      background: #1e293b;
      border-top: 2px solid #334155;
      padding: 0.5rem 0.75rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    .dpad-container {
      display: grid;
      grid-template-columns: repeat(3, 34px);
      grid-template-rows: repeat(3, 34px);
      gap: 2px;
      user-select: none;
    }
    .dpad-btn {
      background: #334155;
      border: 2px solid #475569;
      border-radius: 8px;
      color: #f8fafc;
      font-size: 0.9rem;
      font-weight: 900;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.1s;
      box-shadow: 0 2px 0 #1e293b;
    }
    .dpad-btn:active {
      transform: translateY(2px);
      box-shadow: 0 1px 0 #1e293b;
      background: #ffcb05;
      color: #0f172a;
    }
    .btn-quick-encounter {
      background: linear-gradient(135deg, #ef4444, #f97316);
      color: #fff;
      font-weight: 900;
      font-size: 0.8rem;
      padding: 0.6rem 1rem;
      border-radius: 12px;
      border: 2px solid #fca5a5;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
      display: flex;
      align-items: center;
      gap: 0.4rem;
      transition: all 0.2s;
    }
    .btn-quick-encounter:hover {
      transform: scale(1.03);
      box-shadow: 0 6px 15px rgba(239, 68, 68, 0.6);
    }

    /* BATTLE STAGE */
    .poke-battle-arena {
      background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 45%, #14532d 100%);
      border: 2px solid #ffcb05;
      border-radius: 16px;
      padding: 0.75rem 0.5rem;
      position: relative;
      overflow: hidden;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6), inset 0 0 30px rgba(255, 203, 5, 0.1);
      transition: background 0.6s ease, border-color 0.6s ease, box-shadow 0.6s ease;
    }
    @media (min-width: 640px) {
      .poke-battle-arena { padding: 1.75rem 1.5rem; border-radius: 28px; border-width: 3px; }
    }

    /* Arena Habitat Badge */
    .arena-habitat-badge {
      position: absolute;
      top: 6px;
      left: 8px;
      z-index: 5;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.25rem 0.65rem;
      border-radius: 99px;
      font-size: 0.7rem;
      font-weight: 800;
      backdrop-filter: blur(8px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
      transition: all 0.4s ease;
    }

    /* Scenery Backdrop */
    .arena-scenery-backdrop {
      position: absolute;
      inset: 0;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
      border-radius: inherit;
      transition: opacity 0.5s ease;
    }

    /* 1. Forest Environment (ป่าเขียวขจี) */
    .poke-battle-arena.arena-forest {
      background: linear-gradient(180deg, #052e16 0%, #064e3b 40%, #022c22 75%, #14532d 100%) !important;
      border-color: #22c55e !important;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.7), inset 0 0 40px rgba(34, 197, 94, 0.2) !important;
    }
    .arena-forest .arena-habitat-badge {
      background: rgba(5, 46, 22, 0.85);
      border: 1px solid rgba(74, 222, 128, 0.5);
      color: #86efac;
    }
    .arena-forest .enemy-pedestal,
    .arena-forest .player-pedestal {
      background: radial-gradient(ellipse, rgba(34, 197, 94, 0.85) 0%, rgba(21, 128, 61, 0.45) 50%, transparent 75%) !important;
      box-shadow: 0 4px 18px rgba(34, 197, 94, 0.4) !important;
    }
    .arena-forest .arena-scenery-backdrop {
      background-image: 
        radial-gradient(circle at 20% 30%, rgba(134, 239, 172, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 80% 60%, rgba(74, 222, 128, 0.12) 0%, transparent 50%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 300' preserveAspectRatio='none'%3E%3Cpath d='M0 300 L0 180 L35 150 L70 200 L120 120 L170 190 L220 100 L270 180 L320 130 L370 210 L430 90 L490 200 L550 110 L610 190 L680 80 L740 180 L800 120 L860 200 L920 140 L970 190 L1000 160 L1000 300 Z' fill='%23022c22' opacity='0.7'/%3E%3Cpath d='M0 300 L0 210 L50 170 L100 230 L160 160 L210 220 L270 150 L330 230 L390 170 L460 240 L520 160 L580 230 L650 150 L710 230 L780 170 L840 240 L910 180 L960 230 L1000 200 L1000 300 Z' fill='%2314532d' opacity='0.85'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: bottom;
      background-size: 100% 70%;
    }

    /* 2. Mountain / Volcano Environment (ภูเขาและถ้ำหิน) */
    .poke-battle-arena.arena-mountain {
      background: linear-gradient(180deg, #450a0a 0%, #29120e 35%, #1c1917 65%, #44403c 100%) !important;
      border-color: #f97316 !important;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.7), inset 0 0 40px rgba(249, 115, 22, 0.25) !important;
    }
    .arena-mountain .arena-habitat-badge {
      background: rgba(69, 10, 10, 0.85);
      border: 1px solid rgba(251, 146, 60, 0.5);
      color: #fed7aa;
    }
    .arena-mountain .enemy-pedestal,
    .arena-mountain .player-pedestal {
      background: radial-gradient(ellipse, rgba(234, 88, 12, 0.85) 0%, rgba(120, 53, 15, 0.5) 45%, #292524 75%, transparent 80%) !important;
      box-shadow: 0 4px 18px rgba(234, 88, 12, 0.45) !important;
    }
    .arena-mountain .arena-scenery-backdrop {
      background-image: 
        radial-gradient(circle at 75% 20%, rgba(239, 68, 68, 0.2) 0%, transparent 45%),
        radial-gradient(circle at 25% 70%, rgba(249, 115, 22, 0.15) 0%, transparent 40%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 300' preserveAspectRatio='none'%3E%3Cpolygon points='0,300 0,160 140,70 280,210 420,50 560,190 700,40 840,180 1000,90 1000,300' fill='%231c1917' opacity='0.7'/%3E%3Cpolygon points='0,300 0,220 180,120 340,240 500,100 680,230 820,130 1000,210 1000,300' fill='%23292524' opacity='0.9'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: bottom;
      background-size: 100% 75%;
    }

    /* 3. River / Water Basin Environment (แม่น้ำและทะเลสาบ) */
    .poke-battle-arena.arena-river {
      background: linear-gradient(180deg, #082f49 0%, #0c4a6e 40%, #075985 75%, #0369a1 100%) !important;
      border-color: #38bdf8 !important;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.7), inset 0 0 40px rgba(56, 189, 248, 0.25) !important;
    }
    .arena-river .arena-habitat-badge {
      background: rgba(8, 47, 73, 0.85);
      border: 1px solid rgba(56, 189, 248, 0.5);
      color: #bae6fd;
    }
    .arena-river .enemy-pedestal,
    .arena-river .player-pedestal {
      background: radial-gradient(ellipse, rgba(56, 189, 248, 0.9) 0%, rgba(2, 132, 199, 0.55) 45%, rgba(3, 105, 161, 0.3) 70%, transparent 78%) !important;
      box-shadow: 0 4px 20px rgba(56, 189, 248, 0.5) !important;
    }
    .arena-river .arena-scenery-backdrop {
      background-image: 
        radial-gradient(circle at 30% 25%, rgba(186, 230, 253, 0.2) 0%, transparent 45%),
        radial-gradient(circle at 70% 65%, rgba(56, 189, 248, 0.15) 0%, transparent 40%),
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 300' preserveAspectRatio='none'%3E%3Cpath d='M0 300 Q150 160 300 200 T600 170 T900 210 T1000 180 L1000 300 Z' fill='%23075985' opacity='0.65'/%3E%3Cpath d='M0 300 Q200 210 400 235 T800 215 T1000 230 L1000 300 Z' fill='%230284c7' opacity='0.85'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: bottom;
      background-size: 100% 70%;
    }

    /* Battle Visual Field (Platforms & Pokémon Sprites) */
    .poke-battle-field {
      position: relative;
      height: 160px; /* Reduced for mobile */
      margin-bottom: 0.5rem;
    }
    @media (min-width: 640px) {
      .poke-battle-field { height: 280px; margin-bottom: 1rem; }
    }

    /* Enemy Area (Top Right) */
    .enemy-platform-wrap {
      position: absolute;
      top: 5px;
      right: 5px;
      width: 55%;
      max-width: 280px;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }
    .poke-status-card {
      background: rgba(15, 23, 42, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 0.4rem 0.6rem;
      width: 100%;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      backdrop-filter: blur(10px);
    }
    @media (min-width: 640px) {
      .poke-status-card { padding: 0.55rem 0.85rem; border-width: 2px; border-radius: 16px; }
    }
    .poke-status-card.enemy {
      border-color: #fca5a5;
      background: rgba(69, 10, 10, 0.7);
    }
    .poke-status-card.player {
      border-color: #fde047;
      background: rgba(30, 27, 75, 0.85);
    }
    .poke-name-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
      margin-bottom: 0.2rem;
    }
    .poke-name {
      font-size: 0.75rem;
      font-weight: 900;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 0.2rem;
    }
    .poke-lv-badge {
      font-size: 0.65rem;
      font-weight: 800;
      color: var(--poke-yellow);
      background: rgba(0,0,0,0.5);
      padding: 0.1rem 0.3rem;
      border-radius: 4px;
      border: 1px solid rgba(255, 203, 5, 0.3);
    }
    .poke-hp-wrap {
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }
    .poke-hp-label {
      font-size: 0.6rem;
      font-weight: 900;
      color: #fbbf24;
      letter-spacing: 0.05em;
    }
    .poke-hp-track {
      flex: 1;
      height: 6px;
      background: #1e293b;
      border-radius: 99px;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.15);
      position: relative;
    }
    .poke-hp-fill {
      height: 100%;
      width: 100%;
      background: #10b981;
      border-radius: 99px;
      transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s;
    }
    .poke-exp-track {
      width: 100%;
      height: 3px;
      background: #1e293b;
      border-radius: 99px;
      overflow: hidden;
      margin-top: 0.2rem;
    }
    .poke-exp-fill {
      height: 100%;
      width: 40%;
      background: linear-gradient(90deg, #38bdf8, #818cf8);
      border-radius: 99px;
      transition: width 0.4s ease;
    }

    .enemy-sprite-container {
      margin-top: 0.2rem;
      position: relative;
      width: 95px;
      height: 95px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
    }
    @media (min-width: 640px) {
      .enemy-sprite-container { width: 155px; height: 135px; margin-top: 0.4rem; }
    }
    .enemy-pedestal {
      position: absolute;
      bottom: 2px;
      width: 90px;
      height: 22px;
      background: radial-gradient(ellipse, rgba(16, 185, 129, 0.75) 0%, rgba(21, 128, 61, 0.35) 50%, transparent 75%);
      border-radius: 50%;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
      z-index: 1;
    }
    @media (min-width: 640px) {
      .enemy-pedestal { width: 135px; height: 30px; }
    }
    .enemy-sprite {
      position: relative;
      z-index: 2;
      width: 80px;
      height: 80px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      animation: enemyHover 2.5s ease-in-out infinite alternate;
      transition: all 0.25s ease;
    }
    @media (min-width: 640px) {
      .enemy-sprite { width: 130px; height: 130px; }
    }
    @keyframes enemyHover {
      0% { transform: translateY(0); }
      100% { transform: translateY(-8px); }
    }

    /* High-Fidelity Pixel Sprite Rendering */
    .poke-pixel-sprite {
      width: 100%;
      height: 100%;
      max-height: 100%;
      object-fit: contain;
      image-rendering: -webkit-optimize-contrast;
      image-rendering: crisp-edges;
      image-rendering: pixelated;
      filter: drop-shadow(0 8px 14px rgba(0, 0, 0, 0.55));
      pointer-events: none;
      user-select: none;
      transition: transform 0.25s ease, filter 0.25s ease;
    }

    .poke-skill-effect {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 170px;
      height: 170px;
      z-index: 10;
      pointer-events: none;
      opacity: 0;
      border-radius: 50%;
    }
    .poke-skill-effect::before,
    .poke-skill-effect::after {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      border-radius: inherit;
    }

    /* 1. THUNDERBOLT (สายฟ้าฟาด ⚡) - Smooth Electric Lightning Burst & Arc Ring */
    .skill-thunder {
      background: radial-gradient(circle, rgba(254, 240, 138, 0.95) 15%, rgba(234, 179, 8, 0.75) 45%, rgba(56, 189, 248, 0.3) 70%, transparent 80%);
      box-shadow: 0 0 50px rgba(250, 204, 21, 0.9), 0 0 90px rgba(234, 179, 8, 0.6);
      animation: thunderFlash 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    .skill-thunder::before {
      background: linear-gradient(180deg, #ffffff 0%, #fef08a 40%, #eab308 70%, transparent 100%);
      clip-path: polygon(48% 0%, 58% 30%, 45% 36%, 60% 64%, 42% 68%, 56% 100%, 46% 100%, 36% 70%, 48% 66%, 35% 38%, 47% 32%, 38% 0%);
      filter: drop-shadow(0 0 12px #ffffff) drop-shadow(0 0 25px #facc15);
      animation: thunderBolt 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .skill-thunder::after {
      border: 3px solid rgba(254, 240, 138, 0.9);
      box-shadow: 0 0 25px rgba(234, 179, 8, 0.8), inset 0 0 25px rgba(56, 189, 248, 0.5);
      animation: thunderShockwave 0.85s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    @keyframes thunderFlash {
      0% { opacity: 0; transform: translate(-50%, -50%) scale(0.4); filter: brightness(2); }
      15% { opacity: 1; transform: translate(-50%, -50%) scale(1.15); filter: brightness(2.5); }
      40% { opacity: 0.95; transform: translate(-50%, -50%) scale(1.3); filter: brightness(1.8); }
      70% { opacity: 0.6; transform: translate(-50%, -50%) scale(1.4); filter: brightness(1.2); }
      100% { opacity: 0; transform: translate(-50%, -50%) scale(1.5); filter: brightness(1); }
    }
    @keyframes thunderBolt {
      0% { opacity: 0; transform: scaleY(0.1) translateY(-100%); }
      20% { opacity: 1; transform: scaleY(1.1) translateY(0); }
      45% { opacity: 1; transform: scale(1.15) rotate(5deg); }
      70% { opacity: 0.7; transform: scale(1.05) rotate(-3deg); }
      100% { opacity: 0; transform: scale(0.95); }
    }
    @keyframes thunderShockwave {
      0% { opacity: 0; transform: scale(0.3); }
      25% { opacity: 1; transform: scale(0.8); }
      60% { opacity: 0.8; transform: scale(1.35); }
      100% { opacity: 0; transform: scale(1.7); }
    }

    /* 2. IRON TAIL (หางเหล็กกล้า 💥) - Dynamic Steel Crescent Slash & Impact Sparks */
    .skill-iron-tail {
      background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 10%, rgba(203, 213, 225, 0.5) 40%, transparent 70%);
      animation: ironTailImpact 0.85s cubic-bezier(0.25, 1, 0.5, 1) forwards;
    }
    .skill-iron-tail::before {
      background: linear-gradient(135deg, transparent 35%, rgba(255, 255, 255, 0.95) 48%, #ffffff 50%, rgba(226, 232, 240, 0.95) 52%, transparent 65%);
      box-shadow: 0 0 35px rgba(255, 255, 255, 0.9), 0 0 60px rgba(148, 163, 184, 0.8);
      filter: drop-shadow(0 0 15px #ffffff);
      animation: ironTailSlash 0.85s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
    }
    .skill-iron-tail::after {
      border: 3px solid rgba(255, 255, 255, 0.9);
      box-shadow: 0 0 30px rgba(203, 213, 225, 0.9), inset 0 0 20px rgba(255, 255, 255, 0.6);
      animation: ironTailRing 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes ironTailImpact {
      0% { opacity: 0; transform: translate(-50%, -50%) scale(0.5); }
      25% { opacity: 1; transform: translate(-50%, -50%) scale(1.2); }
      60% { opacity: 0.7; transform: translate(-50%, -50%) scale(1.4); }
      100% { opacity: 0; transform: translate(-50%, -50%) scale(1.6); }
    }
    @keyframes ironTailSlash {
      0% { opacity: 0; transform: translate(-80px, 80px) rotate(-60deg) scale(0.4); }
      30% { opacity: 1; transform: translate(0, 0) rotate(15deg) scale(1.3); }
      65% { opacity: 0.85; transform: translate(40px, -40px) rotate(45deg) scale(1.1); }
      100% { opacity: 0; transform: translate(70px, -70px) rotate(60deg) scale(0.9); }
    }
    @keyframes ironTailRing {
      0% { opacity: 0; transform: scale(0.2) rotate(0deg); }
      30% { opacity: 1; transform: scale(0.9) rotate(30deg); }
      70% { opacity: 0.6; transform: scale(1.35) rotate(60deg); }
      100% { opacity: 0; transform: scale(1.65) rotate(90deg); }
    }

    /* 3. ELECTRO BALL (บอลประจุไฟฟ้า 🔮) - High-Tech Plasma Orb & Supernova Wave */
    .skill-electro-ball {
      background: radial-gradient(circle, #ffffff 15%, #38bdf8 45%, #0284c7 70%, transparent 85%);
      box-shadow: 0 0 45px #38bdf8, 0 0 85px #0284c7, inset 0 0 25px #ffffff;
      animation: electroBallTravel 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    .skill-electro-ball::before {
      border: 3px dashed rgba(224, 242, 254, 0.95);
      box-shadow: 0 0 25px #38bdf8;
      animation: electroBallOrbit 0.85s linear forwards;
    }
    .skill-electro-ball::after {
      border: 3px solid rgba(56, 189, 248, 0.9);
      box-shadow: 0 0 35px #0ea5e9, inset 0 0 30px #bae6fd;
      animation: electroBallPulseRing 0.85s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    @keyframes electroBallTravel {
      0% { opacity: 0; transform: translate(-140%, 80%) scale(0.3); }
      35% { opacity: 1; transform: translate(-50%, -50%) scale(1.25); filter: brightness(1.8); }
      60% { opacity: 0.9; transform: translate(-50%, -50%) scale(1.4); filter: brightness(1.4); }
      100% { opacity: 0; transform: translate(-50%, -50%) scale(1.8); filter: brightness(1); }
    }
    @keyframes electroBallOrbit {
      0% { opacity: 0; transform: scale(0.5) rotate(0deg); }
      35% { opacity: 1; transform: scale(1.1) rotate(180deg); }
      70% { opacity: 0.7; transform: scale(1.35) rotate(300deg); }
      100% { opacity: 0; transform: scale(1.6) rotate(360deg); }
    }
    @keyframes electroBallPulseRing {
      0% { opacity: 0; transform: scale(0.2); }
      40% { opacity: 1; transform: scale(1); }
      75% { opacity: 0.6; transform: scale(1.45); }
      100% { opacity: 0; transform: scale(1.8); }
    }

    /* 4. QUICK ATTACK (พุ่งจู่โจมไว 💨) - Supersonic Sonic Bloom & Speed Slashes */
    .skill-quick-attack {
      background: radial-gradient(circle, rgba(255, 255, 255, 0.85) 10%, rgba(224, 242, 254, 0.5) 45%, transparent 75%);
      animation: quickAttackSonic 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .skill-quick-attack::before {
      background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.9) 25%, #ffffff 50%, rgba(255, 255, 255, 0.9) 75%, transparent 100%);
      clip-path: polygon(0% 45%, 100% 45%, 100% 55%, 0% 55%);
      filter: drop-shadow(0 0 15px #ffffff) drop-shadow(0 0 30px #38bdf8);
      animation: quickAttackSlice 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .skill-quick-attack::after {
      border: 3px solid rgba(255, 255, 255, 0.95);
      box-shadow: 0 0 35px rgba(255, 255, 255, 0.8), 0 0 60px rgba(56, 189, 248, 0.6);
      animation: quickAttackCone 0.85s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes quickAttackSonic {
      0% { opacity: 0; transform: translate(-50%, -50%) scale(0.3); }
      20% { opacity: 1; transform: translate(-50%, -50%) scale(1.15); filter: blur(0px); }
      55% { opacity: 0.85; transform: translate(-50%, -50%) scale(1.3); filter: blur(2px); }
      100% { opacity: 0; transform: translate(-50%, -50%) scale(1.6); filter: blur(4px); }
    }
    @keyframes quickAttackSlice {
      0% { opacity: 0; transform: translateX(-120%) scaleX(0.2) skewX(-40deg); }
      25% { opacity: 1; transform: translateX(0%) scaleX(1.4) skewX(-25deg); }
      55% { opacity: 0.9; transform: translateX(50%) scaleX(1.2) skewX(-15deg); }
      100% { opacity: 0; transform: translateX(120%) scaleX(0.5); }
    }
    @keyframes quickAttackCone {
      0% { opacity: 0; transform: scale(0.2) skewX(-20deg); }
      25% { opacity: 1; transform: scale(0.95) skewX(0deg); }
      60% { opacity: 0.7; transform: scale(1.4) skewX(10deg); }
      100% { opacity: 0; transform: scale(1.75); }
    }

    /* Player Area (Bottom Left) */
    .player-platform-wrap {
      position: absolute;
      bottom: 0;
      left: 10px;
      width: 55%;
      max-width: 320px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    .player-sprite-container {
      position: relative;
      width: 95px;
      height: 95px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      margin-bottom: 0.1rem;
    }
    @media (min-width: 640px) {
      .player-sprite-container { width: 155px; height: 135px; margin-bottom: 0.3rem; }
    }
    .player-pedestal {
      position: absolute;
      bottom: 2px;
      width: 95px;
      height: 24px;
      background: radial-gradient(ellipse, rgba(34, 197, 94, 0.8) 0%, rgba(22, 101, 52, 0.4) 50%, transparent 75%);
      border-radius: 50%;
      box-shadow: 0 4px 15px rgba(34, 197, 94, 0.35);
      z-index: 1;
    }
    @media (min-width: 640px) {
      .player-pedestal { width: 140px; height: 32px; }
    }
    .player-sprite {
      position: relative;
      z-index: 2;
      width: 85px;
      height: 85px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      animation: playerBounce 2s ease-in-out infinite alternate;
      transition: all 0.25s ease;
    }
    @media (min-width: 640px) {
      .player-sprite { width: 135px; height: 135px; }
    }
    @keyframes playerBounce {
      0% { transform: scale(1) translateY(0); }
      100% { transform: scale(1.03) translateY(-4px); }
    }

    /* Battle Animations */
    .player-attack-anim,
    .player-attack-lunge {
      animation: playerLunge 0.85s cubic-bezier(0.25, 1, 0.5, 1) !important;
    }
    @keyframes playerLunge {
      0% { transform: translate(0, 0); }
      45% { transform: translate(65px, -35px) scale(1.18); }
      70% { transform: translate(30px, -15px) scale(1.08); }
      100% { transform: translate(0, 0) scale(1); }
    }
    .enemy-hit-anim,
    .enemy-take-hit {
      animation: enemyHit 0.85s ease-in-out !important;
    }
    @keyframes enemyHit {
      0%, 100% { transform: translate(0, 0); filter: brightness(1); }
      15% { transform: translate(10px, -6px); filter: brightness(1.8) drop-shadow(0 0 18px rgba(239, 68, 68, 0.8)); }
      30% { transform: translate(-10px, 6px); filter: brightness(1.6) drop-shadow(0 0 14px rgba(239, 68, 68, 0.7)); }
      50% { transform: translate(6px, -4px); filter: brightness(1.3); }
      70% { transform: translate(-4px, 3px); filter: brightness(1.1); }
      85% { transform: translate(2px, -1px); filter: brightness(1); }
    }
    .enemy-fainted {
      animation: enemyFaint 0.85s cubic-bezier(0.4, 0, 0.2, 1) forwards !important;
    }
    @keyframes enemyFaint {
      0% { opacity: 1; transform: translateY(0) scale(1); }
      50% { opacity: 0.6; transform: translateY(20px) scale(0.85); }
      100% { opacity: 0; transform: translateY(45px) scale(0.65); filter: blur(2px); }
    }
    .screen-shake {
      animation: arenaShake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }
    @keyframes arenaShake {
      10%, 90% { transform: translate3d(-2px, 0, 0); }
      20%, 80% { transform: translate3d(3px, 0, 0); }
      30%, 50%, 70% { transform: translate3d(-5px, 0, 0); }
      40%, 60% { transform: translate3d(5px, 0, 0); }
    }

    /* Enemy Attack Animations (Enemy lunges down-left toward Pikachu) */
    .enemy-attack-anim {
      animation: enemyLunge 0.75s cubic-bezier(0.25, 1, 0.5, 1) !important;
    }
    @keyframes enemyLunge {
      0% { transform: translate(0, 0); }
      45% { transform: translate(-65px, 35px) scale(1.18); }
      70% { transform: translate(-30px, 15px) scale(1.08); }
      100% { transform: translate(0, 0) scale(1); }
    }

    /* Player Hit Animation (Pikachu takes hit, flashes red, shakes) */
    .player-hit-anim {
      animation: playerHit 0.75s ease-in-out !important;
    }
    @keyframes playerHit {
      0%, 100% { transform: translate(0, 0); filter: brightness(1); }
      15% { transform: translate(-10px, 6px); filter: brightness(1.9) drop-shadow(0 0 20px rgba(239, 68, 68, 0.95)); }
      30% { transform: translate(10px, -6px); filter: brightness(1.7) drop-shadow(0 0 16px rgba(239, 68, 68, 0.8)); }
      50% { transform: translate(-6px, 4px); filter: brightness(1.4); }
      70% { transform: translate(4px, -3px); filter: brightness(1.15); }
      85% { transform: translate(-2px, 1px); filter: brightness(1); }
    }

    /* Enemy Skill VFX: Dark Scratch / Claws */
    .skill-enemy-scratch {
      background: radial-gradient(circle, rgba(239, 68, 68, 0.9) 15%, rgba(185, 28, 28, 0.6) 45%, transparent 75%);
      box-shadow: 0 0 45px rgba(239, 68, 68, 0.9), 0 0 75px rgba(185, 28, 28, 0.7);
      animation: enemyScratchImpact 0.75s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
    }
    .skill-enemy-scratch::before {
      background: linear-gradient(135deg, transparent 40%, #ff4d4d 48%, #ffffff 50%, #ff4d4d 52%, transparent 60%);
      clip-path: polygon(0% 40%, 100% 40%, 100% 60%, 0% 60%);
      filter: drop-shadow(0 0 15px #ff4d4d) drop-shadow(0 0 25px #dc2626);
      animation: enemyScratchCut 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .skill-enemy-scratch::after {
      border: 3px solid rgba(254, 202, 202, 0.9);
      box-shadow: 0 0 25px rgba(239, 68, 68, 0.8), inset 0 0 20px rgba(220, 38, 38, 0.6);
      animation: enemyScratchRing 0.75s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    @keyframes enemyScratchImpact {
      0% { opacity: 0; transform: translate(-50%, -50%) scale(0.3); }
      25% { opacity: 1; transform: translate(-50%, -50%) scale(1.2); }
      65% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.35); }
      100% { opacity: 0; transform: translate(-50%, -50%) scale(1.6); }
    }
    @keyframes enemyScratchCut {
      0% { opacity: 0; transform: translate(60px, -60px) rotate(45deg) scale(0.4); }
      30% { opacity: 1; transform: translate(0, 0) rotate(15deg) scale(1.3); }
      65% { opacity: 0.85; transform: translate(-30px, 30px) rotate(-15deg) scale(1.1); }
      100% { opacity: 0; transform: translate(-60px, 60px) rotate(-35deg) scale(0.8); }
    }
    @keyframes enemyScratchRing {
      0% { opacity: 0; transform: scale(0.2); }
      30% { opacity: 1; transform: scale(0.95); }
      70% { opacity: 0.6; transform: scale(1.4); }
      100% { opacity: 0; transform: scale(1.7); }
    }

    /* Floating Damage Numbers Popup */
    .poke-damage-popup {
      position: absolute;
      top: 15%;
      left: 50%;
      transform: translateX(-50%);
      color: #ef4444;
      font-size: 1.35rem;
      font-weight: 900;
      text-shadow: 0 0 4px #000, 0 0 12px rgba(239, 68, 68, 0.95);
      pointer-events: none;
      z-index: 50;
      white-space: nowrap;
      animation: damageFloat 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .poke-damage-popup.player-critical {
      color: #facc15;
      font-size: 1.45rem;
      text-shadow: 0 0 4px #000, 0 0 14px rgba(250, 204, 21, 0.95);
    }
    @keyframes damageFloat {
      0% { opacity: 0; transform: translate(-50%, 15px) scale(0.6); }
      20% { opacity: 1; transform: translate(-50%, -10px) scale(1.25); }
      65% { opacity: 1; transform: translate(-50%, -30px) scale(1); }
      100% { opacity: 0; transform: translate(-50%, -50px) scale(0.8); }
    }

    /* BATTLE BOX: Question & Move Commands */
    .poke-battle-box {
      background: #0f172a;
      border: 4px solid #38bdf8;
      border-radius: 22px;
      overflow: hidden;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.7);
    }
    .poke-dialog-screen {
      background: #020617;
      border-bottom: 3px solid #1e293b;
      padding: 0.6rem 0.75rem; /* Reduced for mobile */
      min-height: 60px; /* Reduced */
    }
    .poke-dialog-title {
      font-size: 0.7rem; /* Reduced */
      font-weight: 800;
      color: #38bdf8;
      display: flex;
      align-items: center;
      gap: 0.4rem;
      margin-bottom: 0.3rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .poke-dialog-text {
      font-size: 0.9rem; /* Reduced */
      font-weight: 800;
      color: #fff;
      line-height: 1.4;
      white-space: pre-wrap;
      word-break: break-word;
    }
    @media (min-width: 640px) {
      .poke-dialog-screen { padding: 1rem 1.25rem; min-height: 80px; }
      .poke-dialog-title { font-size: 0.75rem; margin-bottom: 0.4rem; }
      .poke-dialog-text { font-size: 1.2rem; line-height: 1.6; }
    }

    .poke-battle-narrator {
      font-size: 0.75rem; /* Reduced */
      font-weight: 800;
      color: #fde047;
      background: rgba(253, 224, 71, 0.1);
      border-left: 3px solid #fde047;
      padding: 0.3rem 0.6rem;
      border-radius: 6px;
      margin-top: 0.4rem;
      display: none;
    }
    @media (min-width: 640px) {
      .poke-battle-narrator { font-size: 0.85rem; padding: 0.4rem 0.75rem; margin-top: 0.6rem; }
    }

    /* Move Commands (ช้อยส์คำตอบคือท่าไม้ตาย) */
    .poke-moves-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 0.4rem; /* Reduced */
      padding: 0.5rem 0.75rem; /* Reduced */
      background: #090d16;
    }
    @media (min-width: 640px) {
      .poke-moves-grid {
        grid-template-columns: 1fr 1fr;
        gap: 0.6rem;
        padding: 0.85rem 1rem;
      }
    }

    .poke-move-card {
      background: #1e293b;
      border: 2px solid #334155;
      border-radius: 12px;
      padding: 0.5rem 0.75rem; /* Reduced */
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.5rem; /* Reduced */
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
    }
    @media (min-width: 640px) {
      .poke-move-card { padding: 0.85rem 1rem; border-radius: 14px; gap: 0.75rem; }
    }
    .poke-move-card:hover {
      background: #334155;
      border-color: #ffcb05;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(255, 203, 5, 0.2);
    }
    .poke-move-card:active {
      transform: scale(0.98);
    }
    .poke-move-card.selected {
      background: rgba(255, 203, 5, 0.18);
      border-color: #ffcb05;
      box-shadow: 0 0 0 2px #ffcb05, 0 8px 24px rgba(255, 203, 5, 0.3);
    }
    .poke-move-letter {
      width: 24px;
      height: 24px;
      border-radius: 6px;
      background: rgba(0,0,0,0.4);
      border: 1px solid rgba(255,255,255,0.15);
      color: #94a3b8;
      font-size: 0.75rem;
      font-weight: 900;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    @media (min-width: 640px) {
      .poke-move-letter { width: 28px; height: 28px; border-radius: 8px; font-size: 0.8rem; }
    }
    .poke-move-card.selected .poke-move-letter {
      background: #ffcb05;
      color: #0f172a;
      border-color: #ffcb05;
    }
    .poke-move-info {
      flex: 1;
      min-width: 0;
    }
    .poke-move-tag {
      font-size: 0.6rem;
      font-weight: 900;
      color: #38bdf8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 0.1rem;
      display: block;
    }
    @media (min-width: 640px) {
      .poke-move-tag { font-size: 0.65rem; margin-bottom: 0.15rem; }
    }
    .poke-move-name {
      font-size: 0.85rem;
      font-weight: 800;
      color: #fff;
      line-height: 1.3;
      white-space: pre-wrap;
      word-break: break-word;
    }
    @media (min-width: 640px) {
      .poke-move-name { font-size: 0.95rem; line-height: 1.4; }
    }

    .poke-battle-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem; /* Reduced */
      padding: 0.6rem 0.75rem; /* Reduced */
      background: #0f172a;
      border-top: 2px solid #1e293b;
    }
    @media (min-width: 640px) {
      .poke-battle-actions { gap: 0.75rem; padding: 0.85rem 1rem; }
    }
    .btn-poke-attack {
      flex: 1;
      background: linear-gradient(135deg, #eab308, #ca8a04);
      color: #0f172a;
      font-weight: 900;
      font-size: 0.95rem; /* Reduced */
      padding: 0.6rem 1rem; /* Reduced */
      border-radius: 12px;
      border: 2px solid #fef08a;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(234, 179, 8, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }
    .btn-poke-attack:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(234, 179, 8, 0.6);
    }
    .btn-poke-attack:disabled {
      opacity: 0.35;
      cursor: not-allowed;
      filter: grayscale(1);
    }
    .btn-poke-map-back {
      background: #1e293b;
      color: #cbd5e1;
      border: 2px solid #334155;
      font-weight: 800;
      font-size: 0.85rem;
      padding: 0.85rem 1.25rem;
      border-radius: 14px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .btn-poke-map-back:hover {
      background: #334155;
      color: #fff;
    }

    /* Coin Quest gets its own compact, game-first frame. */
    body[data-exam-mode="escape_room"] {
      background: #080914;
    }
    body[data-exam-mode="escape_room"] .escape-room-hud {
      backdrop-filter: blur(16px);
    }
    body[data-exam-mode="escape_room"] .escape-room-stage {
      border-top-color: rgba(196, 181, 253, .14);
      box-shadow: 0 30px 90px rgba(5, 3, 18, .65), inset 0 1px rgba(255,255,255,.05);
    }
    .escape-room-playfield {
      display: grid;
      grid-template-columns: minmax(0, 1fr) 225px;
      align-items: stretch;
      background: radial-gradient(ellipse at 35% 45%, rgba(109,40,217,.12), transparent 55%), #090b15;
    }
    #escapeRoomCanvas {
      display: block;
      width: 100%;
      height: auto;
      aspect-ratio: 12 / 7;
      background: #0a0c17;
      image-rendering: pixelated;
      touch-action: none;
    }
    .escape-room-controls {
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 1.15rem;
      padding: 1.25rem;
      border-left: 1px solid rgba(196,181,253,.14);
      background: linear-gradient(155deg, rgba(23,19,41,.96), rgba(11,14,26,.96));
    }
    .escape-room-controls-title {
      display: flex;
      align-items: center;
      gap: .65rem;
    }
    .escape-room-controls-hint {
      color: #9ca3af;
      font-size: .68rem;
      line-height: 1.7;
    }
    .escape-room-controls-hint strong {
      color: #ddd6fe;
    }
    .escape-room-action-key {
      color: #f0abfc !important;
      background: linear-gradient(145deg, #7e22ce, #581c87) !important;
      border-color: rgba(240,171,252,.45) !important;
      font-weight: 950;
    }
    #escapeRoomPrompt {
      min-height: 2rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: color .2s, background .2s, transform .2s;
    }
    .escape-room-stage .dpad-btn {
      width: 2.65rem;
      height: 2.65rem;
      border-radius: .85rem;
      border: 1px solid rgba(196,181,253,.22);
      background: rgba(255,255,255,.06);
      color: #ede9fe;
      box-shadow: inset 0 1px rgba(255,255,255,.08), 0 5px 14px rgba(0,0,0,.2);
      transition: transform .12s, background .12s;
    }
    .escape-room-stage .dpad-btn:active {
      transform: scale(.9);
      background: rgba(168,85,247,.35);
    }
    .escape-room-stage .btn-quick-encounter {
      min-width: min(100%, 250px);
      border: 1px solid rgba(216,180,254,.55);
      background: linear-gradient(135deg, #a855f7, #6d28d9);
      color: white;
      box-shadow: 0 8px 26px rgba(126,34,206,.35);
    }
    @keyframes escapeRoomEnter {
      0% { opacity: 0; transform: translateY(14px) scale(.985); filter: blur(3px); }
      100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    }
    .escape-room-enter {
      animation: escapeRoomEnter .42s cubic-bezier(.2,.75,.25,1) both;
    }
    .escape-room-step {
      border: 1px solid rgba(196,181,253,.13);
      border-radius: 999px;
      padding: .4rem .75rem;
      color: #827a92;
      background: rgba(255,255,255,.025);
      transition: all .25s;
    }
    .escape-room-step.active {
      color: #fff;
      border-color: rgba(232,121,249,.55);
      background: linear-gradient(120deg, rgba(168,85,247,.28), rgba(217,70,239,.12));
      box-shadow: 0 0 18px rgba(168,85,247,.18);
    }
    .escape-room-step.done {
      color: #c4b5fd;
      border-color: rgba(167,139,250,.35);
      background: rgba(124,58,237,.12);
    }
    .escape-room-encounter {
      display: flex;
      align-items: center;
      gap: .9rem;
      padding: 1rem 1.2rem;
      border: 1px solid rgba(216,180,254,.3);
      border-radius: 1.25rem;
      background: radial-gradient(circle at 0 50%, rgba(168,85,247,.2), transparent 45%), linear-gradient(120deg, #171329, #111827);
      box-shadow: 0 16px 40px rgba(20,8,40,.35);
    }
    .escape-room-encounter-icon {
      width: 3rem;
      height: 3rem;
      flex: 0 0 3rem;
      display: grid;
      place-items: center;
      border-radius: 1rem;
      background: linear-gradient(145deg, rgba(217,70,239,.25), rgba(124,58,237,.2));
      border: 1px solid rgba(232,121,249,.35);
      font-size: 1.35rem;
      box-shadow: 0 0 24px rgba(217,70,239,.18);
    }
    body[data-exam-mode="escape_room"] .question-area {
      border: 1px solid rgba(196,181,253,.2);
      border-radius: 1.5rem;
      background: linear-gradient(145deg, rgba(25,20,43,.97), rgba(9,12,24,.98));
      box-shadow: 0 24px 65px rgba(5,3,18,.45);
    }
    body[data-exam-mode="escape_room"] .progress-section {
      border-color: rgba(196,181,253,.2);
    }
    @media (max-width: 640px) {
      body[data-exam-mode="escape_room"] .escape-room-hud {
        margin-top: .5rem;
        padding: .75rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomHud h2 {
        font-size: .95rem;
      }
      body[data-exam-mode="escape_room"] .escape-room-stage {
        width: 100%;
        margin-bottom: 1rem;
        border-radius: 0 0 1.25rem 1.25rem;
      }
      .escape-room-stage .dpad-btn {
        width: 2.4rem;
        height: 2.4rem;
      }
      .escape-room-playfield {
        grid-template-columns: minmax(0, 1fr);
      }
      .escape-room-controls {
        display: grid;
        grid-template-columns: 112px minmax(0, 1fr);
        grid-template-rows: auto auto auto;
        align-items: center;
        gap: .5rem .85rem;
        padding: .75rem;
        border-left: 0;
        border-top: 1px solid rgba(196,181,253,.14);
      }
      .escape-room-controls-title {
        grid-column: 2;
        grid-row: 1;
      }
      .escape-room-dpad {
        grid-column: 1;
        grid-row: 1 / span 3;
      }
      .escape-room-controls-hint {
        grid-column: 2;
        grid-row: 2;
      }
      .escape-room-controls .btn-quick-encounter {
        grid-column: 2;
        grid-row: 3;
        width: 100%;
        min-width: 0;
        padding: .65rem .8rem;
      }
      .escape-room-encounter {
        width: 94%;
        padding: .75rem;
      }
      .escape-room-encounter-icon {
        width: 2.6rem;
        height: 2.6rem;
        flex-basis: 2.6rem;
      }
    }

    /* Coin Quest stays on one exam screen: platform view and question panel remain together. */
    body[data-exam-mode="escape_room"] {
      height: 100vh;
      height: 100dvh;
      overflow: hidden;
      overscroll-behavior: none;
      background: #091322;
    }
    body[data-exam-mode="escape_room"] .top-bar {
      position: absolute;
      inset: 0 0 auto;
      z-index: 120;
      padding: .45rem .8rem;
    }
    body[data-exam-mode="escape_room"] .top-bar-inner {
      max-width: none;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud {
      position: fixed;
      z-index: 110;
      top: 58px;
      left: 1%;
      width: 98%;
      margin: 0;
      height: 76px;
      padding: .4rem .85rem;
      border: 1px solid rgba(196,181,253,.3);
      border-radius: 1rem;
      overflow: hidden;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud > div:nth-of-type(2),
    body[data-exam-mode="escape_room"] #escapeRoomRoomMessage {
      display: none !important;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud > div:first-child {
      gap: .5rem;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud h2 {
      font-size: .9rem;
      line-height: 1.1;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud .h-12 {
      width: 2.5rem;
      height: 2.5rem;
      font-size: 1.25rem;
      border-radius: .8rem;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud .mt-3.h-2 {
      margin-top: .35rem;
      height: 5px;
    }
    body[data-exam-mode="escape_room"] #escapeRoomHud .mt-2.flex {
      margin-top: .2rem;
    }
    body[data-exam-mode="escape_room"] #escapeRoomMapView {
      position: fixed;
      z-index: 40;
      top: 144px;
      bottom: 10px;
      left: 1%;
      width: 48.5%;
      height: auto;
      margin: 0;
      border: 1px solid rgba(196,181,253,.3);
      border-radius: 1.25rem;
      display: flex;
      flex-direction: column;
    }
    body[data-exam-mode="escape_room"] #escapeRoomMapView > div:first-child {
      min-height: 54px;
      padding: .45rem .7rem;
    }
    body[data-exam-mode="escape_room"] #escapeRoomMapView > div:first-child p:last-child {
      font-size: .7rem;
      line-height: 1.2;
    }
    body[data-exam-mode="escape_room"] .escape-room-playfield {
      flex: 1;
      min-height: 0;
      grid-template-columns: minmax(0, 1fr) 108px;
    }
    body[data-exam-mode="escape_room"] #escapeRoomCanvas {
      width: 100%;
      height: 100%;
      min-height: 0;
      aspect-ratio: auto;
      object-fit: contain;
    }
    body[data-exam-mode="escape_room"] .escape-room-controls {
      gap: .45rem;
      padding: .45rem;
      border-left: 1px solid rgba(196,181,253,.14);
    }
    body[data-exam-mode="escape_room"] .escape-room-controls-title,
    body[data-exam-mode="escape_room"] .escape-room-controls-hint {
      display: none;
    }
    body[data-exam-mode="escape_room"] .escape-room-dpad {
      flex-wrap: wrap;
      gap: .35rem;
    }
    body[data-exam-mode="escape_room"] .escape-room-stage .dpad-btn {
      width: 2.45rem;
      height: 2.45rem;
    }
    body[data-exam-mode="escape_room"] .escape-room-controls .btn-quick-encounter {
      min-width: 0;
      width: 100%;
      padding: .55rem .35rem;
      font-size: .7rem;
    }
    body[data-exam-mode="escape_room"] #classicExamView.escape-room-question-panel {
      position: fixed;
      z-index: 40;
      top: 144px;
      right: 1%;
      bottom: 10px;
      left: 50.5%;
      width: 48.5%;
      height: auto;
      margin: 0;
      padding: .6rem;
      display: flex;
      flex-direction: column;
      gap: .55rem;
      overflow: hidden;
    }
    body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter {
      flex: 0 0 auto;
      width: 100%;
      min-height: 66px;
      margin: 0;
      padding: .6rem .75rem;
      gap: .65rem;
    }
    body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter-icon {
      width: 2.5rem;
      height: 2.5rem;
      flex-basis: 2.5rem;
      font-size: 1.15rem;
      border-radius: .8rem;
    }
    body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter h2 {
      font-size: .95rem;
      line-height: 1.15;
    }
    body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter p:last-child {
      font-size: .68rem;
      line-height: 1.2;
    }
    body[data-exam-mode="escape_room"] #classicExamView .progress-section {
      display: none;
    }
    body[data-exam-mode="escape_room"] .escape-room-question-empty {
      flex: 1;
      min-height: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: .65rem;
      padding: 1.5rem;
      text-align: center;
      border: 1px dashed rgba(196,181,253,.32);
      border-radius: 1.25rem;
      background: radial-gradient(circle at 50% 0, rgba(168,85,247,.16), transparent 65%), rgba(15,18,32,.9);
      color: #e9d5ff;
    }
    body[data-exam-mode="escape_room"] .escape-room-empty-coin {
      font-size: 2.6rem;
      filter: drop-shadow(0 0 16px rgba(250,204,21,.45));
    }
    body[data-exam-mode="escape_room"] .escape-room-question-empty h3 {
      font-weight: 900;
      font-size: 1.05rem;
    }
    body[data-exam-mode="escape_room"] .escape-room-question-empty p {
      max-width: 28rem;
      color: #b8b5c7;
      font-size: .8rem;
      line-height: 1.55;
    }
    body[data-exam-mode="escape_room"] #classicExamView.escape-room-question-open .escape-room-question-empty {
      display: none;
    }
    body[data-exam-mode="escape_room"] #classicExamView .question-area {
      flex: 1;
      min-height: 0;
      max-width: none;
      width: 100%;
      margin: 0;
      padding: .75rem;
      overflow: auto;
      overscroll-behavior: contain;
      display: none;
    }
    body[data-exam-mode="escape_room"] #classicExamView.escape-room-question-open .question-area {
      display: block;
    }
    body[data-exam-mode="escape_room"] #classicExamView .question-area > div[style*="margin-top"] {
      margin-top: .65rem !important;
      padding-top: .55rem !important;
    }
    body[data-exam-mode="escape_room"] #classicExamView .q-text {
      font-size: 1rem;
      line-height: 1.45;
      margin-bottom: .55rem;
    }
    body[data-exam-mode="escape_room"] #classicExamView .q-image {
      max-height: 130px;
      object-fit: contain;
    }
    @media (max-width: 760px) {
      body[data-exam-mode="escape_room"] #escapeRoomHud {
        top: 54px;
        height: 68px;
        padding: .35rem .6rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomHud .text-right {
        padding: .35rem .55rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomMapView {
        top: 130px;
        left: 1%;
        right: 1%;
        bottom: auto;
        width: auto;
        height: clamp(170px, 29dvh, 255px);
        border-radius: 1rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomMapView > div:first-child {
        min-height: 40px;
        padding: .35rem .55rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomMapView > div:first-child p:first-child {
        font-size: .55rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomMapView > div:first-child p:last-child {
        font-size: .62rem;
      }
      body[data-exam-mode="escape_room"] #escapeRoomPrompt {
        min-height: 1.5rem;
        padding: .25rem .45rem;
        font-size: .58rem;
      }
      body[data-exam-mode="escape_room"] .escape-room-playfield {
        position: relative;
        display: block;
        height: calc(100% - 40px);
      }
      body[data-exam-mode="escape_room"] #escapeRoomCanvas {
        display: block;
        width: 100%;
        height: 100%;
      }
      body[data-exam-mode="escape_room"] .escape-room-controls {
        position: absolute;
        right: .4rem;
        bottom: .35rem;
        width: auto;
        display: flex;
        flex-direction: row;
        gap: .35rem;
        padding: 0;
        border: 0;
        background: transparent;
      }
      body[data-exam-mode="escape_room"] .escape-room-dpad {
        gap: .3rem;
      }
      body[data-exam-mode="escape_room"] .escape-room-stage .dpad-btn {
        width: 2.15rem;
        height: 2.15rem;
        border-radius: .7rem;
        background: rgba(15,23,42,.82);
      }
      body[data-exam-mode="escape_room"] .escape-room-controls .btn-quick-encounter {
        width: auto;
        min-width: 2.15rem;
        height: 2.15rem;
        margin: 0;
        padding: .35rem;
        border-radius: .7rem;
        font-size: .65rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView.escape-room-question-panel {
        top: calc(138px + clamp(170px, 29dvh, 255px));
        right: 1%;
        bottom: 8px;
        left: 1%;
        width: auto;
        padding: .35rem;
        gap: .35rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter {
        min-height: 50px;
        padding: .4rem .55rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter-icon {
        width: 2rem;
        height: 2rem;
        flex-basis: 2rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter h2 {
        font-size: .82rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .escape-room-encounter p:last-child {
        display: none;
      }
      body[data-exam-mode="escape_room"] .escape-room-question-empty {
        gap: .25rem;
        padding: .5rem;
      }
      body[data-exam-mode="escape_room"] .escape-room-empty-coin {
        font-size: 1.65rem;
      }
      body[data-exam-mode="escape_room"] .escape-room-question-empty h3 {
        font-size: .85rem;
      }
      body[data-exam-mode="escape_room"] .escape-room-question-empty p {
        font-size: .68rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .question-area {
        padding: .4rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .q-text {
        font-size: .84rem;
        line-height: 1.3;
        margin-bottom: .3rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .q-type-badge {
        margin-bottom: .3rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .opt-card {
        padding: .45rem .6rem;
      }
      body[data-exam-mode="escape_room"] #classicExamView .options-list {
        gap: .35rem;
      }
    }

  </style>
</head>
<body data-exam-mode="<?= esc($examMode ?? 'classic') ?>">
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
        <?php if (($examMode ?? 'classic') === 'pokemon'): ?>
          <!-- Sound Toggle Button (เฉพาะโหมดเกม) -->
          <button type="button" id="soundToggleBtn" onclick="toggleSoundFx()" class="p-1.5 px-2.5 rounded-full text-xs font-black border border-white/10 bg-white/5 hover:bg-white/10 text-slate-300 transition-all flex items-center gap-1 cursor-pointer" title="เปิด/ปิดเสียงเอฟเฟกต์เกม">
            <span id="soundToggleIcon">🔊</span>
          </button>
          <span class="text-[11px] font-black text-amber-300 bg-amber-500/15 border border-amber-500/30 px-2.5 py-1 rounded-full flex items-center gap-1">
            <span>⚡</span> Pokémon RPG
          </span>
        <?php elseif (($examMode ?? 'classic') === 'escape_room'): ?>
          <span class="text-[11px] font-black text-violet-300 bg-violet-500/15 border border-violet-500/30 px-2.5 py-1 rounded-full flex items-center gap-1">
            <span>🪙</span> Coin Quest
          </span>
        <?php else: ?>
          <span class="text-[11px] font-black text-sky-300 bg-sky-500/15 border border-sky-500/30 px-2.5 py-1 rounded-full flex items-center gap-1">
            <span>📝</span> แบบมาตรฐาน
          </span>
        <?php endif; ?>

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

  <?php if (($examMode ?? 'classic') === 'escape_room'): ?>
    <section id="escapeRoomHud" class="escape-room-hud mx-auto mt-4 mb-0 w-[min(96%,1000px)] rounded-t-3xl border border-violet-400/30 border-b-0 bg-gradient-to-r from-[#171329] via-[#251b3d] to-[#111827] px-5 py-3 shadow-2xl shadow-violet-950/40">
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="grid h-12 w-12 place-items-center rounded-2xl border border-violet-300/30 bg-violet-500/15 text-2xl">🪙</div>
          <div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-violet-300">COIN QUEST</p>
            <h2 class="text-lg font-black text-white">กระโดดเก็บเหรียญ ตอบคำถาม</h2>
          </div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-2 text-right">
          <span class="block text-[10px] font-bold text-slate-400">ด่านปัจจุบัน</span>
          <span id="escapeRoomRoomNumber" class="text-sm font-black text-amber-300">ด่าน 1 / --</span>
        </div>
      </div>
      <div class="mt-3 flex flex-wrap items-center gap-2 text-[10px] font-black">
        <span id="escapeRoomStepSearch" class="escape-room-step">① วิ่งและกระโดด</span>
        <span class="text-violet-400">›</span>
        <span id="escapeRoomStepAnswer" class="escape-room-step">② ตอบคำถาม</span>
        <span class="text-violet-400">›</span>
        <span id="escapeRoomStepExit" class="escape-room-step">③ ไปด่านต่อไป</span>
      </div>
      <p id="escapeRoomRoomMessage" class="mt-2 text-xs font-semibold text-slate-300">วิ่งและกระโดดไปแตะเหรียญเพื่อรับคำถาม</p>
      <div class="mt-3 h-2 overflow-hidden rounded-full bg-black/40">
        <div id="escapeRoomProgressFill" class="h-full rounded-full bg-gradient-to-r from-violet-500 via-fuchsia-400 to-amber-300 transition-all duration-500" style="width:0%"></div>
      </div>
      <div class="mt-2 flex items-center justify-between text-[10px] font-bold text-slate-400">
        <span id="escapeRoomKeys">🪙 เหรียญที่เก็บได้ 0 เหรียญ</span>
        <span id="escapeRoomExitStatus">🚪 ทางออกยังล็อกอยู่</span>
      </div>
    </section>
  <?php endif; ?>

  <?php if (($examMode ?? 'classic') === 'escape_room'): ?>
    <section id="escapeRoomMapView" class="escape-room-stage mx-auto mt-0 mb-8 w-[min(96%,1000px)] overflow-hidden rounded-b-3xl border border-violet-400/30 border-t-0 bg-slate-950 shadow-2xl shadow-violet-950/40">
      <div class="flex flex-wrap items-center justify-between gap-2 border-b border-violet-300/15 bg-slate-900/90 px-4 py-3">
        <div>
          <p class="text-[10px] font-black uppercase tracking-[0.2em] text-violet-300">โลกผจญภัยเก็บเหรียญ</p>
          <p class="text-sm font-bold text-white">กระโดดและวิ่งไปแตะเหรียญเพื่อรับคำถาม</p>
        </div>
        <span id="escapeRoomPrompt" class="rounded-full bg-violet-400/10 px-3 py-1 text-xs font-bold text-violet-200">🪙 กระโดดแตะเหรียญเพื่อรับคำถาม</span>
      </div>
      <div class="escape-room-playfield">
        <canvas id="escapeRoomCanvas" width="720" height="420" aria-label="เกมแพลตฟอร์มกระโดดเก็บเหรียญ" style="touch-action:none"></canvas>
        <aside class="escape-room-controls">
          <div class="escape-room-controls-title">
            <span class="text-base">🎮</span>
            <div><p class="text-[10px] font-black uppercase tracking-[.18em] text-violet-300">Platform game</p><p class="text-xs font-bold text-slate-200">วิ่ง กระโดด เก็บเหรียญ</p></div>
          </div>
          <div class="escape-room-dpad flex items-center justify-center gap-2 self-center">
            <button type="button" class="dpad-btn" aria-label="เดินซ้าย" onpointerdown="escapeRoomHeldKeys.left=true" onpointerup="escapeRoomHeldKeys.left=false" onpointerleave="escapeRoomHeldKeys.left=false">◀</button>
            <button type="button" class="dpad-btn escape-room-action-key" aria-label="กระโดด" onclick="escapeRoomWalk(0,-1)">⬆</button>
            <button type="button" class="dpad-btn" aria-label="เดินขวา" onpointerdown="escapeRoomHeldKeys.right=true" onpointerup="escapeRoomHeldKeys.right=false" onpointerleave="escapeRoomHeldKeys.right=false">▶</button>
          </div>
          <p class="escape-room-controls-hint">คีย์บอร์ด: <strong>← →</strong> หรือ <strong>A D</strong> เพื่อวิ่ง<br>กด <strong>Space / ↑</strong> เพื่อกระโดด เก็บเหรียญเพื่อรับคำถาม</p>
          <button type="button" id="escapeRoomInteractBtn" onclick="escapeRoomGuardianDefeated ? interactEscapeRoom() : escapeRoomWalk(0,-1)" class="btn-quick-encounter">
            <span>⬆️</span><span>กระโดด</span>
          </button>
        </aside>
      </div>
    </section>
  <?php endif; ?>

  <!-- ==========================================
       VIEW 1: CLASSIC STANDARD EXAM VIEW
       ========================================== -->
  <div id="classicExamView" class="<?= ($examMode ?? 'classic') === 'escape_room' ? 'escape-room-question-panel' : '' ?>">
    <?php if (($examMode ?? 'classic') === 'escape_room'): ?>
      <div class="escape-room-encounter mx-auto mt-5 w-[min(92%,900px)]">
        <div class="escape-room-encounter-icon">🪙</div>
        <div>
          <p class="text-[10px] font-black uppercase tracking-[.22em] text-fuchsia-200">Coin question</p>
          <h2 class="text-lg font-black text-white">คำถามด่าน <span id="escapeRoomEncounterNumber">1</span></h2>
          <p class="text-xs text-violet-100/70">ตอบคำถามเพื่อเก็บเหรียญและปลดล็อกด่านถัดไป</p>
        </div>
        <div class="ml-auto hidden text-right sm:block">
          <span class="text-xs font-bold text-violet-200" id="escapeRoomEncounterKeys">🪙 0 เหรียญ</span>
        </div>
      </div>
    <?php endif; ?>
    <?php if (($examMode ?? 'classic') === 'escape_room'): ?>
    <div id="escapeRoomQuestionEmpty" class="escape-room-question-empty">
      <div class="escape-room-empty-coin">🪙</div>
      <h3 id="escapeRoomQuestionEmptyTitle">เก็บเหรียญเพื่อเปิดคำถาม</h3>
      <p id="escapeRoomQuestionEmptyText">ใช้ปุ่มซ้าย/ขวาเพื่อวิ่ง แล้วกระโดดแตะเหรียญ คำถามจะแสดงตรงนี้โดยไม่ออกจากฉากเกม</p>
    </div>
    <?php endif; ?>
    <!-- ===== PROGRESS ===== -->
    <div class="progress-section">
      <div class="progress-info">
        <?php
          $isMidterm = (mb_strpos($examType, 'กลางภาค') !== false);
          $isFinal   = (mb_strpos($examType, 'ปลายภาค') !== false);
          $typeTag   = $isMidterm ? '🎯 ' : ($isFinal ? '🏁 ' : '📝 ');
        ?>
        <span class="progress-label" id="examSubject"><?= $typeTag . esc($examType) ?></span>
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
  </div>

  <!-- ==========================================
       VIEW 2: GAMIFIED POKÉMON RPG ADVENTURE VIEW
       ========================================== -->
  <div id="pokemonExamView" class="hidden">
    <!-- Pokémon HUD Bar -->
    <div class="poke-hud-bar">
      <div class="poke-route-badge">
        <span class="text-xl animate-pulse">🌲</span>
        <div>
          <span class="block text-xs font-black text-amber-300" id="pokeRouteTitle">ถนนสายวิชาการ (Route 1)</span>
          <span class="block text-[10px] text-slate-300 font-bold" id="pokeMilestoneText">จุดข้อสอบที่ 1 / --</span>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <div class="poke-stage-switch">
          <button type="button" id="btnShowMapStage" onclick="switchPokemonSubStage('map')" class="poke-stage-btn active">🗺️ แผนที่เดิน</button>
          <button type="button" id="btnShowBattleStage" onclick="switchPokemonSubStage('battle')" class="poke-stage-btn">⚔️ สนามประลอง</button>
        </div>
      </div>
    </div>

    <!-- SUB-STAGE A: OVERWORLD MAP STAGE -->
    <div id="pokeMapStage" class="poke-map-card">
      <div class="poke-map-header">
        <span class="flex items-center gap-1.5">
          <span>🎮</span> บังคับเดินด้วยปุ่มลูกศร, WASD หรือแตะจุดบนแผนที่
        </span>
        <span id="pokeTrainerBadge" class="text-amber-300 font-mono font-bold">Trainer & Pikachu ⚡</span>
      </div>

      <canvas id="pokeMapCanvas" width="640" height="420"></canvas>

      <div class="poke-map-footer">
        <!-- Mobile Touch D-Pad -->
        <div class="flex items-center gap-4">
          <div class="dpad-container">
            <div></div>
            <button type="button" class="dpad-btn" onmousedown="pokeMapWalk('up')" ontouchstart="pokeMapWalk('up'); event.preventDefault();">▲</button>
            <div></div>
            <button type="button" class="dpad-btn" onmousedown="pokeMapWalk('left')" ontouchstart="pokeMapWalk('left'); event.preventDefault();">◄</button>
            <button type="button" class="dpad-btn" style="background:#1e293b; font-size:0.75rem; color:#fbbf24;" onmousedown="pokeMapInteract()" ontouchstart="pokeMapInteract(); event.preventDefault();">A</button>
            <button type="button" class="dpad-btn" onmousedown="pokeMapWalk('right')" ontouchstart="pokeMapWalk('right'); event.preventDefault();">►</button>
            <div></div>
            <button type="button" class="dpad-btn" onmousedown="pokeMapWalk('down')" ontouchstart="pokeMapWalk('down'); event.preventDefault();">▼</button>
            <div></div>
          </div>
          <span class="text-[11px] text-slate-400 hidden sm:inline leading-tight">
            เดินชนพงหญ้า / จุดธงข้อสอบเพื่อเข้าสู่การประลอง
          </span>
        </div>

        <!-- Quick Encounter Button -->
        <button type="button" onclick="startPokemonBattleEncounter()" class="btn-quick-encounter">
          <span>⚡</span>
          <span>เข้าฉากประลองข้อนี้ทันที! ➔</span>
        </button>
      </div>
    </div>

    <!-- SUB-STAGE B: POKEMON BATTLE ARENA -->
    <div id="pokeBattleStage" class="poke-battle-arena arena-forest hidden">
      <!-- Arena Visual Field (Platforms & Pokémon) -->
      <div class="poke-battle-field" id="pokeBattleField">
        <!-- Habitat Badge -->
        <div class="arena-habitat-badge" id="arenaHabitatBadge">
          <span id="arenaHabitatIcon">🌲</span>
          <span id="arenaHabitatName">ป่าเขียวขจี</span>
        </div>

        <!-- Scenery Backdrop Layer -->
        <div class="arena-scenery-backdrop" id="arenaSceneryBackdrop"></div>

        <!-- Enemy Platform (Top Right) -->
        <div class="enemy-platform-wrap">
          <div class="poke-status-card enemy">
            <div class="poke-name-row">
              <span class="poke-name" id="pokeEnemyName">
                <span id="pokeEnemyIcon">👻</span> Gengar
              </span>
              <span class="poke-lv-badge" id="pokeEnemyLv">Lv. 15</span>
            </div>
            <div class="poke-hp-wrap">
              <span class="poke-hp-label">HP</span>
              <div class="poke-hp-track">
                <div class="poke-hp-fill" id="pokeEnemyHpFill" style="width: 100%;"></div>
              </div>
              <span id="pokeEnemyHpText" class="text-[9px] font-black text-slate-300 ml-1">100/100</span>
            </div>
          </div>
          <div class="enemy-sprite-container" id="pokeEnemySpriteContainer">
            <div class="enemy-pedestal"></div>
            <div class="enemy-sprite" id="pokeEnemySprite"></div>
            <div id="pokeSkillEffect" class="poke-skill-effect"></div>
          </div>
        </div>

        <!-- Player Platform (Bottom Left) -->
        <div class="player-platform-wrap">
          <div class="player-sprite-container" id="pokePlayerSpriteContainer">
            <div class="player-pedestal"></div>
            <div class="player-sprite" id="pokePlayerSprite"></div>
            <div id="pokePlayerSkillEffect" class="poke-skill-effect"></div>
          </div>
          <div class="poke-status-card player">
            <div class="poke-name-row">
              <span class="poke-name">
                <span>⚡</span> Pikachu (คู่หู)
              </span>
              <span class="poke-lv-badge" id="pokePlayerLv">Lv. 10</span>
            </div>
            <div class="poke-hp-wrap">
              <span class="poke-hp-label">HP</span>
              <div class="poke-hp-track">
                <div class="poke-hp-fill" id="pokePlayerHpFill" style="width: 100%;"></div>
              </div>
              <span id="pokePlayerHpText" class="text-[9px] font-black text-slate-300 ml-1">100/100</span>
            </div>
            <div class="poke-exp-track">
              <div class="poke-exp-fill" id="pokePlayerExpFill" style="width: 0%;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Battle Box (Question Dialog & Moves Grid) -->
      <div class="poke-battle-box">
        <!-- Dialog / Question Pane -->
        <div class="poke-dialog-screen">
          <div class="poke-dialog-title" id="pokeBattleQTitle">
            <span>📜</span> คำถามข้อที่ 1 / -- • ปรนัย
          </div>
          <div class="poke-dialog-text" id="pokeBattleQText">
            กำลังโหลดคำถาม...
          </div>
          <div id="pokeBattleQImage" class="mt-2"></div>
          <div class="poke-battle-narrator" id="pokeBattleNarrator"></div>
        </div>

        <!-- Moves Commands Pane (ช้อยส์ A, B, C, D หรือข้อเขียน) -->
        <div id="pokeBattleMovesContainer" class="poke-moves-grid">
          <!-- Dynamic battle moves rendered via JS -->
        </div>

        <!-- Battle Actions Bar -->
        <div class="poke-battle-actions">
          <!-- Back to Map button removed to enforce answering -->
          <button type="button" id="pokeAttackBtn" disabled onclick="executePokemonAttack()" class="btn-poke-attack cursor-pointer">
            <span>⚡</span>
            <span id="pokeAttackBtnText">ปล่อยพลังโจมตี! (ยืนยันคำตอบ)</span>
          </button>
        </div>
      </div>
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
    var answeredQuestions = [];
    var timerInterval;
    var startTime;
    var serverAttemptStartedAt = <?= !empty($attemptStartedAt ?? null) ? json_encode($attemptStartedAt) : 'null' ?>;
    var serverAttemptStartedMs = serverAttemptStartedAt ? Date.parse(serverAttemptStartedAt.replace(' ', 'T') + '<?= date_default_timezone_get() === 'Asia/Bangkok' ? '+07:00' : '' ?>') : 0;
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
        if (typeof initExamViewMode === 'function') {
            initExamViewMode();
        }
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
        startTime = serverAttemptStartedMs > 0 ? new Date(serverAttemptStartedMs) : new Date();
        isExamActive = true;
        studentAnswers = [];
        answeredQuestions = [];
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
            
            // Prevent accidental refresh or leaving page
            window.addEventListener('beforeunload', (e) => {
                if (isExamActive) {
                    e.preventDefault();
                    e.returnValue = ''; // Required for Chrome
                }
            });

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
            // 7. If NOT in a typing field, allow navigation and Pokémon game walk keys (WASD, Arrows, Space, Enter)
            else if (!isTypingField) {
                const allowedMovementKeys = [
                    'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight',
                    'w', 'a', 's', 'd', 'W', 'A', 'S', 'D', ' ', 'Enter'
                ];
                if (currentExamMode === 'escape_room') allowedMovementKeys.push('e', 'E');
                if (!allowedMovementKeys.includes(e.key)) {
                    shouldBlock = true;
                } else {
                    // Check if Pokemon Map view wants to handle this key for walking
                    if (window.handleEscapeRoomKey && window.handleEscapeRoomKey(e.key)) {
                        e.preventDefault();
                        return true;
                    }
                    if (window.handlePokeMapKey && window.handlePokeMapKey(e.key)) {
                        e.preventDefault();
                        return true;
                    }
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
                if (questions.length > 0) {
                    if (currentExamMode === 'escape_room') {
                        updateEscapeRoomProgress();
                        drawEscapeRoom();
                    } else {
                        displayQuestion();
                    }
                } else {
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

    function updateEscapeRoomProgress() {
        const roomNumber = document.getElementById('escapeRoomRoomNumber');
        if (!roomNumber || !questions.length) return;

        const current = currentQuestionIndex + 1;
        const total = questions.length;
        const cleared = Math.min(total, currentQuestionIndex + (escapeRoomGuardianDefeated ? 1 : 0));
        const percentage = Math.round((cleared / total) * 100);
        const setStep = (id, state) => {
            const step = document.getElementById(id);
            if (step) step.className = 'escape-room-step' + (state ? ' ' + state : '');
        };
        setStep('escapeRoomStepSearch', escapeRoomGuardianDefeated ? 'done' : 'active');
        setStep('escapeRoomStepAnswer', escapeRoomEncounterActive ? 'active' : (escapeRoomGuardianDefeated ? 'done' : ''));
        setStep('escapeRoomStepExit', escapeRoomGuardianDefeated ? 'active' : '');

        roomNumber.textContent = `ด่าน ${current} / ${total}`;
        const progress = document.getElementById('escapeRoomProgressFill');
        if (progress) progress.style.width = `${percentage}%`;
        const encounterNumber = document.getElementById('escapeRoomEncounterNumber');
        const encounterKeys = document.getElementById('escapeRoomEncounterKeys');
        if (encounterNumber) encounterNumber.textContent = current;
        if (encounterKeys) encounterKeys.textContent = `🪙 ${escapeRoomKeysCollected} เหรียญ`;
        const keys = document.getElementById('escapeRoomKeys');
        const exitStatus = document.getElementById('escapeRoomExitStatus');
        const roomMessage = document.getElementById('escapeRoomRoomMessage');
        if (keys) keys.textContent = `🪙 เก็บได้ ${escapeRoomKeysCollected} เหรียญ`;
        if (exitStatus) exitStatus.textContent = escapeRoomGuardianDefeated
            ? (current === total ? '🏁 พร้อมจบการสอบ' : '➡️ เหรียญที่ตอบแล้ว พาไปด่านถัดไป')
            : '🪙 กระโดดแตะเหรียญเพื่อรับคำถาม';
        if (roomMessage) roomMessage.textContent = escapeRoomGuardianDefeated
            ? 'ตอบคำถามแล้ว! กด E หรือปุ่มกระโดดเพื่อไปด่านถัดไป'
            : 'วิ่งและกระโดดไปแตะเหรียญ แล้วตอบคำถามเพื่อเก็บเหรียญ';
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
        updateEscapeRoomProgress();

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
        if (currentExamMode === 'pokemon' && pokeSubStage === 'map') {
            startQuestionTimer(20, true);
        } else {
            startQuestionTimer();
        }

        // Buttons
        const nextButton = document.getElementById('nextQuestionBtn');
        const submitButton = document.getElementById('submitExamBtn');
        nextButton.disabled = true;
        submitButton.disabled = true;
        nextButton.textContent = currentExamMode === 'escape_room'
            ? (currentQuestionIndex === questions.length - 1 ? 'ตอบและจบการสอบ 🏁' : 'ตอบแล้ววิ่งต่อ ➔')
            : 'ข้อถัดไป ➔';
        submitButton.textContent = 'ส่งข้อสอบ ✓';

        if (currentExamMode === 'escape_room' || currentQuestionIndex < questions.length - 1) {
            nextButton.classList.remove('hidden');
            submitButton.classList.add('hidden');
        } else {
            nextButton.classList.add('hidden');
            submitButton.classList.remove('hidden');
        }

        // Synchronize with Pokémon Game Stage if engine is loaded
        if (typeof updatePokemonExamState === 'function') {
            updatePokemonExamState();
        }
    }

    // ===== Writing Input Check =====
    window.checkWritingInput = function(el) {
        const has = el.value.trim().length > 0;
        document.getElementById('nextQuestionBtn').disabled = !has;
        document.getElementById('submitExamBtn').disabled = !has;
        if (typeof syncPokeWritingInput === 'function') {
            syncPokeWritingInput(el.value);
        }
    }

    // ===== Select Option =====
    function selectOption(el, index) {
        document.querySelectorAll('.opt-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input').checked = true;
        document.getElementById('nextQuestionBtn').disabled = false;
        document.getElementById('submitExamBtn').disabled = false;
        if (typeof syncPokeMoveSelection === 'function') {
            syncPokeMoveSelection(index);
        }
    }

    // ===== Timer =====
    function startQuestionTimer(startFrom, isMapTimer = false) {
        clearInterval(timerInterval);
        const currentQ = questions[currentQuestionIndex];
        
        let timeLeft = 0;
        if (isMapTimer) {
            timeLeft = (typeof startFrom === 'number' && startFrom > 0) ? Math.floor(startFrom) : 20;
        } else {
            timeLeft = (typeof startFrom === 'number' && startFrom > 0)
                ? Math.floor(startFrom)
                : (currentQ && currentQ.type === 'writing' ? timeLimitWriting : timeLimitChoice);
        }
        
        questionTimeLeft = timeLeft;

        if (!isMapTimer) {
            totalQuestionTime = timeLeft;
            pokePlayerHp = 100;
            isEnemyAttacking = false;
            lastEnemyAttackTime = Date.now();
            if (typeof updatePokeHpDisplays === 'function') {
                updatePokeHpDisplays();
            }
        }

        const timerEl = document.getElementById('timerNumber');
        const timerBox = document.getElementById('questionTimer');
        timerEl.textContent = timeLeft;
        timerBox.classList.remove('warning');
        timerEl.classList.remove('timer-pulse');

        if (isMapTimer) {
            // Map timer UI style
            timerBox.style.borderColor = '#38bdf8';
            timerBox.style.background = 'rgba(56, 189, 248, 0.12)';
            timerEl.style.color = '#38bdf8';
        } else if (currentQ && currentQ.type === 'writing') {
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

            // Enemy random attack proportional to elapsed / decreasing time
            if (!isMapTimer && currentExamMode === 'pokemon' && pokeSubStage === 'battle') {
                if (typeof checkAndTriggerEnemyAttack === 'function') {
                    checkAndTriggerEnemyAttack(questionTimeLeft, totalQuestionTime);
                }
            }

            if (questionTimeLeft <= 0) {
                clearInterval(timerInterval);
                handleTimeOut(isMapTimer);
            }
        }, 1000);
    }

    function handleTimeOut(isMapTimer = false) {
        if (!isExamActive || isPaused || isSubmitting) return;
        const currentQ = questions[currentQuestionIndex];
        const isPokemonMode = (currentExamMode === 'pokemon');
        
        if (isMapTimer) {
            Swal.fire({
                title: 'มัวแต่เดินเล่น!',
                text: 'เดินช้าเกินไป โปเกมอนป่าแอบขโมยคะแนนไปแล้ว 1 ข้อ!',
                icon: 'warning',
                timer: 2500,
                showConfirmButton: false
            });
            setTimeout(() => {
                // Find an unanswered question (prefer non-writing)
                let unanswered = questions.map((_, i) => i).filter(i => !answeredQuestions.includes(i) && questions[i].type !== 'writing');
                if (unanswered.length === 0) {
                    unanswered = questions.map((_, i) => i).filter(i => !answeredQuestions.includes(i));
                }
                
                if (unanswered.length > 0) {
                    const lostIdx = unanswered[Math.floor(Math.random() * unanswered.length)];
                    answeredQuestions.push(lostIdx);
                    studentAnswers.push({
                        questionId: String(questions[lostIdx].id),
                        selectedOption: ''
                    });
                }
                
                if (answeredQuestions.length < questions.length) {
                    startQuestionTimer(20, true);
                    drawPokeMap();
                } else {
                    finishExam();
                }
            }, 2500);
            return;
        }

        if (currentQ && currentQ.type === 'writing' && currentExamMode === 'escape_room') {
            Swal.fire({ title: 'หมดเวลา!', text: 'หมดเวลาข้อนี้ เหรียญข้อถัดไปรออยู่ข้างหน้า', icon: 'info', timer: 1200, showConfirmButton: false });
            setTimeout(() => {
                const answer = collectAnswer();
                returnToEscapeRoom(answer);
            }, 1200);
            return;
        }

        if (currentQ && currentQ.type === 'writing') {
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
            const timeoutMsg = isPokemonMode
                ? '💨 โปเกมอนป่าหนีไปแล้ว! ข้อนี้ถูกข้ามไป'
                : 'ระบบกำลังเปลี่ยนไปข้อถัดไป';
            const timeoutIcon = isPokemonMode ? 'warning' : 'info';
            const timeoutTitle = isPokemonMode ? 'โปเกมอนหนีไปแล้ว!' : 'หมดเวลา!';

            Swal.fire({ title: timeoutTitle, text: timeoutMsg, icon: timeoutIcon, timer: 1500, showConfirmButton: false });
            setTimeout(() => {
                const answer = collectAnswer();
                if (isPokemonMode) {
                    if (!answeredQuestions.includes(currentQuestionIndex)) {
                        answeredQuestions.push(currentQuestionIndex);
                    }
                    if (answeredQuestions.length < questions.length) {
                        switchPokemonSubStage('map');
                        drawPokeMap();
                        startQuestionTimer(20, true);
                    } else {
                        finishExam();
                    }
                } else if (currentExamMode === 'escape_room') {
                    returnToEscapeRoom(answer);
                } else {
                    currentQuestionIndex++;
                    if (currentQuestionIndex < questions.length) {
                        displayQuestion();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        finishExam();
                    }
                }
            }, 1500);
        }
    }

    // ===== Navigation =====
    document.getElementById('nextQuestionBtn').onclick = moveToNext;

    function moveToNext() {
        const answer = collectAnswer();
        if (currentExamMode === 'escape_room') {
            returnToEscapeRoom(answer);
            return;
        }

        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            displayQuestion();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function collectAnswer() {
        if (currentExamMode === 'escape_room' && escapeRoomAnswerRecorded) return '';
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
        if (currentExamMode === 'escape_room') escapeRoomAnswerRecorded = true;
        return ansValue;
    }

    // ===== Submit =====
    document.getElementById('submitExamBtn').onclick = async () => {
        if (isSubmitting) return;

        const result = await Swal.fire({
            title: currentExamMode === 'escape_room' ? 'พร้อมเปิดทางออกหรือยัง?' : 'ต้องการส่งข้อสอบ?',
            text: currentExamMode === 'escape_room'
                ? 'ส่งคำตอบเพื่อปลดล็อกทางออก เมื่อส่งแล้วจะกลับมาแก้ไขไม่ได้'
                : "เมื่อส่งแล้วจะไม่สามารถกลับมาแก้ไขได้อีก",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ส่งข้อสอบ',
            cancelButtonText: 'ยกเลิก',
            allowOutsideClick: false
        });

        if (result.isConfirmed) {
            collectAnswer();
            finishExam();
        }
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

        // Lock the submit UI immediately so students always get visible feedback
        // while the server is processing the submission.
        const submitBtn = document.getElementById('submitExamBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('is-processing');
            submitBtn.innerHTML = '<span class="submit-spinner" aria-hidden="true"></span> กำลังส่งข้อสอบ...';
        }
        clearInterval(timerInterval);
        clearInterval(overallTimerInterval);
        clearInterval(statusInterval);

        if (blurHandler) window.removeEventListener('blur', blurHandler);
        if (visibilityHandler) document.removeEventListener('visibilitychange', visibilityHandler);
        if (keydownBlocker) window.removeEventListener('keydown', keydownBlocker, true);

        var totalTime = startTime ? Math.max(0, Math.floor((Date.now() - startTime.getTime()) / 1000)) : 0;

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

        var totalTime = startTime ? Math.max(0, Math.floor((Date.now() - startTime.getTime()) / 1000)) : 0;

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

    /* ==========================================================================
       🎮 POKÉMON RPG GAMIFIED EXAM ENGINE (เสียง, แผนที่เดิน, และระบบประลองข้อสอบ)
       ========================================================================== */

    // Sound FX Web Audio Synthesizer (Retro 8-bit Audio)
    const PokeSoundFX = {
        ctx: null,
        muted: false,
        init() {
            if (!this.ctx) {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (AudioCtx) this.ctx = new AudioCtx();
            }
            if (this.ctx && this.ctx.state === 'suspended') {
                this.ctx.resume();
            }
        },
        tone(freq, type, duration, delay = 0, gainLevel = 0.12) {
            if (this.muted) return;
            this.init();
            if (!this.ctx) return;
            setTimeout(() => {
                try {
                    const osc = this.ctx.createOscillator();
                    const gain = this.ctx.createGain();
                    osc.type = type;
                    osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
                    gain.gain.setValueAtTime(gainLevel, this.ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.0001, this.ctx.currentTime + duration);
                    osc.connect(gain);
                    gain.connect(this.ctx.destination);
                    osc.start();
                    osc.stop(this.ctx.currentTime + duration);
                } catch (e) {}
            }, delay * 1000);
        },
        playStep() {
            this.tone(130, 'triangle', 0.04, 0, 0.03);
        },
        playSelect() {
            this.tone(523, 'sine', 0.07, 0, 0.08);
            this.tone(659, 'sine', 0.09, 0.05, 0.08);
        },
        playEncounter() {
            [261, 329, 392, 523, 659, 784].forEach((freq, idx) => {
                this.tone(freq, 'sawtooth', 0.11, idx * 0.045, 0.12);
            });
        },
        playAttack() {
            if (this.muted) return;
            this.init();
            if (!this.ctx) return;
            try {
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(850, this.ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(80, this.ctx.currentTime + 0.32);
                gain.gain.setValueAtTime(0.18, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, this.ctx.currentTime + 0.32);
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start();
                osc.stop(this.ctx.currentTime + 0.32);
            } catch (e) {}
        },
        playHit() {
            this.tone(100, 'square', 0.22, 0, 0.22);
            this.tone(55, 'sawtooth', 0.28, 0.04, 0.2);
        },
        playEnemyAttack() {
            if (this.muted) return;
            this.init();
            if (!this.ctx) return;
            try {
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(280, this.ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(70, this.ctx.currentTime + 0.28);
                gain.gain.setValueAtTime(0.16, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, this.ctx.currentTime + 0.28);
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start();
                osc.stop(this.ctx.currentTime + 0.28);
            } catch (e) {}
        },
        playPlayerHit() {
            this.tone(90, 'square', 0.22, 0, 0.22);
            this.tone(45, 'sawtooth', 0.28, 0.03, 0.2);
        },
        playLevelUp() {
            const notes = [261, 329, 392, 523, 659, 784, 1046];
            notes.forEach((freq, idx) => {
                this.tone(freq, 'square', 0.1, idx * 0.065, 0.1);
            });
        },
        playVictory() {
            const fanfare = [523, 523, 523, 523, 415, 466, 523, 466, 523];
            fanfare.forEach((freq, idx) => {
                this.tone(freq, 'triangle', 0.15, idx * 0.08, 0.12);
            });
        }
    };

    function toggleSoundFx() {
        PokeSoundFX.muted = !PokeSoundFX.muted;
        const icon = document.getElementById('soundToggleIcon');
        if (icon) {
            icon.textContent = PokeSoundFX.muted ? '🔇' : '🔊';
        }
    }

    // ===== AUTHENTIC POKÉMON PIXEL SPRITE ENGINE =====
    function createPokeSprite(id, name, isBack = false) {
        const cleanName = name.toLowerCase().replace(/[^a-z0-9]/g, '');
        const showdownUrl = isBack
            ? `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/back/${id}.gif`
            : `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/showdown/${id}.gif`;
        const showdownAni = isBack
            ? `https://play.pokemonshowdown.com/sprites/ani-back/${cleanName}.gif`
            : `https://play.pokemonshowdown.com/sprites/ani/${cleanName}.gif`;
        const pixelPng = isBack
            ? `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/back/${id}.png`
            : `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${id}.png`;

        return `<img src="${showdownUrl}" 
                     alt="${name}" 
                     class="poke-pixel-sprite" 
                     loading="eager" 
                     draggable="false"
                     onerror="if(!this.dataset.retried){this.dataset.retried='1';this.src='${showdownAni}';}else if(this.dataset.retried==='1'){this.dataset.retried='2';this.src='${pixelPng}';}" />`;
    }

    const POKE_SPRITES = {
        pikachuBack: createPokeSprite(25, 'pikachu', true),
        gengar: createPokeSprite(94, 'gengar'),
        charmander: createPokeSprite(4, 'charmander'),
        bulbasaur: createPokeSprite(1, 'bulbasaur'),
        squirtle: createPokeSprite(7, 'squirtle'),
        charizard: createPokeSprite(6, 'charizard'),
        snorlax: createPokeSprite(143, 'snorlax'),
        blastoise: createPokeSprite(9, 'blastoise'),
        lucario: createPokeSprite(448, 'lucario'),
        venusaur: createPokeSprite(3, 'venusaur'),
        dragonite: createPokeSprite(149, 'dragonite'),
        gyarados: createPokeSprite(130, 'gyarados'),
        eevee: createPokeSprite(133, 'eevee'),
        rayquaza: createPokeSprite(384, 'rayquaza'),
        mewtwo: createPokeSprite(150, 'mewtwo'),
        mew: createPokeSprite(151, 'mew')
    };

    const POKE_ENEMIES = [
        { name: 'Gengar', icon: '👻', type: 'Ghost/Poison', habitat: 'mountain', habitatName: 'ภูเขาและถ้ำโบราณ ⛰️', sprite: POKE_SPRITES.gengar },
        { name: 'Charmander', icon: '🔥', type: 'Fire', habitat: 'mountain', habitatName: 'หุบเขาภูเขาไฟ 🌋', sprite: POKE_SPRITES.charmander },
        { name: 'Bulbasaur', icon: '🍃', type: 'Grass/Poison', habitat: 'forest', habitatName: 'ป่าเขียวขจี 🌲', sprite: POKE_SPRITES.bulbasaur },
        { name: 'Squirtle', icon: '💧', type: 'Water', habitat: 'river', habitatName: 'ริมแม่น้ำใส 🌊', sprite: POKE_SPRITES.squirtle },
        { name: 'Charizard', icon: '🔥', type: 'Fire/Flying', habitat: 'mountain', habitatName: 'ยอดเขาภูเขาไฟ 🌋', sprite: POKE_SPRITES.charizard },
        { name: 'Snorlax', icon: '💤', type: 'Normal', habitat: 'forest', habitatName: 'ป่าสนโบราณ 🌲', sprite: POKE_SPRITES.snorlax },
        { name: 'Blastoise', icon: '💧', type: 'Water', habitat: 'river', habitatName: 'ทะเลสาบกว้าง 🌊', sprite: POKE_SPRITES.blastoise },
        { name: 'Lucario', icon: '🥊', type: 'Fighting/Steel', habitat: 'mountain', habitatName: 'ยอดเขาสูงชัน 🏔️', sprite: POKE_SPRITES.lucario },
        { name: 'Venusaur', icon: '🍃', type: 'Grass/Poison', habitat: 'forest', habitatName: 'ป่าดงดิบลึก 🌿', sprite: POKE_SPRITES.venusaur },
        { name: 'Dragonite', icon: '🐲', type: 'Dragon/Flying', habitat: 'river', habitatName: 'ปากอ่าวแม่น้ำ 🌊', sprite: POKE_SPRITES.dragonite },
        { name: 'Gyarados', icon: '🌊', type: 'Water/Flying', habitat: 'river', habitatName: 'น้ำตกเชี่ยวกราก 🌊', sprite: POKE_SPRITES.gyarados },
        { name: 'Eevee', icon: '🦊', type: 'Normal', habitat: 'forest', habitatName: 'ทุ่งหญ้าชายป่า 🌸', sprite: POKE_SPRITES.eevee },
        { name: 'Rayquaza', icon: '🐉', type: 'Dragon/Flying', habitat: 'mountain', habitatName: 'ยอดเขาเสียดฟ้า ⚡', sprite: POKE_SPRITES.rayquaza },
        { name: 'Mewtwo', icon: '🔮', type: 'Psychic', habitat: 'mountain', habitatName: 'ถ้ำหินลึกลับ 🔮', sprite: POKE_SPRITES.mewtwo },
        { name: 'Mew', icon: '✨', type: 'Psychic', habitat: 'forest', habitatName: 'ป่าศักดิ์สิทธิ์ ✨', sprite: POKE_SPRITES.mew }
    ];

    const POKE_MOVES_NAMES = [
        { tag: '⚡ สายฟ้าฟาด (Thunderbolt)', desc: 'พลังไฟฟ้าแรงสูง' },
        { tag: '💥 หางเหล็กกล้า (Iron Tail)', desc: 'จู่โจมด้วยหางเหล็ก' },
        { tag: '🔮 บอลประจุไฟฟ้า (Electro Ball)', desc: 'ยิงบอลพลังงาน' },
        { tag: '💨 พุ่งจู่โจมไว (Quick Attack)', desc: 'จู่โจมฉับไว' },
        { tag: '🌟 ประกายดาว (Swift)', desc: 'โจมตีไม่พลาดเป้า' }
    ];

    // Global Mode States (enforced by Teacher's choice)
    var currentExamMode = "<?= esc($examMode ?? 'classic') ?>";
    var pokeSubStage = 'map'; // 'map' or 'battle'
    var selectedPokemonMoveIndex = null;
    var isPokeAttacking = false;
    var pokePlayerHp = 100;
    var isEnemyAttacking = false;
    var lastEnemyAttackTime = 0;
    var totalQuestionTime = 0;

    // View Mode Initialization
    function initExamViewMode() {
        const mode = "<?= esc($examMode ?? 'classic') ?>";
        setExamViewMode(mode, false);
    }

    // ===== Coin Quest coin platformer =====
    var escapeRoomPlayer = { x: 70, y: 354, vy: 0, grounded: true };
    var escapeRoomKeysCollected = 0;
    var escapeRoomEncounterActive = false;
    var escapeRoomGuardianDefeated = false;
    var escapeRoomAnswerRecorded = false;
    var escapeRoomAnimationFrame = null;
    var escapeRoomLastFrame = 0;
    var escapeRoomHeldKeys = {};
    var escapeRoomCoinTaken = false;
    var escapeRoomPlatforms = [
        { x: 0, y: 370, w: 720, h: 50 },
        { x: 150, y: 300, w: 130, h: 16 },
        { x: 340, y: 245, w: 135, h: 16 },
        { x: 535, y: 305, w: 130, h: 16 },
        { x: 70, y: 215, w: 105, h: 16 }
    ];
    var escapeRoomCoinSpots = [
        { x: 120, y: 265 }, { x: 210, y: 265 }, { x: 285, y: 265 },
        { x: 395, y: 210 }, { x: 465, y: 210 }, { x: 590, y: 270 }
    ];
    var escapeRoomCurrentCoin = escapeRoomCoinSpots[0];
    var escapeRoomPreviousSceneIndex = -1;
    var escapeRoomScene = {
        skyTop: '#65c7ff', skyBottom: '#efffb5', hills: '#65c96f', ground: '#39a84e', grass: '#78d65d',
        dirtA: '#a76532', dirtB: '#bd793d', platform: '#8b552f', platformTop: '#61bf4d', platformDetail: '#9ce36c'
    };
    var escapeRoomScenes = [
        { skyTop: '#65c7ff', skyBottom: '#efffb5', hills: '#65c96f', ground: '#39a84e', grass: '#78d65d', dirtA: '#a76532', dirtB: '#bd793d', platform: '#8b552f', platformTop: '#61bf4d', platformDetail: '#9ce36c' },
        { skyTop: '#ffb45e', skyBottom: '#fff0a8', hills: '#dc9b45', ground: '#b86a32', grass: '#e5a83f', dirtA: '#99502c', dirtB: '#b96838', platform: '#89502e', platformTop: '#dfa34a', platformDetail: '#f3cf72' },
        { skyTop: '#7897f2', skyBottom: '#e9e4ff', hills: '#8c8fe0', ground: '#596eb4', grass: '#91a7ed', dirtA: '#68578f', dirtB: '#8170ad', platform: '#65588e', platformTop: '#9b91d1', platformDetail: '#c0b8ef' },
        { skyTop: '#f68fc2', skyBottom: '#fff0d2', hills: '#e7789c', ground: '#b44f7a', grass: '#ef91a5', dirtA: '#a94c66', dirtB: '#c46878', platform: '#96516b', platformTop: '#ed8c9a', platformDetail: '#ffd0a1' }
    ];

    function generateEscapeRoomScene() {
        let sceneIndex = Math.floor(Math.random() * escapeRoomScenes.length);
        if (sceneIndex === escapeRoomPreviousSceneIndex) {
            sceneIndex = (sceneIndex + 1 + Math.floor(Math.random() * (escapeRoomScenes.length - 1))) % escapeRoomScenes.length;
        }
        escapeRoomPreviousSceneIndex = sceneIndex;
        escapeRoomScene = escapeRoomScenes[sceneIndex];
        escapeRoomCurrentCoin = escapeRoomCoinSpots[Math.floor(Math.random() * escapeRoomCoinSpots.length)];
    }

    function transitionEscapeRoomScene() {
        if (escapeRoomEncounterActive || !escapeRoomCoinTaken || currentQuestionIndex >= questions.length) return;
        generateEscapeRoomScene();
        escapeRoomPlayer = { x: 54, y: 354, vy: 0, grounded: true };
        escapeRoomCoinTaken = false;
        escapeRoomHeldKeys.left = false;
        escapeRoomHeldKeys.right = false;
        updateEscapeRoomProgress();
        drawEscapeRoom();
    }

    function getEscapeRoomCoin() {
        return escapeRoomCurrentCoin;
    }

    generateEscapeRoomScene();

    function startEscapeRoomAnimation() {
        if (escapeRoomAnimationFrame !== null) return;
        const animate = (time) => {
            const delta = Math.min(32, time - (escapeRoomLastFrame || time));
            escapeRoomLastFrame = time;
            if (!escapeRoomEncounterActive && currentExamMode === 'escape_room') {
                if (escapeRoomHeldKeys.left) escapeRoomPlayer.x -= 4.2 * (delta / 16.7);
                if (escapeRoomHeldKeys.right) escapeRoomPlayer.x += 4.2 * (delta / 16.7);
                if (escapeRoomPlayer.x >= 690 && escapeRoomCoinTaken && currentQuestionIndex < questions.length) {
                    transitionEscapeRoomScene();
                } else {
                    escapeRoomPlayer.x = Math.max(24, Math.min(696, escapeRoomPlayer.x));
                }
                escapeRoomPlayer.vy += 0.48 * (delta / 16.7);
                const oldY = escapeRoomPlayer.y;
                escapeRoomPlayer.y += escapeRoomPlayer.vy * (delta / 16.7);
                escapeRoomPlayer.grounded = false;
                for (const p of escapeRoomPlatforms) {
                    if (escapeRoomPlayer.vy >= 0 && oldY <= p.y && escapeRoomPlayer.y >= p.y
                        && escapeRoomPlayer.x + 10 > p.x && escapeRoomPlayer.x - 10 < p.x + p.w) {
                        escapeRoomPlayer.y = p.y;
                        escapeRoomPlayer.vy = 0;
                        escapeRoomPlayer.grounded = true;
                        break;
                    }
                }
                const coin = getEscapeRoomCoin();
                if (!escapeRoomCoinTaken && !escapeRoomGuardianDefeated
                    && Math.hypot(escapeRoomPlayer.x - coin.x, (escapeRoomPlayer.y - 18) - coin.y) < 25) {
                    interactEscapeRoom();
                }
                drawEscapeRoom();
            }
            escapeRoomAnimationFrame = requestAnimationFrame(animate);
        };
        escapeRoomAnimationFrame = requestAnimationFrame(animate);
    }

    function stopEscapeRoomAnimation() {
        if (escapeRoomAnimationFrame !== null) cancelAnimationFrame(escapeRoomAnimationFrame);
        escapeRoomAnimationFrame = null;
        escapeRoomLastFrame = 0;
    }

    function drawEscapeRoom() {
        const canvas = document.getElementById('escapeRoomCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const w = canvas.width, h = canvas.height;
        const t = Date.now() / 1000;
        const sky = ctx.createLinearGradient(0, 0, 0, h);
        sky.addColorStop(0, escapeRoomScene.skyTop); sky.addColorStop(.62, escapeRoomScene.skyBottom); sky.addColorStop(1, '#fffdf0');
        ctx.fillStyle = sky; ctx.fillRect(0, 0, w, h);

        // Bright hills and clouds create a cheerful side-scrolling platform scene.
        ctx.fillStyle = 'rgba(255,255,255,.78)';
        [[95,72],[260,112],[510,68],[650,128]].forEach(([x,y],i) => {
            const drift = (t * (i + 1) * 3) % 760;
            const cx = (x + drift) % 760 - 20;
            ctx.beginPath(); ctx.arc(cx,y,18,0,Math.PI*2); ctx.arc(cx+22,y-8,23,0,Math.PI*2);
            ctx.arc(cx+48,y,17,0,Math.PI*2); ctx.fill();
        });
        ctx.fillStyle = escapeRoomScene.hills;
        [[-30,350,180],[210,350,240],[485,350,270]].forEach(([x,y,r]) => {
            ctx.beginPath(); ctx.ellipse(x+r/2,y,r/2,75,0,Math.PI,Math.PI*2); ctx.fill();
        });
        ctx.fillStyle = escapeRoomScene.ground; ctx.fillRect(0, 370, w, 50);
        ctx.fillStyle = escapeRoomScene.grass; ctx.fillRect(0, 370, w, 9);
        for (let x=0; x<w; x+=34) {
            ctx.fillStyle = ((x/34)%2) ? escapeRoomScene.dirtA : escapeRoomScene.dirtB;
            ctx.fillRect(x,379,32,41);
            ctx.strokeStyle='rgba(91,48,25,.35)'; ctx.strokeRect(x,379,32,41);
        }

        escapeRoomPlatforms.slice(1).forEach((p,i) => {
            ctx.fillStyle = escapeRoomScene.platform; ctx.fillRect(p.x,p.y,p.w,p.h);
            ctx.fillStyle = escapeRoomScene.platformTop; ctx.fillRect(p.x,p.y-7,p.w,10);
            ctx.fillStyle = escapeRoomScene.platformDetail;
            for (let x=p.x+8; x<p.x+p.w-5; x+=22) ctx.fillRect(x,p.y-7,11,4);
            ctx.fillStyle = 'rgba(80,43,24,.45)';
            for (let x=p.x+15; x<p.x+p.w-8; x+=38) ctx.fillRect(x,p.y+5,10,6);
        });

        // Question coin floats above a platform; touching it opens the current exam question.
        const coin = getEscapeRoomCoin();
        if (!escapeRoomCoinTaken && !escapeRoomGuardianDefeated) {
            const cy = coin.y + Math.sin(t*4)*4;
            ctx.save(); ctx.translate(coin.x,cy);
            ctx.shadowColor='#ffbf24'; ctx.shadowBlur=18;
            ctx.fillStyle='#ffdc45'; ctx.beginPath(); ctx.ellipse(0,0,10,15,0,0,Math.PI*2); ctx.fill();
            ctx.shadowBlur=0; ctx.strokeStyle='#f39718'; ctx.lineWidth=3; ctx.stroke();
            ctx.fillStyle='#fff6a5'; ctx.fillRect(-2,-8,4,16); ctx.restore();
        }

        // Original, generic pixel hero (no external character art).
        const px=escapeRoomPlayer.x, py=escapeRoomPlayer.y;
        ctx.fillStyle='rgba(27,62,61,.22)'; ctx.beginPath(); ctx.ellipse(px,py+3,16,5,0,0,Math.PI*2); ctx.fill();
        ctx.fillStyle='#d94b35'; ctx.fillRect(px-12,py-34,24,9);
        ctx.fillStyle='#f1c09a'; ctx.fillRect(px-9,py-26,18,13);
        ctx.fillStyle='#315bd8'; ctx.fillRect(px-11,py-13,22,15);
        ctx.fillStyle='#233f9c'; ctx.fillRect(px-10,py+1,8,5); ctx.fillRect(px+3,py+1,8,5);
        ctx.fillStyle='#fff'; ctx.fillRect(px-5,py-22,3,3); ctx.fillRect(px+3,py-22,3,3);
        ctx.fillStyle='#342625'; ctx.fillRect(px-10,py-31,20,5);

        const prompt = document.getElementById('escapeRoomPrompt');
        const button = document.getElementById('escapeRoomInteractBtn');
        if (prompt) prompt.textContent = escapeRoomGuardianDefeated
            ? '🚩 ถึงทางออกแล้ว กด E เพื่อไปด่านถัดไป'
            : (escapeRoomCoinTaken ? '➡️ วิ่งไปทางขวาจนสุดแมพเพื่อเข้าสู่ฉากใหม่' : '🪙 วิ่งและกระโดดไปเก็บเหรียญ');
        if (button) {
            button.innerHTML = escapeRoomGuardianDefeated ? '<span>➡️</span><span>ไปต่อ</span>' : '<span>⬆️</span><span>กระโดด</span>';
        }
        const emptyTitle = document.getElementById('escapeRoomQuestionEmptyTitle');
        const emptyText = document.getElementById('escapeRoomQuestionEmptyText');
        if (emptyTitle) emptyTitle.textContent = escapeRoomGuardianDefeated
            ? 'ตอบแล้ว! ไปต่อได้เลย'
            : (escapeRoomCoinTaken ? 'วิ่งไปสุดแมพเพื่อเปลี่ยนฉาก' : 'เก็บเหรียญเพื่อเปิดคำถาม');
        if (emptyText) emptyText.textContent = escapeRoomGuardianDefeated
            ? (currentQuestionIndex >= questions.length - 1 ? 'ส่งข้อสอบเพื่อจบเกม' : 'ไปยังด่านถัดไปได้เลย')
            : (escapeRoomCoinTaken
                ? 'ใช้ปุ่มขวาหรือ D วิ่งไปจนสุดทาง แล้วเกมจะพาเข้าสู่ฉากสุ่มใหม่พร้อมเหรียญข้อถัดไป'
                : 'คุณควบคุมตัวละครเอง: ใช้ปุ่มซ้าย/ขวาเพื่อวิ่ง และกดกระโดดไปเก็บเหรียญ คำถามจะแสดงตรงนี้');
    }

    function escapeRoomWalk(dx, dy) {
        if (currentExamMode !== 'escape_room' || escapeRoomEncounterActive) return;
        if (dx < 0) escapeRoomPlayer.x -= 34;
        if (dx > 0) escapeRoomPlayer.x += 34;
        escapeRoomPlayer.x = Math.max(24, Math.min(696, escapeRoomPlayer.x));
        if (dy < 0 && escapeRoomPlayer.grounded) {
            escapeRoomPlayer.vy = -10.5;
            escapeRoomPlayer.grounded = false;
        }
        drawEscapeRoom();
    }

    async function interactEscapeRoom() {
        if (currentExamMode !== 'escape_room' || escapeRoomEncounterActive || !questions.length) return;
        const coin = getEscapeRoomCoin();
        if (!escapeRoomCoinTaken && Math.hypot(escapeRoomPlayer.x - coin.x, (escapeRoomPlayer.y - 18) - coin.y) >= 30) {
            const prompt = document.getElementById('escapeRoomPrompt');
            if (prompt) prompt.textContent = 'กระโดดและเคลื่อนที่ไปแตะเหรียญก่อน';
            return;
        }
        escapeRoomCoinTaken = true;
        escapeRoomHeldKeys.left = false;
        escapeRoomHeldKeys.right = false;
        escapeRoomEncounterActive = true;
        stopEscapeRoomAnimation();
        document.getElementById('questionTimer').classList.remove('hidden');
        const encounterView = document.getElementById('classicExamView');
        encounterView.classList.add('escape-room-question-open');
        encounterView.classList.remove('escape-room-enter');
        void encounterView.offsetWidth;
        encounterView.classList.add('escape-room-enter');
        displayQuestion();
    }

    function returnToEscapeRoom(answer = '') {
        clearInterval(timerInterval);
        document.getElementById('questionTimer').classList.add('hidden');
        if (String(answer).trim() !== '') escapeRoomKeysCollected++;

        if (currentQuestionIndex >= questions.length - 1) {
                stopEscapeRoomAnimation();
            escapeRoomEncounterActive = false;
            document.getElementById('classicExamView').classList.remove('escape-room-question-open');
            finishExam();
            return;
        }

        currentQuestionIndex++;
        escapeRoomEncounterActive = false;
        escapeRoomAnswerRecorded = false;
        escapeRoomGuardianDefeated = false;
        // Keep this coin cleared; the next coin appears after the player reaches the map end.
        escapeRoomCoinTaken = true;

        const encounterView = document.getElementById('classicExamView');
        encounterView.classList.remove('escape-room-question-open');
        const map = document.getElementById('escapeRoomMapView');
        if (!map) return;
        updateEscapeRoomProgress();
        drawEscapeRoom();
        startEscapeRoomAnimation();
    }

    function handleEscapeRoomKey(key) {
        if (currentExamMode !== 'escape_room' || escapeRoomEncounterActive) return false;
        if (key === 'ArrowLeft' || key === 'a' || key === 'A') { escapeRoomHeldKeys.left = true; return true; }
        if (key === 'ArrowRight' || key === 'd' || key === 'D') { escapeRoomHeldKeys.right = true; return true; }
        if (key === ' ' || key === 'ArrowUp' || key === 'w' || key === 'W') { escapeRoomWalk(0, -1); return true; }
        if (key === 'e' || key === 'E' || key === 'Enter') { interactEscapeRoom(); return true; }
        return false;
    }
    window.handleEscapeRoomKey = handleEscapeRoomKey;

    document.addEventListener('keyup', (event) => {
        if (['ArrowLeft','a','A'].includes(event.key)) escapeRoomHeldKeys.left = false;
        if (['ArrowRight','d','D'].includes(event.key)) escapeRoomHeldKeys.right = false;
    });

    function toggleExamViewMode() {
        // Locked by teacher - switching disabled
        return;
    }

    function setExamViewMode(mode, showNotice) {
        currentExamMode = mode;
        localStorage.setItem('skj_exam_mode', mode);

        const classicView = document.getElementById('classicExamView');
        const pokemonView = document.getElementById('pokemonExamView');
        const escapeRoomHud = document.getElementById('escapeRoomHud');
        const toggleBtnText = document.getElementById('examModeToggleText');
        const toggleBtnIcon = document.getElementById('examModeToggleIcon');

        if (mode === 'pokemon') {
            if (escapeRoomHud) escapeRoomHud.classList.add('hidden');
            const escapeRoomMap = document.getElementById('escapeRoomMapView');
            if (escapeRoomMap) escapeRoomMap.classList.add('hidden');
            stopEscapeRoomAnimation();
            document.getElementById('questionTimer').classList.remove('hidden');
            classicView.classList.add('hidden');
            pokemonView.classList.remove('hidden');
            if (toggleBtnText) toggleBtnText.textContent = 'โหมดปกติ';
            if (toggleBtnIcon) toggleBtnIcon.textContent = '📝';

            // Start sound synthesizer context gently
            PokeSoundFX.init();
            initPokeMapCanvas();
            updatePokemonExamState();

            if (showNotice) {
                Swal.fire({
                    icon: 'success',
                    title: '🎮 เปลี่ยนเป็นโหมด Pokémon แล้ว!',
                    text: 'เดินผจญภัยบนแผนที่เพื่อเผชิญหน้ากับคำถามโปเกมอน',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } else {
            pokemonView.classList.add('hidden');
            const escapeRoomMap = document.getElementById('escapeRoomMapView');
            if (mode === 'escape_room' && escapeRoomMap) {
                document.getElementById('questionTimer').classList.add('hidden');
                classicView.classList.remove('hidden', 'escape-room-question-open');
                escapeRoomMap.classList.remove('hidden');
                escapeRoomPlayer = { x: 70, y: 354, vy: 0, grounded: true };
                        updateEscapeRoomProgress();
                drawEscapeRoom();
                startEscapeRoomAnimation();
            } else {
                stopEscapeRoomAnimation();
                document.getElementById('questionTimer').classList.remove('hidden');
                classicView.classList.remove('hidden');
                if (escapeRoomMap) escapeRoomMap.classList.add('hidden');
            }
            if (escapeRoomHud) {
                escapeRoomHud.classList.toggle('hidden', mode !== 'escape_room');
            }
            if (toggleBtnText) toggleBtnText.textContent = 'โหมด Pokémon';
            if (toggleBtnIcon) toggleBtnIcon.textContent = '🎮';

            if (showNotice) {
                Swal.fire({
                    icon: 'info',
                    title: '📝 เปลี่ยนเป็นโหมดมาตรฐานแล้ว',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        }
    }

    function switchPokemonSubStage(stage) {
        pokeSubStage = stage;
        const mapStage = document.getElementById('pokeMapStage');
        const battleStage = document.getElementById('pokeBattleStage');
        const btnMap = document.getElementById('btnShowMapStage');
        const btnBattle = document.getElementById('btnShowBattleStage');

        if (stage === 'battle') {
            mapStage.classList.add('hidden');
            battleStage.classList.remove('hidden');
            btnBattle.classList.add('active');
            btnMap.classList.remove('active');
            PokeSoundFX.playEncounter();
        } else {
            battleStage.classList.add('hidden');
            mapStage.classList.remove('hidden');
            btnMap.classList.add('active');
            btnBattle.classList.remove('active');
            drawPokeMap();
        }
    }

    function startPokemonBattleEncounter() {
        // Dramatic encounter transition
        PokeSoundFX.playEncounter();
        const mapCard = document.getElementById('pokeMapStage');
        mapCard.classList.add('screen-shake');
        setTimeout(() => {
            mapCard.classList.remove('screen-shake');
            switchPokemonSubStage('battle');
            displayQuestion();
        }, 300);
    }

    // ===== OVERWORLD MAP ENGINE (Canvas 16-bit RPG) =====
    var pokeCanvas, pokeCtx;
    var pokeMapGrid = { cols: 16, rows: 10, tileSize: 40 };
    var pokePlayer = {
        gridX: 1,
        gridY: 5,
        targetX: 1,
        targetY: 5,
        screenX: 40,
        screenY: 200,
        direction: 'right',
        walkFrame: 0,
        isMoving: false
    };
    var pokeMilestones = [];

    function initPokeMapCanvas() {
        pokeCanvas = document.getElementById('pokeMapCanvas');
        if (!pokeCanvas) return;

        // Dynamically size the grid to be large enough for scattered placement and fit screen
        const total = questions.length || 1;
        const containerWidth = document.getElementById('pokemonExamView').clientWidth || window.innerWidth;
        const baseCols = Math.max(12, Math.floor(containerWidth / 40)); // Dynamic width, min 12 cols
        
        // Ensure enough area for scattering: we want at least 4 tiles per question
        const areaNeeded = total * 4;
        const interiorCols = baseCols - 2;
        const neededRows = Math.ceil(areaNeeded / interiorCols);
        const totalRows = Math.max(12, neededRows + 2); // At least 12 rows

        pokeMapGrid.cols = baseCols;
        pokeMapGrid.rows = totalRows;
        pokeMapGrid.tileSize = 40;

        // Resize canvas to fit the grid
        pokeCanvas.width = pokeMapGrid.cols * pokeMapGrid.tileSize;
        pokeCanvas.height = pokeMapGrid.rows * pokeMapGrid.tileSize;

        pokeCtx = pokeCanvas.getContext('2d');

        // Calculate milestone locations along winding path
        setupPokeMilestones();

        // Tap/click on map to walk
        pokeCanvas.onpointerdown = handleCanvasTapToMove;

        // Start render loop if not started
        if (!window.pokeLoopStarted) {
            window.pokeLoopStarted = true;
            requestAnimationFrame(pokeMapGameLoop);
        }
    }

    function setupPokeMilestones() {
        pokeMilestones = [];
        const total = questions.length || 1;
        const cols = pokeMapGrid.cols;
        const rows = pokeMapGrid.rows;
        const ts = pokeMapGrid.tileSize;

        // Generate scattered waypoints across the grid
        const waypoints = [];
        // Keep track of used grid spaces to avoid overlap
        const usedGrids = new Set();
        // Also don't place on player's spawn point (2,2 roughly)
        usedGrids.add("1,5");
        usedGrids.add("2,5");
        usedGrids.add("3,5");

        // Helper to check if a grid is free (and its immediate neighbors aren't too crowded)
        function isGridFree(x, y) {
            if (usedGrids.has(`${x},${y}`)) return false;
            // Ensure no immediate adjacent to keep some spacing
            if (usedGrids.has(`${x-1},${y}`) || usedGrids.has(`${x+1},${y}`) ||
                usedGrids.has(`${x},${y-1}`) || usedGrids.has(`${x},${y+1}`)) {
                // If it's a very tight map, we might allow it, but try to avoid.
                return Math.random() > 0.8; // 80% chance to reject adjacent
            }
            return true;
        }

        let attempts = 0;
        for (let i = 0; i < total; i++) {
            let placed = false;
            while (!placed && attempts < 1000) {
                attempts++;
                const rx = Math.floor(Math.random() * (cols - 4)) + 2; // 2 to cols-3
                const ry = Math.floor(Math.random() * (rows - 4)) + 2; // 2 to rows-3
                
                if (isGridFree(rx, ry)) {
                    waypoints.push({ x: rx, y: ry });
                    usedGrids.add(`${rx},${ry}`);
                    placed = true;
                }
            }
            if (!placed) {
                // Fallback if map gets too full: just find ANY empty spot
                for (let ry = 2; ry < rows - 2; ry++) {
                    for (let rx = 2; rx < cols - 2; rx++) {
                        if (!usedGrids.has(`${rx},${ry}`)) {
                            waypoints.push({ x: rx, y: ry });
                            usedGrids.add(`${rx},${ry}`);
                            placed = true;
                            break;
                        }
                    }
                    if (placed) break;
                }
            }
        }

        for (let i = 0; i < total; i++) {
            const wp = waypoints[i];
            pokeMilestones.push({
                questionIndex: i,
                gridX: wp.x,
                gridY: wp.y,
                screenX: wp.x * ts + ts / 2,
                screenY: wp.y * ts + ts / 2
            });
        }

        // Initially align player near the left edge
        if (pokeMilestones.length > 0) {
            pokePlayer.gridX = 1;
            pokePlayer.gridY = 5;
            pokePlayer.targetX = 1;
            pokePlayer.targetY = 5;
            pokePlayer.screenX = 1 * ts + ts / 2;
            pokePlayer.screenY = 5 * ts + ts / 2;
        }
    }

    function syncPlayerToCurrentQuestion() {
        if (!pokeMilestones[currentQuestionIndex]) return;
        const targetMs = pokeMilestones[currentQuestionIndex];
        // Place player 1 step left of milestone if possible
        const px = Math.max(1, targetMs.gridX - 1);
        const py = targetMs.gridY;
        pokePlayer.gridX = px;
        pokePlayer.gridY = py;
        pokePlayer.targetX = px;
        pokePlayer.targetY = py;
        pokePlayer.screenX = px * pokeMapGrid.tileSize + pokeMapGrid.tileSize / 2;
        pokePlayer.screenY = py * pokeMapGrid.tileSize + pokeMapGrid.tileSize / 2;
    }

    function handleCanvasTapToMove(e) {
        if (!pokeCanvas) return;
        const rect = pokeCanvas.getBoundingClientRect();
        const scaleX = pokeCanvas.width / rect.width;
        const scaleY = pokeCanvas.height / rect.height;
        const clickX = (e.clientX - rect.left) * scaleX;
        const clickY = (e.clientY - rect.top) * scaleY;

        const targetGridX = Math.floor(clickX / pokeMapGrid.tileSize);
        const targetGridY = Math.floor(clickY / pokeMapGrid.tileSize);

        // Move one step toward clicked tile
        const dx = targetGridX - pokePlayer.gridX;
        const dy = targetGridY - pokePlayer.gridY;

        if (Math.abs(dx) > Math.abs(dy)) {
            pokeMapWalk(dx > 0 ? 'right' : 'left');
        } else if (dy !== 0) {
            pokeMapWalk(dy > 0 ? 'down' : 'up');
        } else {
            // Clicked directly on player's tile -> interact
            pokeMapInteract();
        }
    }

    function pokeMapWalk(dir) {
        let nx = pokePlayer.gridX;
        let ny = pokePlayer.gridY;

        if (dir === 'up') { ny--; pokePlayer.direction = 'up'; }
        else if (dir === 'down') { ny++; pokePlayer.direction = 'down'; }
        else if (dir === 'left') { nx--; pokePlayer.direction = 'left'; }
        else if (dir === 'right') { nx++; pokePlayer.direction = 'right'; }

        // Boundaries check
        if (nx >= 1 && nx < pokeMapGrid.cols - 1 && ny >= 1 && ny < pokeMapGrid.rows - 1) {
            pokePlayer.gridX = nx;
            pokePlayer.gridY = ny;
            pokePlayer.walkFrame = (pokePlayer.walkFrame + 1) % 4;
            PokeSoundFX.playStep();

            // Check if stepped on milestone
            checkMilestoneCollision();
        }
    }

    function pokeMapInteract() {
        checkMilestoneCollision();
    }

    function checkMilestoneCollision() {
        // Find if player is standing on any milestone
        const m = pokeMilestones.find(ms => ms.gridX === pokePlayer.gridX && ms.gridY === pokePlayer.gridY);
        if (!m) return;

        const idx = m.questionIndex;

        if (answeredQuestions.includes(idx)) {
            // Already answered
            Swal.fire({
                title: 'สำเร็จแล้ว!',
                text: 'คุณปราบโปเกมอนตัวนี้ไปแล้ว!',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        const q = questions[idx];
        if (q && q.type === 'writing') {
            // Check if all multiple choice are done
            const writingCount = questions.filter(quest => quest.type === 'writing').length;
            const requiredMcqCount = questions.length - writingCount;
            const answeredMcqCount = answeredQuestions.filter(ansIdx => questions[ansIdx].type !== 'writing').length;

            if (answeredMcqCount < requiredMcqCount) {
                Swal.fire({
                    title: 'ยังเข้าไม่ได้!',
                    text: 'ต้องปราบโปเกมอนระดับปรนัยให้หมดก่อน ถึงจะเข้าสู้บอสข้อเขียนได้',
                    icon: 'warning',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }
        }

        // Set this question as current, update UI, and start battle
        currentQuestionIndex = idx;
        startPokemonBattleEncounter();
    }

    // Global Key Listener for Map
    window.handlePokeMapKey = function(key) {
        if (currentExamMode !== 'pokemon' || pokeSubStage !== 'map') return false;

        if (key === 'ArrowUp' || key === 'w' || key === 'W') {
            pokeMapWalk('up');
            return true;
        }
        if (key === 'ArrowDown' || key === 's' || key === 'S') {
            pokeMapWalk('down');
            return true;
        }
        if (key === 'ArrowLeft' || key === 'a' || key === 'A') {
            pokeMapWalk('left');
            return true;
        }
        if (key === 'ArrowRight' || key === 'd' || key === 'D') {
            pokeMapWalk('right');
            return true;
        }
        if (key === ' ' || key === 'Enter') {
            pokeMapInteract();
            return true;
        }
        return false;
    };

    function pokeMapGameLoop() {
        if (currentExamMode === 'pokemon' && pokeSubStage === 'map') {
            drawPokeMap();
        }
        requestAnimationFrame(pokeMapGameLoop);
    }

    // ===== AUTHENTIC POKÉMON GBA OVERWORLD ENGINE =====

    // Helper: Determine if tile is part of Route 1 winding path
    function isPokemonPath(c, r, cols, rows) {
        // Main horizontal route around row 5
        if (r === 5 && c >= 1 && c <= cols - 2) return true;
        // Vertical path crossing
        const midCol = Math.floor(cols / 2);
        if ((c === midCol || c === midCol - 1) && r >= 2 && r <= rows - 3) return true;
        // Branch to top-right
        if (c >= cols - 5 && r === 3) return true;
        // Branch to bottom-left
        if (c === 3 && r >= 5 && r <= rows - 3) return true;
        return false;
    }

    // Helper: Determine if tile is Wild Tall Grass (พงหญ้าจับโปเกมอน)
    function isPokemonTallGrass(c, r, cols, rows) {
        if (isPokemonPath(c, r, cols, rows)) return false;
        // Natural tall grass patches across the route
        const seed = (c * 7 + r * 13) % 17;
        return seed === 2 || seed === 5 || seed === 9 || seed === 14;
    }

    // 1. Draw Authentic Pokémon GBA Oak Tree
    function drawPokemonOakTree(ctx, x, y, size) {
        ctx.save();
        // Ground shadow
        ctx.fillStyle = 'rgba(15, 45, 15, 0.45)';
        ctx.beginPath();
        ctx.ellipse(x + size / 2, y + size - 3, size * 0.42, size * 0.18, 0, 0, Math.PI * 2);
        ctx.fill();

        // Wood Trunk
        const trunkW = size * 0.28;
        const trunkH = size * 0.36;
        const trunkX = x + (size - trunkW) / 2;
        const trunkY = y + size - trunkH - 2;

        ctx.fillStyle = '#6e3a15';
        ctx.fillRect(trunkX, trunkY, trunkW, trunkH);
        ctx.fillStyle = '#8a4b1c';
        ctx.fillRect(trunkX + 2, trunkY, trunkW * 0.45, trunkH);
        ctx.fillStyle = '#4a250a';
        ctx.fillRect(trunkX + trunkW - 2, trunkY, 2, trunkH);

        // Canopy Layer 1 (Dark Foliage Outline & Deep Shade)
        ctx.fillStyle = '#0f380f';
        ctx.beginPath();
        ctx.arc(x + size / 2, y + size * 0.44, size * 0.45, 0, Math.PI * 2);
        ctx.fill();

        // Canopy Layer 2 (Forest Green Body)
        ctx.fillStyle = '#1c7524';
        ctx.beginPath();
        ctx.arc(x + size / 2, y + size * 0.42, size * 0.42, 0, Math.PI * 2);
        ctx.fill();

        // Canopy Layer 3 (Vibrant Leaf Clusters)
        ctx.fillStyle = '#2ea438';
        ctx.beginPath();
        ctx.arc(x + size * 0.42, y + size * 0.38, size * 0.33, 0, Math.PI * 2);
        ctx.arc(x + size * 0.60, y + size * 0.45, size * 0.25, 0, Math.PI * 2);
        ctx.arc(x + size * 0.48, y + size * 0.52, size * 0.24, 0, Math.PI * 2);
        ctx.fill();

        // Canopy Layer 4 (Sunlight Highlight Top-Left)
        ctx.fillStyle = '#60cf69';
        ctx.beginPath();
        ctx.arc(x + size * 0.38, y + size * 0.32, size * 0.22, 0, Math.PI * 2);
        ctx.fill();

        // Highlight Leaf Pixels
        ctx.fillStyle = '#9cf5a3';
        ctx.fillRect(x + size * 0.34, y + size * 0.28, 3, 3);
        ctx.fillRect(x + size * 0.42, y + size * 0.25, 4, 3);

        ctx.restore();
    }

    // 2. Draw Authentic Pokémon Tall Grass (พงหญ้าสูงสำหรับจับโปเกมอน)
    function drawPokemonTallGrass(ctx, x, y, size) {
        ctx.save();
        // Deep moss soil under grass
        ctx.fillStyle = '#265e20';
        ctx.fillRect(x, y, size, size);

        // Dark layered grass blades
        ctx.fillStyle = '#36852a';
        for (let i = 0; i < 4; i++) {
            const bx = x + i * (size / 4) + 1;
            ctx.beginPath();
            ctx.moveTo(bx, y + size);
            ctx.lineTo(bx + 4, y + 8);
            ctx.lineTo(bx + 8, y + size);
            ctx.fill();
        }

        // Vibrant emerald grass blades
        ctx.fillStyle = '#4bb83a';
        for (let i = 0; i < 4; i++) {
            const bx = x + i * (size / 4) + 2;
            ctx.beginPath();
            ctx.moveTo(bx + 1, y + size);
            ctx.lineTo(bx + 4, y + 10);
            ctx.lineTo(bx + 7, y + size);
            ctx.fill();
        }

        // Bright sunlight blade tips
        ctx.fillStyle = '#82f06e';
        for (let i = 0; i < 4; i++) {
            const bx = x + i * (size / 4) + 3;
            ctx.fillRect(bx + 1, y + 8, 2, 4);
        }
        ctx.restore();
    }

    // 3. Draw Wild Flowers
    function drawPokemonFlowers(ctx, x, y, color) {
        ctx.save();
        ctx.fillStyle = color;
        // 4 petals
        ctx.beginPath();
        ctx.arc(x - 2.5, y, 2.5, 0, Math.PI * 2);
        ctx.arc(x + 2.5, y, 2.5, 0, Math.PI * 2);
        ctx.arc(x, y - 2.5, 2.5, 0, Math.PI * 2);
        ctx.arc(x, y + 2.5, 2.5, 0, Math.PI * 2);
        ctx.fill();
        // Yellow center
        ctx.fillStyle = '#fef08a';
        ctx.beginPath();
        ctx.arc(x, y, 1.8, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }

    // 4. Draw Wooden Fence
    function drawPokemonFence(ctx, x, y, size) {
        ctx.save();
        // Posts
        ctx.fillStyle = '#6b3710';
        ctx.fillRect(x + 4, y + 8, 6, size - 12);
        ctx.fillRect(x + size - 10, y + 8, 6, size - 12);
        // Post Caps
        ctx.fillStyle = '#8b4b1a';
        ctx.fillRect(x + 3, y + 6, 8, 3);
        ctx.fillRect(x + size - 11, y + 6, 8, 3);
        // Rails
        ctx.fillStyle = '#92400e';
        ctx.fillRect(x, y + 13, size, 5);
        ctx.fillRect(x, y + 23, size, 5);
        // Highlight
        ctx.fillStyle = '#b45309';
        ctx.fillRect(x, y + 13, size, 1.5);
        ctx.fillRect(x, y + 23, size, 1.5);
        ctx.restore();
    }

    // 5. Draw Route Signpost
    function drawPokemonSignpost(ctx, x, y) {
        ctx.save();
        // Post
        ctx.fillStyle = '#5c3210';
        ctx.fillRect(x + 17, y + 16, 6, 20);
        // Wooden Board
        ctx.fillStyle = '#a16207';
        ctx.fillRect(x + 6, y + 8, 28, 16);
        ctx.strokeStyle = '#451a03';
        ctx.lineWidth = 1.5;
        ctx.strokeRect(x + 6, y + 8, 28, 16);
        // White writing line
        ctx.fillStyle = '#fef3c7';
        ctx.fillRect(x + 9, y + 12, 22, 3);
        ctx.fillRect(x + 11, y + 17, 18, 2);
        ctx.restore();
    }

    // Preload Follower Pixel Pikachu Sprite
    const miniPikaImg = new Image();
    miniPikaImg.crossOrigin = 'anonymous';
    miniPikaImg.src = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png';
    let miniPikaLoaded = false;
    miniPikaImg.onload = () => { miniPikaLoaded = true; };

    function drawPokeMap() {
        if (!pokeCtx) return;
        const w = pokeCanvas.width;
        const h = pokeCanvas.height;
        const ts = pokeMapGrid.tileSize;
        const cols = pokeMapGrid.cols;
        const rows = pokeMapGrid.rows;

        // 1. Lush Emerald Base Grass
        pokeCtx.fillStyle = '#4ea12a';
        pokeCtx.fillRect(0, 0, w, h);

        // 2. Render Authentic Pokémon Terrain Tiles
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < cols; c++) {
                const x = c * ts;
                const y = r * ts;
                const isBorder = (r === 0 || r === rows - 1 || c === 0 || c === cols - 1);
                const isPath = isPokemonPath(c, r, cols, rows);
                const isTallGrass = isPokemonTallGrass(c, r, cols, rows);

                if (isBorder) {
                    // Border: Lush Oak Trees with fence openings
                    if (r === 5 && c === 0) {
                        // Route entrance opening!
                        pokeCtx.fillStyle = '#dca35a';
                        pokeCtx.fillRect(x, y, ts, ts);
                    } else if (r === 0 && (c === 4 || c === 5)) {
                        // Wooden fence top
                        pokeCtx.fillStyle = '#52a82e';
                        pokeCtx.fillRect(x, y, ts, ts);
                        drawPokemonFence(pokeCtx, x, y, ts);
                    } else {
                        drawPokemonOakTree(pokeCtx, x, y, ts);
                    }
                } else if (isPath) {
                    // Sandy Dirt Road (Route 1)
                    pokeCtx.fillStyle = '#dca35a';
                    pokeCtx.fillRect(x, y, ts, ts);

                    // Dirt shading and texture
                    pokeCtx.fillStyle = '#c58d45';
                    pokeCtx.fillRect(x, y + ts - 3, ts, 3);
                    pokeCtx.fillRect(x + ts - 3, y, 3, ts);

                    // Stepping pebbles
                    if ((c * 5 + r * 7) % 3 === 0) {
                        pokeCtx.fillStyle = '#f0c78a';
                        pokeCtx.fillRect(x + 8, y + 10, 4, 3);
                        pokeCtx.fillRect(x + 22, y + 24, 3, 3);
                        pokeCtx.fillStyle = '#9e6d2a';
                        pokeCtx.fillRect(x + 12, y + 13, 3, 2);
                    }

                    // Green grass fringe encroaching on path
                    pokeCtx.fillStyle = '#52a82e';
                    if (!isPokemonPath(c, r - 1, cols, rows)) {
                        pokeCtx.fillRect(x + 4, y, 6, 2);
                        pokeCtx.fillRect(x + 20, y, 8, 3);
                    }
                    if (!isPokemonPath(c, r + 1, cols, rows)) {
                        pokeCtx.fillRect(x + 8, y + ts - 3, 7, 3);
                        pokeCtx.fillRect(x + 24, y + ts - 2, 6, 2);
                    }
                } else if (isTallGrass) {
                    // Wild Encounter Tall Grass
                    drawPokemonTallGrass(pokeCtx, x, y, ts);
                } else {
                    // Vibrant Lawn Lawn (Alternating checker tones)
                    pokeCtx.fillStyle = ((r + c) % 2 === 0) ? '#55ab30' : '#4ea02a';
                    pokeCtx.fillRect(x, y, ts, ts);

                    // Micro grass blades
                    if ((c * 3 + r * 7) % 4 === 0) {
                        pokeCtx.fillStyle = '#71d044';
                        pokeCtx.fillRect(x + 10, y + 16, 2, 6);
                        pokeCtx.fillRect(x + 13, y + 13, 2, 9);
                        pokeCtx.fillRect(x + 16, y + 17, 2, 5);
                    }

                    // Wild Flowers on special tiles
                    if ((c * 11 + r * 17) % 9 === 0) {
                        const flowerColors = ['#ef4444', '#fde047', '#38bdf8', '#f472b6'];
                        const fCol = flowerColors[(c + r) % flowerColors.length];
                        drawPokemonFlowers(pokeCtx, x + 15, y + 18, fCol);
                        drawPokemonFlowers(pokeCtx, x + 25, y + 24, fCol);
                    }
                }
            }
        }

        // 3. Draw Route 1 Signpost near player spawn
        drawPokemonSignpost(pokeCtx, 1 * ts + 6, 4 * ts + 4);

        // 4. Draw Question Milestone Markers (3D Realistic Pokéballs)
        pokeMilestones.forEach((m, idx) => {
            const isCompleted = answeredQuestions.includes(idx);
            
            // Check writing lock
            const q = questions[idx];
            let isLocked = false;
            if (q && q.type === 'writing' && !isCompleted) {
                const writingCount = questions.filter(quest => quest.type === 'writing').length;
                const requiredMcqCount = questions.length - writingCount;
                const answeredMcqCount = answeredQuestions.filter(ansIdx => questions[ansIdx].type !== 'writing').length;
                if (answeredMcqCount < requiredMcqCount) {
                    isLocked = true;
                }
            }

            pokeCtx.save();
            pokeCtx.translate(m.screenX, m.screenY);

            const radius = 16;

            // Ground Shadow beneath Pokéball
            pokeCtx.fillStyle = 'rgba(10, 30, 10, 0.45)';
            pokeCtx.beginPath();
            pokeCtx.ellipse(0, radius + 2, radius * 0.9, 5, 0, 0, Math.PI * 2);
            pokeCtx.fill();

            if (isCompleted) {
                // Completed: Shiny Golden Gym Badge (Star of Victory)
                pokeCtx.fillStyle = '#fbbf24';
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, radius, 0, Math.PI * 2);
                pokeCtx.fill();
                pokeCtx.strokeStyle = '#b45309';
                pokeCtx.lineWidth = 2.5;
                pokeCtx.stroke();

                // Shiny specular highlight
                pokeCtx.fillStyle = '#fef08a';
                pokeCtx.beginPath();
                pokeCtx.arc(-4, -4, 5, 0, Math.PI * 2);
                pokeCtx.fill();

                // Star icon
                pokeCtx.fillStyle = '#78350f';
                pokeCtx.font = 'bold 15px sans-serif';
                pokeCtx.textAlign = 'center';
                pokeCtx.textBaseline = 'middle';
                pokeCtx.fillText('★', 0, 1);

                // Small "DONE" tag
                pokeCtx.fillStyle = '#15803d';
                pokeCtx.beginPath();
                pokeCtx.roundRect(-16, -radius - 12, 32, 11, 4);
                pokeCtx.fill();
                pokeCtx.fillStyle = '#ffffff';
                pokeCtx.font = 'bold 8px sans-serif';
                pokeCtx.fillText('✓ พิชิต', 0, -radius - 7);

            } else if (isLocked) {
                // Locked (Writing question): Dark Armored Steel Sphere with Heavy Padlock
                pokeCtx.fillStyle = '#334155';
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, radius, 0, Math.PI * 2);
                pokeCtx.fill();
                pokeCtx.strokeStyle = '#0f172a';
                pokeCtx.lineWidth = 2.5;
                pokeCtx.stroke();

                // Lock icon (emoji)
                pokeCtx.fillStyle = '#e2e8f0';
                pokeCtx.font = 'bold 13px sans-serif';
                pokeCtx.textAlign = 'center';
                pokeCtx.textBaseline = 'middle';
                pokeCtx.fillText('🔒', 0, 1);
                
                // Floating Lock Badge
                pokeCtx.fillStyle = '#475569';
                pokeCtx.beginPath();
                pokeCtx.roundRect(-18, -radius - 12, 36, 11, 4);
                pokeCtx.fill();
                pokeCtx.fillStyle = '#f8fafc';
                pokeCtx.font = 'bold 8px sans-serif';
                pokeCtx.fillText(`ข้อ ${idx + 1} ล็อก`, 0, -radius - 7);

            } else {
                // Available: Authentic 3D Shaded Pokéball
                const isNear = (pokePlayer.gridX === m.gridX && pokePlayer.gridY === m.gridY);
                
                // Pulsing energy beacon ring
                const pulse = Math.sin(Date.now() / 180) * 4;
                pokeCtx.strokeStyle = isNear ? 'rgba(56, 189, 248, 0.9)' : 'rgba(34, 197, 94, 0.5)';
                pokeCtx.lineWidth = isNear ? 3 : 2;
                pokeCtx.beginPath();
                pokeCtx.arc(0, radius + 2, radius + (isNear ? 8 : 4) + pulse, 0, Math.PI * 2);
                pokeCtx.stroke();

                // 1. Top Half: Vivid Crimson with 3D Radial Gradient
                const topGrad = pokeCtx.createRadialGradient(-4, -6, 2, 0, 0, radius);
                topGrad.addColorStop(0, '#f87171');
                topGrad.addColorStop(0.5, '#dc2626');
                topGrad.addColorStop(1, '#991b1b');
                pokeCtx.fillStyle = topGrad;
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, radius, Math.PI, 0, false);
                pokeCtx.closePath();
                pokeCtx.fill();

                // Specular curved gloss on upper dome
                pokeCtx.fillStyle = 'rgba(255, 255, 255, 0.6)';
                pokeCtx.beginPath();
                pokeCtx.ellipse(-4, -8, 6, 3, -Math.PI / 6, 0, Math.PI * 2);
                pokeCtx.fill();

                // 2. Bottom Half: Clean White with 3D Ambient Shade
                const botGrad = pokeCtx.createRadialGradient(-3, 4, 2, 0, 2, radius);
                botGrad.addColorStop(0, '#ffffff');
                botGrad.addColorStop(0.7, '#e2e8f0');
                botGrad.addColorStop(1, '#94a3b8');
                pokeCtx.fillStyle = botGrad;
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, radius, 0, Math.PI, false);
                pokeCtx.closePath();
                pokeCtx.fill();

                // 3. Black Center Band
                pokeCtx.strokeStyle = '#0f172a';
                pokeCtx.lineWidth = 3;
                pokeCtx.beginPath();
                pokeCtx.moveTo(-radius, 0);
                pokeCtx.lineTo(radius, 0);
                pokeCtx.stroke();

                // 4. Center Button with Metallic Core
                pokeCtx.fillStyle = '#0f172a';
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, 5.5, 0, Math.PI * 2);
                pokeCtx.fill();

                pokeCtx.fillStyle = '#ffffff';
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, 3.5, 0, Math.PI * 2);
                pokeCtx.fill();

                // Inner core LED (cyan glowing dot)
                pokeCtx.fillStyle = '#38bdf8';
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, 1.8, 0, Math.PI * 2);
                pokeCtx.fill();

                // Outer crisp sphere outline
                pokeCtx.strokeStyle = '#0f172a';
                pokeCtx.lineWidth = 1.5;
                pokeCtx.beginPath();
                pokeCtx.arc(0, 0, radius, 0, Math.PI * 2);
                pokeCtx.stroke();

                // Floating Info Badge above Pokéball
                pokeCtx.fillStyle = isNear ? '#0284c7' : '#1e293b';
                pokeCtx.beginPath();
                pokeCtx.roundRect(-14, -radius - 13, 28, 11, 4);
                pokeCtx.fill();
                pokeCtx.strokeStyle = isNear ? '#38bdf8' : '#64748b';
                pokeCtx.lineWidth = 1;
                pokeCtx.stroke();

                pokeCtx.fillStyle = '#ffffff';
                pokeCtx.font = 'bold 8px sans-serif';
                pokeCtx.textAlign = 'center';
                pokeCtx.textBaseline = 'middle';
                pokeCtx.fillText(`ข้อ ${idx + 1}`, 0, -radius - 8);

                // If player is standing directly on it: Bouncing Action Arrow
                if (isNear) {
                    const arrowY = -radius - 20 + pulse;
                    pokeCtx.fillStyle = '#facc15';
                    pokeCtx.beginPath();
                    pokeCtx.moveTo(0, arrowY + 5);
                    pokeCtx.lineTo(-5, arrowY);
                    pokeCtx.lineTo(5, arrowY);
                    pokeCtx.closePath();
                    pokeCtx.fill();
                }
            }
            pokeCtx.restore();
        });

        // 5. Smooth Player Coordinates towards target
        const targetScreenX = pokePlayer.gridX * ts + ts / 2;
        const targetScreenY = pokePlayer.gridY * ts + ts / 2;
        pokePlayer.screenX += (targetScreenX - pokePlayer.screenX) * 0.35;
        pokePlayer.screenY += (targetScreenY - pokePlayer.screenY) * 0.35;

        // 6. Draw Follower Pikachu Sprite (trailing behind player)
        const pikaOffset = (pokePlayer.direction === 'left') ? 18 : -18;
        drawMiniPikachu(pokeCtx, pokePlayer.screenX + pikaOffset, pokePlayer.screenY + 4, pokePlayer.direction);

        // 7. Draw Trainer Ash Ketchum Sprite
        drawMiniTrainer(pokeCtx, pokePlayer.screenX, pokePlayer.screenY, pokePlayer.direction, pokePlayer.walkFrame);
    }

    function drawMiniTrainer(ctx, x, y, dir, frame) {
        ctx.save();
        ctx.translate(x, y);

        // Ground shadow
        ctx.fillStyle = 'rgba(0,0,0,0.32)';
        ctx.beginPath();
        ctx.ellipse(0, 13, 10, 4.5, 0, 0, Math.PI * 2);
        ctx.fill();

        const bob = (frame % 2 === 1) ? -2 : 0;
        const legSwing = (frame % 2 === 1) ? 3 : -3;

        // 1. Pants & Shoes (Classic Jeans + Red/White Sneakers)
        if (dir === 'left' || dir === 'right') {
            const flip = (dir === 'left') ? -1 : 1;
            ctx.fillStyle = '#1e3a8a';
            ctx.fillRect(-3 * flip + legSwing, 4 + bob, 5, 8);
            ctx.fillRect(1 * flip - legSwing, 4 + bob, 5, 8);
            // Red Sneakers with white sole
            ctx.fillStyle = '#ef4444';
            ctx.fillRect(-4 * flip + legSwing, 11 + bob, 7, 3);
            ctx.fillRect(0 * flip - legSwing, 11 + bob, 7, 3);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(-4 * flip + legSwing, 13 + bob, 7, 1.5);
            ctx.fillRect(0 * flip - legSwing, 13 + bob, 7, 1.5);
        } else {
            ctx.fillStyle = '#1e3a8a';
            ctx.fillRect(-6, 4 + bob + (dir === 'up' ? legSwing : 0), 5, 8);
            ctx.fillRect(1, 4 + bob + (dir === 'up' ? -legSwing : 0), 5, 8);
            ctx.fillStyle = '#ef4444';
            ctx.fillRect(-7, 11 + bob, 6, 3);
            ctx.fillRect(1, 11 + bob, 6, 3);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(-7, 13 + bob, 6, 1.5);
            ctx.fillRect(1, 13 + bob, 6, 1.5);
        }

        // 2. Green Adventure Backpack (visible from up, left, right)
        if (dir === 'up') {
            ctx.fillStyle = '#15803d';
            ctx.fillRect(-7, -6 + bob, 14, 10);
            ctx.fillStyle = '#22c55e';
            ctx.fillRect(-5, -4 + bob, 10, 6);
        } else if (dir === 'left') {
            ctx.fillStyle = '#15803d';
            ctx.fillRect(3, -6 + bob, 5, 9);
        } else if (dir === 'right') {
            ctx.fillStyle = '#15803d';
            ctx.fillRect(-8, -6 + bob, 5, 9);
        }

        // 3. Classic Indigo League Jacket (Blue vest with open white collar & yellow trim)
        ctx.fillStyle = '#2563eb';
        ctx.fillRect(-7, -6 + bob, 14, 11);
        if (dir !== 'up') {
            // White open collar & dark undershirt
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(-3, -6 + bob, 6, 6);
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(-2, -3 + bob, 4, 4);
            // Yellow pocket trim
            ctx.fillStyle = '#facc15';
            ctx.fillRect(-6, 2 + bob, 3, 2);
            ctx.fillRect(3, 2 + bob, 3, 2);
        }

        // 4. Head, Spiky Hair & Face
        // Spiky Black Hair
        ctx.fillStyle = '#0f172a';
        ctx.beginPath();
        ctx.arc(0, -11 + bob, 9, 0, Math.PI * 2);
        ctx.fill();
        // Hair tufts
        ctx.beginPath();
        ctx.moveTo(-9, -11 + bob); ctx.lineTo(-12, -7 + bob); ctx.lineTo(-7, -5 + bob);
        ctx.moveTo(9, -11 + bob); ctx.lineTo(12, -7 + bob); ctx.lineTo(7, -5 + bob);
        ctx.fill();

        // Warm Skin Face
        ctx.fillStyle = '#fed7aa';
        ctx.beginPath();
        ctx.arc(0, -11 + bob, 7, 0, Math.PI * 2);
        ctx.fill();

        // Face Details
        if (dir === 'down') {
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(-4, -12 + bob, 2, 3);
            ctx.fillRect(2, -12 + bob, 2, 3);
            // Z marks on cheeks
            ctx.strokeStyle = '#ea580c';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(-5, -8 + bob); ctx.lineTo(-3, -7 + bob);
            ctx.moveTo(3, -7 + bob); ctx.lineTo(5, -8 + bob);
            ctx.stroke();
        } else if (dir === 'left') {
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(-4, -12 + bob, 2, 3);
        } else if (dir === 'right') {
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(2, -12 + bob, 2, 3);
        }

        // 5. Iconic Red Pokémon Cap & Emblem
        ctx.fillStyle = '#ef4444';
        ctx.beginPath();
        ctx.arc(0, -14 + bob, 8, Math.PI, 0, false);
        ctx.fill();

        if (dir !== 'up') {
            // White front cap dome
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(0, -15 + bob, 4.5, Math.PI, 0, false);
            ctx.fill();
            // Green Pokémon League Semi-circle logo
            ctx.fillStyle = '#16a34a';
            ctx.beginPath();
            ctx.arc(0, -16 + bob, 2, 0, Math.PI * 2);
            ctx.fill();
        }

        // White Visor
        ctx.fillStyle = '#ffffff';
        if (dir === 'left') {
            ctx.fillRect(-11, -14 + bob, 8, 3);
        } else if (dir === 'right') {
            ctx.fillRect(3, -14 + bob, 8, 3);
        } else if (dir === 'down') {
            ctx.fillRect(-5, -13 + bob, 10, 3);
        }

        ctx.restore();
    }

    function drawMiniPikachu(ctx, x, y, dir = 'right') {
        ctx.save();
        // Ground Shadow
        ctx.fillStyle = 'rgba(0,0,0,0.3)';
        ctx.beginPath();
        ctx.ellipse(x, y + 8, 8, 3.5, 0, 0, Math.PI * 2);
        ctx.fill();

        if (miniPikaLoaded) {
            ctx.imageSmoothingEnabled = false;
            const size = 32;
            ctx.translate(x, y);
            if (dir === 'left') {
                ctx.scale(-1, 1);
            }
            ctx.drawImage(miniPikaImg, -size / 2, -size / 2 - 2, size, size);
        } else {
            // Fallback crisp vector
            ctx.translate(x, y);
            if (dir === 'left') ctx.scale(-1, 1);

            // Yellow Body
            ctx.fillStyle = '#ffcb05';
            ctx.beginPath();
            ctx.ellipse(0, 0, 7, 6, 0, 0, Math.PI * 2);
            ctx.fill();

            // Ears with black tips
            ctx.fillStyle = '#ffcb05';
            ctx.beginPath();
            ctx.moveTo(-4, -4); ctx.lineTo(-7, -10); ctx.lineTo(-2, -6);
            ctx.fill();
            ctx.fillStyle = '#000';
            ctx.beginPath();
            ctx.moveTo(-5, -7); ctx.lineTo(-7, -10); ctx.lineTo(-4, -8);
            ctx.fill();

            ctx.fillStyle = '#ffcb05';
            ctx.beginPath();
            ctx.moveTo(4, -4); ctx.lineTo(7, -10); ctx.lineTo(2, -6);
            ctx.fill();
            ctx.fillStyle = '#000';
            ctx.beginPath();
            ctx.moveTo(5, -7); ctx.lineTo(7, -10); ctx.lineTo(4, -8);
            ctx.fill();

            // Red Cheek
            ctx.fillStyle = '#ef4444';
            ctx.beginPath();
            ctx.arc(-3, 0, 1.8, 0, Math.PI * 2);
            ctx.arc(3, 0, 1.8, 0, Math.PI * 2);
            ctx.fill();

            // Lightning Tail
            ctx.strokeStyle = '#ffcb05';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(4, 2); ctx.lineTo(8, -1); ctx.lineTo(6, -4); ctx.lineTo(11, -7);
            ctx.stroke();
        }

        ctx.restore();
    }

    // ===== BATTLE ARENA ENGINE (สนามประลองโปเกมอน) =====
    function updatePokemonExamState() {
        if (!questions || questions.length === 0) return;
        const total = questions.length;
        const current = currentQuestionIndex + 1;
        const q = questions[currentQuestionIndex];
        if (!q) return;

        // 1. Update Route, Milestone & Habitat
        const enemyData = POKE_ENEMIES[currentQuestionIndex % POKE_ENEMIES.length];
        const habitat = enemyData.habitat || 'forest';
        const habitatName = enemyData.habitatName || 'ป่าเขียวขจี 🌲';

        const routeTitle = document.getElementById('pokeRouteTitle');
        const milestoneText = document.getElementById('pokeMilestoneText');
        if (routeTitle) routeTitle.textContent = `ถนนสายวิชาการ (Route ${current}) • ${habitatName}`;
        if (milestoneText) milestoneText.textContent = `จุดข้อสอบที่ ${current} / ${total}`;

        // Switch Arena Background Theme & Habitat Badge
        const battleArena = document.getElementById('pokeBattleStage');
        if (battleArena) {
            battleArena.classList.remove('arena-forest', 'arena-mountain', 'arena-river');
            battleArena.classList.add(`arena-${habitat}`);
        }
        const habitatBadge = document.getElementById('arenaHabitatBadge');
        if (habitatBadge) {
            const icon = (habitat === 'forest') ? '🌲' : (habitat === 'mountain' ? '⛰️' : '🌊');
            const iconEl = document.getElementById('arenaHabitatIcon');
            const nameEl = document.getElementById('arenaHabitatName');
            if (iconEl) iconEl.textContent = icon;
            if (nameEl) nameEl.textContent = habitatName;
        }

        // 2. Select Enemy for Current Question
        const enemyName = document.getElementById('pokeEnemyName');
        const enemyLv = document.getElementById('pokeEnemyLv');
        const enemySprite = document.getElementById('pokeEnemySprite');
        const enemyHpFill = document.getElementById('pokeEnemyHpFill');

        if (enemyName) enemyName.innerHTML = `<span>${enemyData.icon}</span> ${enemyData.name}`;
        if (enemyLv) enemyLv.textContent = `Lv. ${5 + currentQuestionIndex * 2}`;
        if (enemySprite) {
            enemySprite.innerHTML = enemyData.sprite;
            enemySprite.classList.remove('enemy-hit-anim', 'enemy-fainted');
            enemySprite.style.opacity = '1';
        }
        const skillEffect = document.getElementById('pokeSkillEffect');
        if (skillEffect) {
            skillEffect.className = 'poke-skill-effect';
        }
        if (enemyHpFill) {
            enemyHpFill.style.width = '100%';
            enemyHpFill.style.backgroundColor = '#10b981';
        }
        const enemyHpText = document.getElementById('pokeEnemyHpText');
        if (enemyHpText) {
            enemyHpText.textContent = '100/100';
            enemyHpText.style.color = '#cbd5e1';
        }

        // 3. Update Player Pikachu Info
        const playerSprite = document.getElementById('pokePlayerSprite');
        const playerLv = document.getElementById('pokePlayerLv');
        const playerExpFill = document.getElementById('pokePlayerExpFill');
        const playerHpFill = document.getElementById('pokePlayerHpFill');
        const playerHpText = document.getElementById('pokePlayerHpText');

        if (playerSprite) {
            playerSprite.innerHTML = POKE_SPRITES.pikachuBack;
            playerSprite.classList.remove('player-attack-anim', 'player-hit-anim');
        }
        if (playerLv) playerLv.textContent = `Lv. ${5 + Math.floor(currentQuestionIndex * 1.5)}`;
        
        // Reset player HP to 100 on each encounter
        pokePlayerHp = 100;
        isEnemyAttacking = false;
        lastEnemyAttackTime = Date.now();
        if (playerHpFill) {
            playerHpFill.style.width = '100%';
            playerHpFill.style.backgroundColor = '#10b981';
        }
        if (playerHpText) {
            playerHpText.textContent = '100/100';
            playerHpText.style.color = '#cbd5e1';
        }
        if (playerExpFill) {
            const expPercent = Math.min(100, Math.floor(((current - 1) / total) * 100));
            playerExpFill.style.width = `${expPercent}%`;
        }

        // 4. Update Battle Dialog with Question
        const qTitle = document.getElementById('pokeBattleQTitle');
        const qText = document.getElementById('pokeBattleQText');
        const qImage = document.getElementById('pokeBattleQImage');
        const narrator = document.getElementById('pokeBattleNarrator');

        if (qTitle) {
            const typeLabel = (q.type === 'writing') ? 'อัตนัย (ข้อเขียน)' : 'ปรนัย (เลือกตอบ)';
            qTitle.innerHTML = `<span>📜</span> คำถามข้อที่ ${current} / ${total} • ${typeLabel}`;
        }
        if (qText) {
            qText.innerHTML = formatWithImages(q.question);
        }
        if (qImage) {
            if (q.image_url) {
                qImage.innerHTML = `<img src="${q.image_url}" onclick="zoomImage('${q.image_url}')" class="max-h-48 rounded-xl border border-white/20 mx-auto cursor-pointer">`;
            } else {
                qImage.innerHTML = '';
            }
        }
        if (narrator) {
            narrator.style.display = 'none';
            narrator.textContent = '';
        }

        // 5. Render Move Commands (ช้อยส์คำตอบคือท่าไม้ตาย)
        renderPokeBattleMoves(q);

        // Reset attack button state
        const attackBtn = document.getElementById('pokeAttackBtn');
        const attackBtnText = document.getElementById('pokeAttackBtnText');
        if (attackBtn) attackBtn.disabled = true;
        if (attackBtnText) {
            attackBtnText.textContent = (currentQuestionIndex === questions.length - 1)
                ? 'ปล่อยพลังปิดฉาก! (ส่งข้อสอบ)'
                : 'ปล่อยพลังโจมตี! (ยืนยันคำตอบ)';
        }

        // 6. Update Map Milestones
        if (pokeMilestones.length !== questions.length) {
            initPokeMapCanvas();
        }
    }

    function renderPokeBattleMoves(q) {
        const container = document.getElementById('pokeBattleMovesContainer');
        if (!container) return;
        container.innerHTML = '';
        selectedPokemonMoveIndex = null;

        if (q.type === 'writing') {
            container.innerHTML = `
                <div style="grid-column: 1 / -1; padding: 0.5rem 0;">
                    <label class="block text-xs font-black text-amber-300 uppercase tracking-wide mb-1.5">
                        ✍️ ร่ายคาถาพิมพ์คำตอบ (Writing Spell)
                    </label>
                    <textarea
                        id="pokeWritingInput"
                        oninput="handlePokeWritingInput(this)"
                        class="writing-area"
                        rows="3"
                        placeholder="พิมพ์คำตอบเพื่อปล่อยพลังโจมตี..."
                        style="background:#020617; border-color:#38bdf8;"
                    ></textarea>
                </div>
            `;
            return;
        }

        // Multiple Choice: Render 4 Moves
        const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        q.options.forEach((opt, idx) => {
            const card = document.createElement('div');
            card.className = 'poke-move-card';
            card.id = `pokeMoveCard_${idx}`;
            card.onclick = () => selectPokeMove(idx);

            card.innerHTML = `
                <div class="poke-move-letter">${letters[idx] || (idx + 1)}</div>
                <div class="poke-move-info">
                    <div class="poke-move-name">${formatWithImages(opt)}</div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function selectPokeMove(idx) {
        if (isPokeAttacking) return;
        selectedPokemonMoveIndex = idx;
        PokeSoundFX.playSelect();

        // Update Pokemon Move Card UI
        document.querySelectorAll('.poke-move-card').forEach((c, i) => {
            if (i === idx) c.classList.add('selected');
            else c.classList.remove('selected');
        });

        // Sync with Classic view option
        const classicOpts = document.querySelectorAll('.opt-card');
        if (classicOpts && classicOpts[idx]) {
            classicOpts.forEach(c => c.classList.remove('selected'));
            classicOpts[idx].classList.add('selected');
            const radio = classicOpts[idx].querySelector('input');
            if (radio) radio.checked = true;
        }

        // Enable buttons in both views
        const attackBtn = document.getElementById('pokeAttackBtn');
        if (attackBtn) attackBtn.disabled = false;
        document.getElementById('nextQuestionBtn').disabled = false;
        document.getElementById('submitExamBtn').disabled = false;
    }

    function syncPokeMoveSelection(idx) {
        selectedPokemonMoveIndex = idx;
        document.querySelectorAll('.poke-move-card').forEach((c, i) => {
            if (i === idx) c.classList.add('selected');
            else c.classList.remove('selected');
        });
        const attackBtn = document.getElementById('pokeAttackBtn');
        if (attackBtn) attackBtn.disabled = false;
    }

    function handlePokeWritingInput(el) {
        const val = el.value.trim();
        const has = val.length > 0;
        const attackBtn = document.getElementById('pokeAttackBtn');
        if (attackBtn) attackBtn.disabled = !has;

        // Sync with classic textarea
        const classicTextarea = document.getElementById('writing-input');
        if (classicTextarea) {
            classicTextarea.value = el.value;
            checkWritingInput(classicTextarea);
        }
    }

    function syncPokeWritingInput(val) {
        const pokeInput = document.getElementById('pokeWritingInput');
        if (pokeInput) {
            pokeInput.value = val;
            const has = val.trim().length > 0;
            const attackBtn = document.getElementById('pokeAttackBtn');
            if (attackBtn) attackBtn.disabled = !has;
        }
    }

    // Execute Battle Attack Animation & Submit Step
    function executePokemonAttack() {
        if (isPokeAttacking) return;
        isPokeAttacking = true;
        isEnemyAttacking = false;

        const playerSprite = document.getElementById('pokePlayerSprite');
        const enemySprite = document.getElementById('pokeEnemySprite');
        const battleField = document.getElementById('pokeBattleField');
        const enemyHpFill = document.getElementById('pokeEnemyHpFill');
        const attackBtn = document.getElementById('pokeAttackBtn');
        const narrator = document.getElementById('pokeBattleNarrator');
        const skillEffect = document.getElementById('pokeSkillEffect');

        if (attackBtn) attackBtn.disabled = true;

        // Randomize skill and move name for cinematic battle
        let skillClass = 'skill-thunder';
        let skillThaiName = 'สายฟ้าฟาด (Thunderbolt)';
        const randomSkill = Math.floor(Math.random() * 4);
        if (randomSkill === 1) {
            skillClass = 'skill-iron-tail';
            skillThaiName = 'หางเหล็กกล้า (Iron Tail)';
        } else if (randomSkill === 2) {
            skillClass = 'skill-electro-ball';
            skillThaiName = 'บอลประจุไฟฟ้า (Electro Ball)';
        } else if (randomSkill === 3) {
            skillClass = 'skill-quick-attack';
            skillThaiName = 'พุ่งจู่โจมไว (Quick Attack)';
        }

        // Step 1: Pikachu Charges Forward smoothly!
        if (playerSprite) playerSprite.classList.add('player-attack-anim');
        PokeSoundFX.playAttack();

        // Step 2: Impact at 450ms (Peak of Pikachu's lunge)
        setTimeout(() => {
            if (skillEffect) {
                skillEffect.className = 'poke-skill-effect';
                void skillEffect.offsetWidth; // trigger reflow
                skillEffect.classList.add(skillClass);
            }
            if (battleField) battleField.classList.add('screen-shake');
            if (enemySprite) enemySprite.classList.add('enemy-hit-anim');
            PokeSoundFX.playHit();

            // Enemy HP drops smoothly
            if (enemyHpFill) {
                enemyHpFill.style.width = '0%';
                enemyHpFill.style.backgroundColor = '#ef4444';
            }
            const enemyHpText = document.getElementById('pokeEnemyHpText');
            if (enemyHpText) {
                enemyHpText.textContent = '0/100';
                enemyHpText.style.color = '#ef4444';
            }

            // Spawn Critical floating damage on enemy
            spawnFloatingDamage('CRITICAL!', 'pokeEnemySpriteContainer', true);

            // Narrator announcement
            if (narrator) {
                narrator.style.display = 'block';
                narrator.textContent = `⚡ ปิกาจูใช้ท่า "${skillThaiName}" โจมตีเข้าจุดสำคัญ พิชิตข้อสอบสำเร็จ!`;
            }
        }, 450);

        // Step 3: Enemy defeated, victory sound, then RETURN TO MAP
        // 1350ms gives full 900ms for the 0.85s skill VFX to bloom and fade smoothly
        setTimeout(() => {
            if (battleField) battleField.classList.remove('screen-shake');
            if (playerSprite) playerSprite.classList.remove('player-attack-anim');
            if (enemySprite) {
                enemySprite.classList.remove('enemy-hit-anim');
                enemySprite.classList.add('enemy-fainted');
            }

            PokeSoundFX.playVictory();

            // Collect answer
            collectAnswer();

            // Mark question as answered
            if (!answeredQuestions.includes(currentQuestionIndex)) {
                answeredQuestions.push(currentQuestionIndex);
            }

            // Return to map or finish (1050ms later for smooth victory sensation)
            setTimeout(() => {
                isPokeAttacking = false;

                if (answeredQuestions.length < questions.length) {
                    // === RETURN TO MAP: let student walk to next Pokémon ===
                    switchPokemonSubStage('map');
                    drawPokeMap();
                    startQuestionTimer(20, true);
                } else {
                    finishExam();
                }
            }, 850);
        }, 700);
    }

    // ===== ENEMY DYNAMIC RANDOM ATTACK ENGINE =====
    function checkAndTriggerEnemyAttack(timeLeft, totalTime) {
        if (!isExamActive || isPaused || isSubmitting || isPokeAttacking || isEnemyAttacking) return;
        if (typeof totalTime !== 'number' || totalTime <= 0) totalTime = 60;

        const elapsed = totalTime - timeLeft;
        // Grace period: allow 3 seconds at start of question for student to read
        if (elapsed < 3) return;

        const now = Date.now();
        // Cooldown between attacks: gets tighter as time runs out
        const minCooldown = (timeLeft <= 10) ? 2400 : (timeLeft <= 20 ? 3200 : 4200);
        if (now - lastEnemyAttackTime < minCooldown) return;

        // Attack probability increases smoothly as time decreases
        const elapsedRatio = Math.min(1, Math.max(0, elapsed / totalTime)); // 0.0 -> 1.0
        let attackChance = 0.22 + (elapsedRatio * 0.46);
        if (timeLeft <= 10) {
            attackChance = 0.80; // High urgency in the last 10 seconds
        }

        if (Math.random() < attackChance) {
            executeEnemyRandomAttack();
        }
    }

    function executeEnemyRandomAttack() {
        if (isEnemyAttacking || isPokeAttacking || !isExamActive || isPaused || isSubmitting) return;
        isEnemyAttacking = true;
        lastEnemyAttackTime = Date.now();

        const enemySprite = document.getElementById('pokeEnemySprite');
        const playerSprite = document.getElementById('pokePlayerSprite');
        const battleField = document.getElementById('pokeBattleField');
        const playerHpFill = document.getElementById('pokePlayerHpFill');
        const playerHpText = document.getElementById('pokePlayerHpText');
        const playerSkillEffect = document.getElementById('pokePlayerSkillEffect');
        const narrator = document.getElementById('pokeBattleNarrator');
        const enemyData = POKE_ENEMIES[currentQuestionIndex % POKE_ENEMIES.length] || { name: 'โปเกมอนคู่แข่ง' };

        // Slower answer = bigger damage
        const isUrgent = (questionTimeLeft <= 10);
        const damage = isUrgent
            ? Math.floor(Math.random() * 10 + 15) // 15 - 24 damage
            : Math.floor(Math.random() * 8 + 10); // 10 - 17 damage

        // Floor at 5 HP so player survives until timeout
        pokePlayerHp = Math.max(5, pokePlayerHp - damage);

        // 1. Enemy lunges towards player Pikachu
        if (enemySprite) {
            enemySprite.classList.remove('enemy-attack-anim');
            void enemySprite.offsetWidth;
            enemySprite.classList.add('enemy-attack-anim');
        }
        PokeSoundFX.playEnemyAttack();

        // 2. Impact at 350ms
        setTimeout(() => {
            if (!isExamActive || isPaused || isPokeAttacking) {
                isEnemyAttacking = false;
                return;
            }

            // Screen shake
            if (battleField) {
                battleField.classList.remove('screen-shake');
                void battleField.offsetWidth;
                battleField.classList.add('screen-shake');
            }

            // Player takes hit animation
            if (playerSprite) {
                playerSprite.classList.remove('player-hit-anim');
                void playerSprite.offsetWidth;
                playerSprite.classList.add('player-hit-anim');
            }

            // Enemy attack effect on player Pikachu
            if (playerSkillEffect) {
                playerSkillEffect.className = 'poke-skill-effect';
                void playerSkillEffect.offsetWidth;
                playerSkillEffect.classList.add('skill-enemy-scratch');
            }

            // Hit sound effect
            PokeSoundFX.playPlayerHit();

            // Spawn floating damage popup over player
            spawnFloatingDamage(damage, 'pokePlayerSpriteContainer', false);

            // Update Player HP Bar UI
            if (playerHpFill) {
                playerHpFill.style.width = `${pokePlayerHp}%`;
                if (pokePlayerHp > 50) {
                    playerHpFill.style.backgroundColor = '#10b981';
                } else if (pokePlayerHp > 20) {
                    playerHpFill.style.backgroundColor = '#f59e0b';
                } else {
                    playerHpFill.style.backgroundColor = '#ef4444';
                }
            }
            if (playerHpText) {
                playerHpText.textContent = `${pokePlayerHp}/100`;
                if (pokePlayerHp <= 20) {
                    playerHpText.style.color = '#ef4444';
                } else if (pokePlayerHp <= 50) {
                    playerHpText.style.color = '#f59e0b';
                } else {
                    playerHpText.style.color = '#cbd5e1';
                }
            }

            // Narrator status announcement
            if (narrator) {
                narrator.style.display = 'block';
                if (pokePlayerHp <= 20) {
                    narrator.innerHTML = `⚠️ <b>${enemyData.name}</b> โจมตีหนัก! ปิกาจูเหลือเพียง <b>${pokePlayerHp} HP</b>! รีบเลือกคำตอบเพื่อสวนกลับ!`;
                } else {
                    narrator.innerHTML = `⚔️ <b>${enemyData.name}</b> ฉวยโอกาสที่คิดนาน โจมตีใส่ปิกาจู <span style="color:#ef4444; font-weight:900;">(-${damage} HP)</span> ยิ่งคิดนานศัตรูยิ่งโจมตีถี่ขึ้น!`;
                }
            }
        }, 350);

        // 3. Reset animation classes after attack completes
        setTimeout(() => {
            if (enemySprite) enemySprite.classList.remove('enemy-attack-anim');
            if (playerSprite) playerSprite.classList.remove('player-hit-anim');
            if (battleField) battleField.classList.remove('screen-shake');
            isEnemyAttacking = false;
        }, 750);
    }

    function spawnFloatingDamage(dmg, containerId, isCrit = false) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const popup = document.createElement('div');
        popup.className = 'poke-damage-popup' + (isCrit ? ' player-critical' : '');
        popup.textContent = (typeof dmg === 'number') ? `-${dmg}` : dmg;
        container.appendChild(popup);
        setTimeout(() => {
            if (popup && popup.parentElement) popup.remove();
        }, 920);
    }

    function updatePokeHpDisplays() {
        const playerHpFill = document.getElementById('pokePlayerHpFill');
        const playerHpText = document.getElementById('pokePlayerHpText');
        const enemyHpFill = document.getElementById('pokeEnemyHpFill');
        const enemyHpText = document.getElementById('pokeEnemyHpText');

        if (playerHpFill) {
            playerHpFill.style.width = `${pokePlayerHp}%`;
            if (pokePlayerHp > 50) {
                playerHpFill.style.backgroundColor = '#10b981';
            } else if (pokePlayerHp > 20) {
                playerHpFill.style.backgroundColor = '#f59e0b';
            } else {
                playerHpFill.style.backgroundColor = '#ef4444';
            }
        }
        if (playerHpText) {
            playerHpText.textContent = `${pokePlayerHp}/100`;
            if (pokePlayerHp <= 20) {
                playerHpText.style.color = '#ef4444';
            } else if (pokePlayerHp <= 50) {
                playerHpText.style.color = '#f59e0b';
            } else {
                playerHpText.style.color = '#cbd5e1';
            }
        }
        if (enemyHpFill && !isPokeAttacking) {
            enemyHpFill.style.width = '100%';
            enemyHpFill.style.backgroundColor = '#10b981';
        }
        if (enemyHpText && !isPokeAttacking) {
            enemyHpText.textContent = '100/100';
            enemyHpText.style.color = '#cbd5e1';
        }
    }

    // Explicitly bind all functions to window for inline onclick handlers
    window.toggleSoundFx = toggleSoundFx;
    window.toggleExamViewMode = toggleExamViewMode;
    window.switchPokemonSubStage = switchPokemonSubStage;
    window.startPokemonBattleEncounter = startPokemonBattleEncounter;
    window.pokeMapWalk = pokeMapWalk;
    window.pokeMapInteract = pokeMapInteract;
    window.selectPokeMove = selectPokeMove;
    window.handlePokeWritingInput = handlePokeWritingInput;
    window.executePokemonAttack = executePokemonAttack;
    window.checkAndTriggerEnemyAttack = checkAndTriggerEnemyAttack;
    window.executeEnemyRandomAttack = executeEnemyRandomAttack;
    window.spawnFloatingDamage = spawnFloatingDamage;
    window.updatePokeHpDisplays = updatePokeHpDisplays;
  </script>
</body>
</html>

# 📖 คู่มือการใช้งานระบบจัดการข้อสอบออนไลน์ (Online Exam System)
> **ระบบจัดการข้อสอบออนไลน์อัจฉริยะ (Smart Online Exam Platform)**  
> พัฒนาขึ้นเพื่อรองรับการจัดสอบวัดผลสัมฤทธิ์ทางการเรียน ทั้งรูปแบบปรนัย (Multiple Choice) และอัตนัย (Writing/Essay)  
> มีระบบความปลอดภัยป้องกันการทุจริตแบบเรียลไทม์ พร้อมระบบช่วยตรวจคำตอบอัตนัยด้วยปัญญาประดิษฐ์ (Google Gemini AI)

---

## 📑 สารบัญ (Table of Contents)
1. [ภาพรวมของระบบ (System Overview)](#1-ภาพรวมของระบบ-system-overview)
2. [คู่มือสำหรับคุณครูและผู้ดูแลระบบ (Teacher & Admin Portal)](#2-คู่มือสำหรับคุณครูและผู้ดูแลระบบ-teacher--admin-portal)
   - [2.1 การเข้าสู่ระบบ (Login)](#21-การเข้าสู่ระบบ-login)
   - [2.2 หน้าหลักการจัดการรายวิชาสอบ (Exam Cards Lobby)](#22-หน้าหลักการจัดการรายวิชาสอบ-exam-cards-lobby)
   - [2.3 การสร้างและตั้งค่ารายวิชาสอบใหม่ (Create Exam)](#23-การสร้างและตั้งค่ารายวิชาสอบใหม่-create-exam)
   - [2.4 การคัดลอกวิชาสอบ (Duplicate Exam)](#24-การคัดลอกวิชาสอบ-duplicate-exam)
   - [2.5 การจัดการคลังข้อสอบ (Question Bank Management)](#25-การจัดการคลังข้อสอบ-question-bank-management)
   - [2.6 การนำเข้าข้อสอบจาก Excel / Google Sheets (Bulk Import)](#26-การนำเข้าข้อสอบจาก-excel--google-sheets-bulk-import)
   - [2.7 จอภาพควบคุมการสอบแบบเรียลไทม์ (Live Monitor & Virtual Lobby)](#27-จอภาพควบคุมการสอบแบบเรียลไทม์-live-monitor--virtual-lobby)
   - [2.8 การดูผลสอบและตรวจให้คะแนนอัตนัยด้วย AI (Results & AI Grading)](#28-การดูผลสอบและตรวจให้คะแนนอัตนัยด้วย-ai-results--ai-grading)
   - [2.9 การพิมพ์ประกาศผลสอบ และส่งออกคะแนนเป็น Excel/CSV](#29-การพิมพ์ประกาศผลสอบ-และส่งออกคะแนนเป็น-excelcsv)
   - [2.10 การตรวจสอบความเสี่ยงและประวัติทุจริต (Anti-Cheating & Risk Logs)](#210-การตรวจสอบความเสี่ยงและประวัติทุจริต-anti-cheating--risk-logs)
   - [2.11 การตั้งค่าระบบส่วนกลาง (Global Settings)](#211-การตั้งค่าระบบส่วนกลาง-global-settings)
3. [คู่มือสำหรับนักเรียนผู้เข้าสอบ (Student Portal)](#3-คู่มือสำหรับนักเรียนผู้เข้าสอบ-student-portal)
   - [3.1 การเลือกลงทะเบียนเข้าห้องสอบ (Registration)](#31-การเลือกลงทะเบียนเข้าห้องสอบ-registration)
   - [3.2 ห้องพักคอย (Waiting Lobby)](#32-ห้องพักคอย-waiting-lobby)
   - [3.3 การทำข้อสอบโหมดมาตรฐาน (Classic Exam Interface)](#33-การทำข้อสอบโหมดมาตรฐาน-classic-exam-interface)
   - [3.4 การทำข้อสอบโหมดพิเศษ: Pokémon RPG Adventure](#34-การทำข้อสอบโหมดพิเศษ-pokémon-rpg-adventure)
   - [3.5 กฎเหล็กและระบบตรวจจับการทุจริต (Anti-Cheating Engine)](#35-กฎเหล็กและระบบตรวจจับการทุจริต-anti-cheating-engine)
   - [3.6 การส่งข้อสอบและการดูรายงานผลคะแนน (Result Summary)](#36-การส่งข้อสอบและการดูรายงานผลคะแนน-result-summary)
4. [คำถามที่พบบ่อยและการแก้ไขปัญหา (FAQ & Troubleshooting)](#4-คำถามที่พบบ่อยและการแก้ไขปัญหา-faq--troubleshooting)

---

## 1. ภาพรวมของระบบ (System Overview)

ระบบจัดการข้อสอบออนไลน์นี้ถูกออกแบบมาเพื่อรองรับการสอบทั้งในห้องปฏิบัติการคอมพิวเตอร์และการสอบผ่านอุปกรณ์พกพา โดยแบ่งการทำงานออกเป็น 2 ฝั่งหลัก:

| ฝั่งการใช้งาน | ที่อยู่ URL | คำอธิบาย |
| :--- | :--- | :--- |
| **ฝั่งนักเรียน (Student)** | `/` หรือ `/lobby`, `/exam`, `/result` | ใช้ลงทะเบียนเข้าสอบ รอในห้องพักคอย ทำข้อสอบ และดูผลคะแนน |
| **ฝั่งคุณครู (Teacher / Admin)** | `/teacher` หรือ `/teacher/dashboard` | ใช้จัดการรายวิชา คลังข้อสอบ คุมสอบเรียลไทม์ ตรวจข้อสอบ และพิมพ์/ส่งออกผลสอบ |

### จุดเด่นที่สำคัญของระบบ
* 🤖 **AI-Assisted Grading**: ช่วยคุณครูประเมินคะแนนข้อสอบอัตนัย (ข้อเขียน) เปรียบเทียบกับเฉลยอย่างเป็นธรรมด้วย **Google Gemini 2.0 Flash** พร้อมแสดงเหตุผลประกอบ
* 🛡️ **Advanced Anti-Cheating**: ตรวจจับการสลับหน้าจอ (Tab Switching / Window Blur), บล็อกคลิกขวา, บล็อกคีย์ลัด DevTools และมีระบบโควตานับครั้ง (Strikes) หากเกินกำหนดจะส่งข้อสอบและระงับสิทธิ์ทันที
* 🎮 **Interactive 2D Virtual Lobby**: ห้องพักคอยที่นักเรียนสามารถบังคับตัวละครเพื่อรอเวลาสอบ พร้อมซิงค์ตำแหน่งแบบเรียลไทม์
* ⚡ **Pokémon RPG Adventure Mode**: ทางเลือกใหม่ของการจัดสอบในรูปแบบเกมสำรวจแผนที่ 2D และประลองตอบคำถามกับ NPC
* 📊 **Automated Export & Print**: สั่งพิมพ์ใบประกาศผลสอบรูปแบบทางการ (A4) และส่งออกคะแนนเป็นไฟล์ **Excel / CSV (UTF-8 with BOM)** สำหรับนำไปลงสมุด ปพ. ได้ทันที

---

## 2. คู่มือสำหรับคุณครูและผู้ดูแลระบบ (Teacher & Admin Portal)

### 2.1 การเข้าสู่ระบบ (Login)
เข้าใช้งานผ่าน URL: `http://<your-domain-or-ip>:8200/teacher`

ระบบรองรับการเข้าสู่ระบบ 2 วิธี:
1. **เข้าสู่ระบบด้วยบัญชี Google Workspace โรงเรียน (`@skj.ac.th`)**:
   - คลิกที่ปุ่ม **Sign in with Google**
   - เลือกล็อกอินด้วยบัญชีอีเมลของโรงเรียน
   - ระบบจะตรวจสอบสิทธิ์บุคลากรและเชื่อมโยงกลุ่มสาระการเรียนรู้ให้อัตโนมัติ
2. **เข้าสู่ระบบด้วยรหัสผ่านผู้ดูแลระบบ (Master Admin Password)**:
   - ในกรณีที่ไม่ได้ใช้อีเมลโรงเรียน หรือทดสอบใช้งาน ให้คลิก **"🔑 หรือเข้าสู่ระบบด้วยรหัสผ่านผู้ดูแลระบบ"**
   - กรอกรหัสผ่านผู้ดูแลระบบ (ค่าเริ่มต้นคือ `admin1234` หรือตามที่กำหนดไว้ในตั้งค่าระบบ) แล้วกด **เข้าสู่ระบบ 🚀**

#### ตัวอย่างหน้าจอเข้าสู่ระบบ (Login Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ https://exam.skj.ac.th/teacher</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Teacher Login</span>
  </div>
  
  <div style="max-width: 440px; margin: 15px auto; background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 26px; text-align: center; backdrop-filter: blur(10px); box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
    <div style="width: 54px; height: 54px; border-radius: 16px; background: linear-gradient(135deg, #ec4899, #38bdf8); margin: 0 auto 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; box-shadow: 0 8px 16px rgba(236, 72, 153, 0.35);">
      🏫
    </div>
    <h4 style="margin: 0; font-size: 18px; font-weight: 800; color: #fff;">ระบบจัดการข้อสอบออนไลน์</h4>
    <p style="margin: 4px 0 18px; font-size: 12px; color: #94a3b8;">สำหรับคุณครูและผู้ดูแลระบบ SKJ Exam Portal</p>
    
    <!-- Google OAuth Button -->
    <div style="padding: 12px 18px; border-radius: 12px; background: #ffffff; color: #1e293b; font-weight: bold; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); cursor: pointer; border: 1px solid #e2e8f0; margin-bottom: 16px;">
      <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
      <span>Sign in with Google (@skj.ac.th)</span>
    </div>

    <!-- Divider -->
    <div style="display: flex; align-items: center; margin: 16px 0; color: #64748b; font-size: 11px;">
      <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.1);"></div>
      <span style="padding: 0 10px;">หรือเข้าด้วยรหัสผ่านแอดมิน</span>
      <div style="flex: 1; height: 1px; background: rgba(255,255,255,0.1);"></div>
    </div>

    <!-- Master Admin Fallback Form -->
    <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 14px; text-align: left;">
      <label style="font-size: 11px; font-weight: bold; color: #cbd5e1; display: block; margin-bottom: 6px;">🔑 Master Admin Password:</label>
      <div style="display: flex; gap: 8px;">
        <input type="password" value="••••••••••••" readonly style="flex: 1; padding: 8px 12px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 12px; outline: none;">
        <button style="padding: 8px 14px; border-radius: 8px; background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; font-size: 12px; font-weight: bold; border: none; cursor: pointer;">เข้าสู่ระบบ 🚀</button>
      </div>
      <p style="margin: 6px 0 0; font-size: 10px; color: #64748b;">(รหัสผ่านตั้งต้น: admin1234 หรือตามที่บันทึกไว้ในระบบ)</p>
    </div>
  </div>
  
</div>


---

### 2.2 หน้าหลักการจัดการรายวิชาสอบ (Exam Cards Lobby)
เมื่อเข้าสู่ระบบ จะพบกับหน้าแสดง **การ์ดรายวิชาทั้งหมดของคุณครู**:

* **🔍 ช่องค้นหา**: พิมพ์รหัสวิชา, ชื่อวิชา, กลุ่มสาระฯ หรือรอบสอบ เพื่อค้นหาวิชาที่ต้องการได้อย่างรวดเร็ว
* **📅 ตัวกรองปีการศึกษา**: เลือกดูเฉพาะปีการศึกษาล่าสุด หรือเลือกดูย้อนหลังได้ตามต้องการ
* **🎯 แท็บคัดกรองรอบสอบ**:
  - **🌟 ทั้งหมด**: แสดงทุกวิชา
  - **🎯 สอบกลางภาค**: แสดงเฉพาะรายวิชาที่จัดสอบกลางภาค (Midterm)
  - **🏁 สอบปลายภาค**: แสดงเฉพาะรายวิชาที่จัดสอบปลายภาค (Final)
  - **📝 สอบอื่นๆ**: แสดงแบบทดสอบย่อย/ก่อนเรียน/หลังเรียน
* **ปุ่มดำเนินการบนการ์ดวิชา**:
  - **⚙️ จัดการข้อสอบ**: เข้าสู่พื้นที่จัดการของรายวิชานั้น (Workspace)
  - **📋 คัดลอกวิชา**: ทำสำเนาวิชานี้เพื่อเปิดสอบรอบใหม่หรือเทอมใหม่
  - **✏️ แก้ไขวิชา**: ปรับปรุงรายละเอียดและเกณฑ์การจัดสอบ

#### ตัวอย่างหน้าจอแผงควบคุมรายวิชาทั้งหมด (Exam Lobby Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ Teacher Dashboard: แผงควบคุมรายวิชาทั้งหมด</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Exam Cards & Filters</span>
  </div>
  
  <!-- Filter Toolbar -->
  <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 10px; background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 14px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 6px 10px; min-width: 220px;">
      <span style="font-size: 12px;">🔍</span>
      <span style="font-size: 11px; color: #94a3b8;">ค้นหาชื่อวิชา, รหัสวิชา...</span>
    </div>
    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
      <span style="font-size: 11px; color: #cbd5e1; font-weight: bold;">ปีการศึกษา:</span>
      <span style="font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 6px; background: rgba(236,72,153,0.15); color: #f472b6; border: 1px solid rgba(236,72,153,0.3);">2569 (ล่าสุด) ▼</span>
      <span style="font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 6px; background: rgba(255,255,255,0.15); color: #fff;">🌟 ทั้งหมด (7)</span>
      <span style="font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 6px; background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3);">🎯 สอบกลางภาค (5)</span>
      <span style="font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 6px; background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.3);">🏁 สอบปลายภาค (2)</span>
      <span style="font-size: 11px; font-weight: bold; padding: 4px 12px; border-radius: 8px; background: linear-gradient(135deg, #ec4899, #db2777); color: #fff; box-shadow: 0 4px 10px rgba(236,72,153,0.3); margin-left: 6px;">➕ เพิ่มวิชาใหม่</span>
    </div>
  </div>

  <!-- Cards Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px;">
    <!-- Card 1: Started -->
    <div style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 16px; padding: 16px; position: relative;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
        <span style="font-family: monospace; font-weight: bold; color: #f472b6; font-size: 12px;">ว30291</span>
        <span style="font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 6px; background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">🚀 Started (กำลังสอบ)</span>
      </div>
      <div style="display: flex; gap: 6px; margin-bottom: 6px;">
        <span style="font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 6px; background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">🎯 สอบกลางภาค</span>
        <span style="font-size: 10px; color: #94a3b8;">เทอม 1/2569</span>
      </div>
      <h4 style="margin: 0 0 8px; font-size: 15px; font-weight: 800; color: #fff;">การสร้าง Web Application</h4>
      <div style="font-size: 11px; color: #94a3b8; line-height: 1.6; margin-bottom: 12px;">
        <div>📚 คลังข้อสอบ: <b>ปรนัย 20 ข้อ</b> / <b>อัตนัย 2 ข้อ</b></div>
        <div>⏱️ สุ่มสอบ 20 ข้อ | เกณฑ์ผ่าน 50% | สิทธิ์สอบ 2 ครั้ง</div>
      </div>
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 6px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 11px; font-weight: bold;">
        <div style="padding: 7px; border-radius: 8px; background: linear-gradient(135deg, #ec4899, #0284c7); color: #fff; text-align: center; cursor: pointer;">⚙️ จัดการข้อสอบ</div>
        <div style="padding: 7px; border-radius: 8px; background: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.25); text-align: center; cursor: pointer;">📋 โคลน</div>
        <div style="padding: 7px; border-radius: 8px; background: rgba(255,255,255,0.06); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); text-align: center; cursor: pointer;">✏️ แก้ไข</div>
      </div>
    </div>

    <!-- Card 2: Waiting -->
    <div style="background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(168, 85, 247, 0.35); border-radius: 16px; padding: 16px; position: relative;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
        <span style="font-family: monospace; font-weight: bold; color: #f472b6; font-size: 12px;">ว20250</span>
        <span style="font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 6px; background: rgba(148, 163, 184, 0.15); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3);">⏳ Waiting (รอเปิดสอบ)</span>
      </div>
      <div style="display: flex; gap: 6px; margin-bottom: 6px;">
        <span style="font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 6px; background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3);">🏁 สอบปลายภาค</span>
        <span style="font-size: 10px; color: #94a3b8;">เทอม 1/2569</span>
      </div>
      <h4 style="margin: 0 0 8px; font-size: 15px; font-weight: 800; color: #fff;">วิทยาการคำนวณและข้อมูล 1</h4>
      <div style="font-size: 11px; color: #94a3b8; line-height: 1.6; margin-bottom: 12px;">
        <div>📚 คลังข้อสอบ: <b>ปรนัย 30 ข้อ</b> / <b>อัตนัย 0 ข้อ</b></div>
        <div>⏱️ สุ่มสอบ 25 ข้อ | เกณฑ์ผ่าน 60% | สิทธิ์สอบ 1 ครั้ง</div>
      </div>
      <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 6px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 11px; font-weight: bold;">
        <div style="padding: 7px; border-radius: 8px; background: linear-gradient(135deg, #ec4899, #0284c7); color: #fff; text-align: center; cursor: pointer;">⚙️ จัดการข้อสอบ</div>
        <div style="padding: 7px; border-radius: 8px; background: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.25); text-align: center; cursor: pointer;">📋 โคลน</div>
        <div style="padding: 7px; border-radius: 8px; background: rgba(255,255,255,0.06); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); text-align: center; cursor: pointer;">✏️ แก้ไข</div>
      </div>
    </div>
  </div>
  
</div>


---

### 2.3 การสร้างและตั้งค่ารายวิชาสอบใหม่ (Create Exam)
กดปุ่ม **"➕ เพิ่มวิชาสอบใหม่"** มุมขวาบน จะมีหน้าต่างให้กรอกข้อมูลดังนี้:

| ช่องข้อมูล | คำอธิบายและคำแนะนำ |
| :--- | :--- |
| **รหัสวิชา & ชื่อวิชา** | เช่น `ว30291` และ `การสร้าง Web Application` |
| **กลุ่มสาระการเรียนรู้** | เลือกกลุ่มสาระฯ ที่รับผิดชอบ |
| **ปีการศึกษา & ภาคเรียน** | เช่น ปีการศึกษา `2569` ภาคเรียนที่ `1` |
| **ประเภทการสอบ** | เลือก `สอบกลางภาค`, `สอบปลายภาค`, หรือ `สอบย่อย` |
| **สถานะการสอบ** | `Waiting` (รอสอบ/พักคอย), `Started` (เปิดให้สอบ), `Paused` (หยุดชั่วคราว), `Finished` (ปิดการสอบ) |
| **จำนวนข้อสอบที่สุ่มสอบ** | จำนวนข้อที่ระบบจะสุ่มมาให้นักเรียนทำต่อ 1 คน (ดึงจากคลังข้อสอบ) |
| **สิทธิ์เข้าสอบสูงสุด** | จำนวนรอบที่อนุญาตให้นักเรียนคนเดิมเข้าทำข้อสอบได้ (เช่น 1 หรือ 2 ครั้ง) |
| **เกณฑ์ผ่าน (%)** | เปอร์เซ็นต์คะแนนขั้นต่ำในการประเมินผลว่า "ผ่าน" เช่น 50% |
| **เวลาทำข้อสอบปรนัย (วินาที/ข้อ)** | กำหนดเวลารายข้อสำหรับข้อกา (เช่น 60 วินาที) เมื่อหมดเวลาจะข้ามข้อทันที |
| **เวลาทำข้อสอบอัตนัย (วินาที/ข้อ)** | กำหนดเวลารายข้อสำหรับข้อเขียน (เช่น 300 วินาที = 5 นาที) |
| **เวลาทำข้อสอบทั้งหมด (นาที)** | ตั้งค่าเวลาจำกัดของทั้งฉบับ (หากใส่ `0` หมายถึงไม่จำกัดเวลาโดยรวม ให้ยึดเวลารายข้อ) |
| **รอบการสอบ (Exam Round)** | ระบุรอบสอบ เช่น `1`, `2`, หรือ `รอบซ่อมเสริม` เพื่อแยกสถิติคะแนน |
| **นโยบายการเข้าร่วมสอบ** | - `เริ่มตอนไหนก็ได้ (Anytime)`: นักเรียนมาถึงกดเริ่มสอบได้ทันที<br>- `ต้องรอในห้องพักคอย (Lobby First)`: นักเรียนต้องรอใน Lobby จนกว่าครูจะกดปุ่ม Start จากจอควบคุม |
| **ระบบป้องกันการทุจริต** | `เปิดใช้งาน` หรือ `ปิดใช้งาน` การตรวจจับการสลับแท็บ/หน้าจอ |
| **จำนวนครั้งสลับหน้าจอสูงสุด** | จำนวน Strike สูงสุดที่ยอมให้สลับหน้าจอได้ก่อนถูกระงับสิทธิ์ (ค่าแนะนำ: 3 ครั้ง) |
| **รูปแบบการสอบ (Exam Mode)** | - `ข้อสอบมาตรฐาน (Classic)`: หน้าต่างทำข้อสอบมาตรฐานมี Progress Bar<br>- `เกมผจญภัย (Pokémon RPG)`: เดินแผนที่ 2D ท้าประลองตอบคำถาม |

#### ตัวอย่างหน้าต่างตั้งค่ารายวิชาสอบ (Create/Edit Modal Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ หน้าต่างตั้งค่ารายวิชาสอบ (Create / Edit Exam Modal)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Modal Settings</span>
  </div>
  
  <div style="max-width: 620px; margin: 0 auto; background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(255,255,255,0.15); border-radius: 20px; padding: 22px; box-shadow: 0 20px 40px rgba(0,0,0,0.6);">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; margin-bottom: 16px;">
      <div>
        <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #fff;">➕ เพิ่มวิชาสอบใหม่ / ปรับแต่งกติกาการสอบ</h4>
        <p style="margin: 3px 0 0; font-size: 11px; color: #94a3b8;">กำหนดรหัสวิชา เกณฑ์การสอบ เวลาทำ และระบบป้องกันทุจริต</p>
      </div>
      <span style="font-size: 16px; color: #94a3b8; cursor: pointer;">✖</span>
    </div>

    <!-- Form 2 Cols -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 11px; margin-bottom: 14px;">
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">รหัสวิชา (Subject Code):</label>
        <input type="text" value="ว30291" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">ชื่อวิชา (Subject Name):</label>
        <input type="text" value="การสร้าง Web Application" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">ประเภทการสอบ:</label>
        <input type="text" value="สอบกลางภาค (Midterm)" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fbbf24; font-weight: bold; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">รอบการสอบ (Exam Round):</label>
        <input type="text" value="1" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">จำนวนข้อสุ่ม (Random Questions):</label>
        <input type="text" value="20 ข้อ (จากคลัง 22 ข้อ)" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">เกณฑ์ผ่านการประเมิน (%):</label>
        <input type="text" value="50 %" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #34d399; font-weight: bold; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">เวลาปรนัย (วินาที/ข้อ):</label>
        <input type="text" value="60 วินาที" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">เวลาอัตนัย (วินาที/ข้อ):</label>
        <input type="text" value="300 วินาที (5 นาที)" readonly style="width: 100%; box-sizing: border-box; padding: 7px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
    </div>

    <!-- Security & Mode Cards -->
    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 12px; margin-bottom: 16px; font-size: 11px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
        <span style="font-weight: bold; color: #f87171;">🛡️ ระบบตรวจจับการทุจริต (Anti-Cheating):</span>
        <span style="padding: 2px 8px; border-radius: 6px; background: rgba(239,68,68,0.2); color: #f87171; border: 1px solid rgba(239,68,68,0.3); font-weight: bold;">เปิดใช้งาน (สลับจอได้สูงสุด 3 ครั้ง)</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-weight: bold; color: #38bdf8;">🎮 รูปแบบการสอบ (Exam Mode):</span>
        <div style="display: flex; gap: 6px;">
          <span style="padding: 2px 8px; border-radius: 6px; background: rgba(56,189,248,0.2); color: #38bdf8; border: 1px solid rgba(56,189,248,0.3); font-weight: bold;">🔘 โหมดมาตรฐาน (Classic)</span>
          <span style="padding: 2px 8px; border-radius: 6px; background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1);">⚪ Pokémon RPG</span>
        </div>
      </div>
    </div>

    <div style="display: flex; justify-content: flex-end; gap: 8px;">
      <span style="padding: 7px 16px; border-radius: 8px; background: rgba(255,255,255,0.08); color: #94a3b8; font-size: 11px; font-weight: bold; cursor: pointer;">ยกเลิก</span>
      <span style="padding: 7px 18px; border-radius: 8px; background: linear-gradient(135deg, #ec4899, #0284c7); color: #fff; font-size: 11px; font-weight: bold; cursor: pointer;">💾 บันทึกและสร้างวิชา</span>
    </div>
  </div>
  
</div>


---

### 2.4 การคัดลอกวิชาสอบ (Duplicate Exam)
เมื่อต้องการใช้ข้อสอบเดิมในภาคเรียนถัดไป หรือจัดสอบรอบซ่อมเสริม:
1. คลิกปุ่ม **📋** ที่การ์ดวิชาที่ต้องการ
2. ระบุ **ชื่อวิชาใหม่**, **รหัสวิชา**, **ภาคเรียน**, **ปีการศึกษา** และ **รอบการสอบใหม่**
3. ทำเครื่องหมายถูกที่ช่อง **"คัดลอกข้อสอบทั้งหมดจากวิชาเดิมไปด้วย"**
4. กด **"ยืนยันการคัดลอก 🚀"** ระบบจะสร้างวิชาใหม่พร้อมดึงคำถามทุกข้อไปให้อัตโนมัติ โดยมีสถานะเริ่มต้นเป็น `Waiting`

---

### 2.5 การจัดการคลังข้อสอบ (Question Bank Management)
เมื่อคลิก **"⚙️ จัดการข้อสอบ"** จากการ์ดวิชา จะเข้าสู่ Workspace แถบ **"📝 คลังข้อสอบรายวิชา"**:

#### ก. การเพิ่มข้อสอบใหม่ทีละข้อ
1. คลิกปุ่ม **"➕ เพิ่มข้อสอบใหม่"**
2. เลือกประเภทข้อสอบ:
   - **🔘 ปรนัย (Multiple Choice)**:
     - กรอกโจทย์คำถาม
     - (ถ้ามี) อัปโหลดรูปภาพประกอบโจทย์
     - กรอกตัวเลือก A, B, C, D และ **คลิกที่วงกลมหน้าตัวเลือกที่ถูกต้อง**
     - ระบุคะแนนของข้อนี้ (ค่าเริ่มต้น 1 คะแนน)
   - **📜 อัตนัย (Writing / Essay)**:
     - กรอกโจทย์คำถาม
     - ระบุคะแนนเต็มของข้อนี้
     - กรอก **"คำตอบอ้างอิงแนวทาง (เฉลย)"** เพื่อใช้เป็นเกณฑ์ในการเทียบคำตอบของ AI หรือระบบตรวจคำ
3. กด **"บันทึกข้อสอบ 💾"**

#### ข. การแก้ไขและลบข้อสอบ
* คลิกปุ่ม **✏️** ที่รายการข้อสอบ เพื่อแก้ไขเนื้อหา รูปภาพ ตัวเลือก หรือเฉลย
* คลิกปุ่ม **🗑️** เพื่อลบข้อสอบรายข้อ (ระบบจะลบไฟล์รูปภาพที่เกี่ยวข้องออกจากเซิร์ฟเวอร์ให้อัตโนมัติ)
* คลิกปุ่ม **"🗑️ ล้างคำถามทั้งหมด"** หากต้องการลบข้อสอบทุกข้อในวิชานั้นเพื่อเริ่มใส่ใหม่

---

### 2.6 การนำเข้าข้อสอบจาก Excel / Google Sheets (Bulk Import)
คุณครูสามารถพิมพ์ข้อสอบในตาราง Excel หรือ Google Sheets แล้วนำเข้าได้ในคราวเดียว:
1. จัดเตรียมคอลัมน์ใน Excel เรียงตามลำดับดังนี้:
   - **คอลัมน์ A**: โจทย์คำถาม
   - **คอลัมน์ B**: ตัวเลือก ก (หรือตัวเลือก A)
   - **คอลัมน์ C**: ตัวเลือก ข (หรือตัวเลือก B)
   - **คอลัมน์ D**: ตัวเลือก ค (หรือตัวเลือก C)
   - **คอลัมน์ E**: ตัวเลือก ง (หรือตัวเลือก D)
   - **คอลัมน์ F**: คำตอบที่ถูกต้อง (เช่น พิมพ์ตัวเลือกที่ตรงกับข้อถูก หรือแนวคำตอบข้อเขียน)
   - **คอลัมน์ G**: ประเภท (`choice` สำหรับปรนัย หรือ `writing` สำหรับอัตนัย)
   - **คอลัมน์ H**: คะแนนประจำข้อ (เช่น `1` หรือ `5`)
2. คัดลอก (Copy) ข้อมูลแถวข้อสอบทั้งหมด
3. ในหน้าระบบ คลิกปุ่ม **"📥 นำเข้าข้อสอบ"**
4. วาง (Paste) ข้อมูลลงในกล่องข้อความ
5. (ตัวเลือก) ติ๊กถูกที่ *"ลบข้อสอบรายวิชานี้ที่เคยมีออกทั้งหมดก่อนนำเข้า"* หากต้องการแทนที่ของเดิม
6. กดปุ่ม **"นำเข้าข้อมูล"**

#### ตัวอย่างหน้าจอคลังข้อสอบและตัวเลือกคำตอบ (Question Bank Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ คลังข้อสอบ: ว30291 การสร้าง Web Application</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Question Bank & Import</span>
  </div>
  
  <!-- Toolbar -->
  <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(15,23,42,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 14px; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
    <div style="display: flex; gap: 6px; font-size: 11px;">
      <span style="padding: 6px 12px; border-radius: 8px; background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; font-weight: bold; cursor: pointer;">➕ เพิ่มข้อสอบใหม่</span>
      <span style="padding: 6px 12px; border-radius: 8px; background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); font-weight: bold; cursor: pointer;">📥 นำเข้าจาก Excel / Sheets</span>
      <span style="padding: 6px 12px; border-radius: 8px; background: rgba(239,68,68,0.1); color: #f87171; border: 1px solid rgba(239,68,68,0.25); font-weight: bold; cursor: pointer;">🗑️ ล้างคำถามทั้งหมด</span>
    </div>
    <span style="font-size: 11px; color: #94a3b8;">ทั้งหมด: <b style="color: #fff;">22 ข้อ</b> (ปรนัย 20 | อัตนัย 2)</span>
  </div>

  <!-- Question List Preview -->
  <div style="display: flex; flex-direction: column; gap: 10px;">
    <!-- Q1 Choice -->
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
        <span style="font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 6px; background: rgba(56,189,248,0.15); color: #38bdf8; border: 1px solid rgba(56,189,248,0.3);">ข้อที่ 1 (ปรนัย - 1 คะแนน)</span>
        <div style="display: flex; gap: 6px; font-size: 11px;">
          <span style="cursor: pointer;">✏️ แก้ไข</span>
          <span style="cursor: pointer; color: #f87171;">🗑️ ลบ</span>
        </div>
      </div>
      <p style="margin: 0 0 10px; font-size: 13px; font-weight: 700; color: #fff;">โปรโตคอลใดที่ใช้สำหรับการสื่อสารและรับส่งข้อมูลเว็บเพจแบบเข้ารหัสความปลอดภัย?</p>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 11px;">
        <div style="padding: 6px 10px; border-radius: 6px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); color: #94a3b8;">A. HTTP</div>
        <div style="padding: 6px 10px; border-radius: 6px; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.4); color: #34d399; font-weight: bold;">B. HTTPS (เฉลยถูกต้อง ✅)</div>
        <div style="padding: 6px 10px; border-radius: 6px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); color: #94a3b8;">C. FTP</div>
        <div style="padding: 6px 10px; border-radius: 6px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); color: #94a3b8;">D. SMTP</div>
      </div>
    </div>

    <!-- Q2 Writing -->
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(168,85,247,0.3); border-radius: 14px; padding: 14px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
        <span style="font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 6px; background: rgba(168,85,247,0.15); color: #c084fc; border: 1px solid rgba(168,85,247,0.3);">ข้อที่ 2 (อัตนัย/ข้อเขียน - 5 คะแนน)</span>
        <div style="display: flex; gap: 6px; font-size: 11px;">
          <span style="cursor: pointer;">✏️ แก้ไข</span>
          <span style="cursor: pointer; color: #f87171;">🗑️ ลบ</span>
        </div>
      </div>
      <p style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #fff;">จงอธิบายหน้าที่ของ Controller ในสถาปัตยกรรม MVC</p>
      <div style="padding: 8px 12px; border-radius: 8px; background: rgba(0,0,0,0.35); border-left: 3px solid #c084fc; font-size: 11px; color: #cbd5e1;">
        <span style="color: #fbbf24; font-weight: bold;">เฉลยแนวทางของคุณครู (สำหรับตรวจ/ให้ AI เทียบ):</span>
        <div style="margin-top: 3px; color: #94a3b8;">"Controller ทำหน้าที่เป็นตัวกลางรับ Request จากผู้ใช้ ประมวลผลตรรกะทางธุรกิจ และเรียก Model หรือ View มาแสดงผล"</div>
      </div>
    </div>
  </div>
  
</div>


---

### 2.7 จอภาพควบคุมการสอบแบบเรียลไทม์ (Live Monitor & Virtual Lobby)
คลิกที่เมนู **"🖥️ จอภาพควบคุม (Lobby)"** ทางแถบซ้ายมือ:
* **จอภาพจำลองห้องพักคอย (Lobby Virtual Room)**: แสดงตัวละคร 2D ของนักเรียนที่กำลังรอสอบแบบเรียลไทม์
* **ตารางรายชื่อนักเรียนสด**: แสดงชื่อ, เลขที่, ห้อง, สถานะการเชื่อมต่อ และจำนวนครั้งการสลับหน้าจอ (Cheating Strikes)
* **ปุ่มควบคุมสถานะการสอบ (Control Buttons)**:
  - 🟢 **เริ่มการสอบ (Start Exam)**: นำนักเรียนทุกคนที่รออยู่ใน Lobby เข้าสู่หน้าทำข้อสอบพร้อมกันทันที
  - ⏸️ **หยุดชั่วคราว (Pause)**: ระงับการทำข้อสอบชั่วคราว
  - 🔴 **ปิดการสอบ (Finish)**: สิ้นสุดการสอบ ไม่รับคำตอบเพิ่ม
  - 🔄 **รีเซ็ตห้องสอบ (Reset to Waiting)**: เคลียร์คิวนักเรียนและตั้งสถานะกลับเป็นรอสอบ
* **การคัดนักเรียนออก**: หากมีนักเรียนที่เข้าผิดวิชาหรือชื่อซ้ำ คุณครูสามารถคลิกปุ่ม **"เอาออก"** เพื่อลบชื่อออกจากห้องได้ทันที

#### ตัวอย่างหน้าจอควบคุมสดและห้องพักคอย 2D (Live Monitor Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ จอภาพควบคุมการสอบแบบเรียลไทม์ (Live Monitor & Virtual Lobby)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Live Monitor</span>
  </div>
  
  <div style="display: grid; grid-template-columns: 240px 1fr; gap: 14px; align-items: start;">
    <!-- Left Column: Controls -->
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; padding: 14px; font-size: 11px;">
      <h5 style="margin: 0 0 10px; font-size: 12px; font-weight: bold; color: #fff;">⚙️ แผงควบคุมระบบสอบ</h5>
      
      <div style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 8px 10px; margin-bottom: 12px;">
        <span style="font-size: 10px; color: #94a3b8; display: block;">สถานะการสอบปัจจุบัน:</span>
        <span style="font-size: 12px; font-weight: bold; color: #34d399;">🚀 Started (กำลังสอบ)</span>
      </div>

      <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px;">
        <div style="padding: 8px 10px; border-radius: 8px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; font-weight: bold; text-align: center; cursor: pointer;">🚀 เริ่มสอบ (ปล่อยข้อสอบ)</div>
        <div style="padding: 8px 10px; border-radius: 8px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; font-weight: bold; text-align: center; cursor: pointer;">⏳ เปิดพักคอย (Lobby)</div>
        <div style="padding: 8px 10px; border-radius: 8px; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; font-weight: bold; text-align: center; cursor: pointer;">🛑 ปิดการสอบ (ยุติ)</div>
      </div>

      <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 10px;">
        <span style="font-size: 10px; color: #94a3b8; display: block; margin-bottom: 4px;">โหมดการสอบ:</span>
        <div style="padding: 6px 10px; border-radius: 6px; background: rgba(56,189,248,0.15); border: 1px solid rgba(56,189,248,0.3); color: #38bdf8; font-weight: bold;">📝 ข้อสอบมาตรฐาน (Classic)</div>
      </div>
    </div>

    <!-- Right Column: 2D Room & Live Table -->
    <div style="display: flex; flex-direction: column; gap: 10px;">
      <!-- 2D Virtual Canvas Representation -->
      <div style="background: #020617; border: 1px solid rgba(56,189,248,0.3); border-radius: 14px; padding: 16px; position: relative; height: 130px; overflow: hidden; box-shadow: inset 0 0 20px rgba(0,0,0,0.8);">
        <div style="position: absolute; top: 10px; left: 14px; font-size: 10px; font-weight: bold; color: #38bdf8;">🎮 2D Virtual Lobby (ห้องพักคอยซิงค์เรียลไทม์)</div>
        <!-- Grid Dots/Floor -->
        <div style="position: absolute; inset: 0; opacity: 0.15; background-image: radial-gradient(#38bdf8 1px, transparent 1px); background-size: 16px 16px;"></div>
        
        <!-- Student Avatar 1 -->
        <div style="position: absolute; left: 25%; top: 40%; text-align: center;">
          <div style="font-size: 9px; color: #fff; background: rgba(0,0,0,0.6); padding: 1px 5px; border-radius: 4px; margin-bottom: 2px;">นายสมชาย 4/1 #12</div>
          <span style="font-size: 22px;">🧑‍🎓</span>
        </div>
        <!-- Student Avatar 2 -->
        <div style="position: absolute; left: 60%; top: 48%; text-align: center;">
          <div style="font-size: 9px; color: #fff; background: rgba(0,0,0,0.6); padding: 1px 5px; border-radius: 4px; margin-bottom: 2px;">น.ส.กานดา 4/1 #5</div>
          <span style="font-size: 22px;">👩‍🎓</span>
        </div>
        <!-- Student Avatar 3 -->
        <div style="position: absolute; left: 80%; top: 35%; text-align: center;">
          <div style="font-size: 9px; color: #fff; background: rgba(0,0,0,0.6); padding: 1px 5px; border-radius: 4px; margin-bottom: 2px;">นายพงศกร 4/1 #18</div>
          <span style="font-size: 22px;">🧑‍💻</span>
        </div>
      </div>

      <!-- Realtime Student Roster Table -->
      <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 10px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: left;">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #94a3b8;">
              <th style="padding: 6px;">ลำดับ</th>
              <th style="padding: 6px;">เลขประจำตัว</th>
              <th style="padding: 6px;">ชื่อ-สกุล</th>
              <th style="padding: 6px;">ห้อง</th>
              <th style="padding: 6px;">เลขที่</th>
              <th style="padding: 6px;">สถานะ</th>
              <th style="padding: 6px;">สลับจอ (Strike)</th>
              <th style="padding: 6px; text-align: center;">จัดการ</th>
            </tr>
          </thead>
          <tbody>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); color: #cbd5e1;">
              <td style="padding: 6px;">1</td>
              <td style="padding: 6px; font-family: monospace;">55012</td>
              <td style="padding: 6px; font-weight: bold; color: #fff;">นายสมชาย ใจดี</td>
              <td style="padding: 6px;">4/1</td>
              <td style="padding: 6px;">12</td>
              <td style="padding: 6px;"><span style="color: #34d399; font-weight: bold;">🟢 กำลังทำข้อสอบ</span></td>
              <td style="padding: 6px;"><span style="color: #34d399; font-weight: bold;">0 / 3</span></td>
              <td style="padding: 6px; text-align: center;"><span style="color: #f87171; cursor: pointer;">เอาออก</span></td>
            </tr>
            <tr style="color: #cbd5e1;">
              <td style="padding: 6px;">2</td>
              <td style="padding: 6px; font-family: monospace;">55005</td>
              <td style="padding: 6px; font-weight: bold; color: #fff;">น.ส.กานดา สดใส</td>
              <td style="padding: 6px;">4/1</td>
              <td style="padding: 6px;">5</td>
              <td style="padding: 6px;"><span style="color: #34d399; font-weight: bold;">🟢 กำลังทำข้อสอบ</span></td>
              <td style="padding: 6px;"><span style="color: #fbbf24; font-weight: bold;">1 / 3 ⚠️</span></td>
              <td style="padding: 6px; text-align: center;"><span style="color: #f87171; cursor: pointer;">เอาออก</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
</div>


---

### 2.8 การดูผลสอบและตรวจให้คะแนนอัตนัยด้วย AI (Results & AI Grading)
คลิกที่เมนู **"📊 ผลสอบและตรวจอัตนัย"**:
* ระบบจะแสดงตารางรายชื่อนักเรียนที่ส่งข้อสอบแล้ว คะแนนปรนัย คะแนนอัตนัย และสถานะความเสี่ยง
* สามารถเลือก **กรองห้องเรียน** หรือ **กรองรอบการสอบ** ได้
* **การตรวจข้อสอบอัตนัย (ข้อเขียน)**:
  1. คลิกปุ่ม **"🔍 ตรวจคำตอบ"** ที่รายชื่อนักเรียนที่ต้องการ
  2. จะปรากฏหน้าต่างแสดงคำตอบของนักเรียนเทียบกับเฉลยแนวทางของคุณครู
  3. **คุณครูสามารถเลือกตรวจได้ 2 แบบ**:
     - **ตรวจด้วยตัวเอง**: พิมพ์ตัวเลขคะแนนและข้อเสนอแนะลงในช่องคะแนนโดยตรง
     - **ใช้ AI ช่วยตรวจ (✨ ให้ AI Gemini ตรวจคำตอบ)**:
       - กดปุ่ม **"✨ ตรวจด้วย AI"**
       - AI จะประเมินความสอดคล้องของคำตอบกับเฉลย พร้อมให้คะแนนและระบุเหตุผลภาษาไทยอย่างละเอียด
       - คุณครูสามารถปรับแก้คะแนนที่ AI แนะนำได้ตามความเหมาะสม
  4. กดปุ่ม **"💾 บันทึกคะแนน"** ระบบจะคำนวณคะแนนรวมและสถานะผ่าน/ไม่ผ่านใหม่อัตโนมัติ

#### ตัวอย่างหน้าต่างตรวจคำตอบอัตนัยด้วย Gemini AI (AI Grading Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ ระบบตรวจคำตอบอัตนัยด้วย Google Gemini AI (AI-Assisted Grading)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: AI Grading Modal</span>
  </div>
  
  <div style="max-width: 640px; margin: 0 auto; background: rgba(15,23,42,0.95); border: 1px solid rgba(168,85,247,0.35); border-radius: 20px; padding: 22px; box-shadow: 0 15px 35px rgba(0,0,0,0.6);">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; margin-bottom: 14px;">
      <div>
        <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #fff;">🔍 ตรวจข้อสอบอัตนัย: นายสมชาย ใจดี (ม.4/1 เลขที่ 12)</h4>
        <p style="margin: 2px 0 0; font-size: 11px; color: #94a3b8;">คำถามข้อที่ 2 (อัตนัย 5 คะแนน) | เกณฑ์ AI: Gemini 2.0 Flash</p>
      </div>
      <span style="font-size: 10px; padding: 3px 8px; border-radius: 6px; background: rgba(168,85,247,0.2); color: #c084fc; border: 1px solid rgba(168,85,247,0.4); font-weight: bold;">✨ Gemini AI</span>
    </div>

    <!-- Student Ans vs Model Ans -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 11px; margin-bottom: 12px;">
      <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(56,189,248,0.25); border-radius: 10px; padding: 10px;">
        <span style="color: #38bdf8; font-weight: bold; display: block; margin-bottom: 4px;">คำตอบของนักเรียน:</span>
        <p style="margin: 0; color: #e2e8f0; line-height: 1.5;">"Controller ทำหน้าที่รับ Request จากผู้ใช้ ประมวลผลตรรกะ และเรียก View มาแสดงผลลัพธ์"</p>
      </div>
      <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(245,158,11,0.25); border-radius: 10px; padding: 10px;">
        <span style="color: #fbbf24; font-weight: bold; display: block; margin-bottom: 4px;">เฉลยแนวทางของคุณครู:</span>
        <p style="margin: 0; color: #e2e8f0; line-height: 1.5;">"Controller เป็นตัวกลางรับ Request ประมวลผล และส่งต่อไปยัง Model และ View"</p>
      </div>
    </div>

    <!-- AI Evaluation Feedback Card -->
    <div style="background: linear-gradient(135deg, rgba(88,28,135,0.4), rgba(15,23,42,0.8)); border: 1px solid rgba(168,85,247,0.45); border-radius: 12px; padding: 14px; margin-bottom: 14px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
        <span style="font-size: 12px; font-weight: 800; color: #c084fc;">✨ ผลการวิเคราะห์จาก Google Gemini:</span>
        <span style="font-size: 12px; font-weight: bold; padding: 2px 8px; border-radius: 6px; background: rgba(16,185,129,0.2); color: #34d399; border: 1px solid rgba(16,185,129,0.4);">ประเมิน: 5.0 / 5.0 คะแนน</span>
      </div>
      <p style="margin: 0; font-size: 11px; color: #cbd5e1; line-height: 1.6;">
        "✅ คำตอบของนักเรียนมีความถูกต้องและครบถ้วนตามหลักการ MVC โดยระบุหน้าที่หลักของ Controller ทั้งการรับ Request ประมวลผลตรรกะ และการส่งต่อข้อมูลไปยัง View ได้อย่างสมบูรณ์ตรงตามเฉลยแนวทาง"
      </p>
    </div>

    <!-- Score Confirmation & Adjust -->
    <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.3); border-radius: 10px; padding: 10px 14px; font-size: 11px;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <span style="font-weight: bold; color: #fff;">คะแนนที่บันทึกจริง:</span>
        <input type="text" value="5.0" readonly style="width: 50px; text-align: center; padding: 4px; border-radius: 6px; background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4); color: #34d399; font-weight: bold; font-size: 12px;">
        <span style="color: #94a3b8;">/ 5.0 คะแนน</span>
      </div>
      <div style="display: flex; gap: 6px;">
        <span style="padding: 6px 12px; border-radius: 6px; background: rgba(168,85,247,0.2); color: #c084fc; border: 1px solid rgba(168,85,247,0.3); font-weight: bold; cursor: pointer;">✨ ให้ AI ตรวจใหม่</span>
        <span style="padding: 6px 16px; border-radius: 6px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; font-weight: bold; cursor: pointer;">💾 บันทึกคะแนน</span>
      </div>
    </div>
  </div>
  
</div>


---

### 2.9 การพิมพ์ประกาศผลสอบ และส่งออกคะแนนเป็น Excel/CSV
ในหน้า **"📊 ผลสอบและตรวจอัตนัย"** มีเครื่องมืออำนวยความสะดวก 2 รูปแบบ:

1. **🖨️ พิมพ์ประกาศผลสอบ**:
   - คลิกปุ่ม **"🖨️ พิมพ์ประกาศผลสอบ"**
   - ระบบจะเปิดหน้าต่างพิมพ์รายงานที่เป็นทางการ มีหัวกระดาษตราโรงเรียน ข้อมูลรายวิชา ภาคเรียน ผู้สอน เกณฑ์ผ่าน ตารางคะแนนรวม คะแนนปรนัย อัตนัย และช่องลงชื่อครูผู้สอน
   - พร้อมสั่งพิมพ์ลงกระดาษ A4 หรือบันทึกเป็น PDF ได้ทันที
2. **📥 ส่งออกคะแนน (Excel/CSV)**:
   - กรองห้องเรียนหรือรอบสอบที่ต้องการ (หรือเลือกแสดงทุกห้อง)
   - คลิกปุ่ม **"📥 ส่งออกคะแนน (Excel/CSV)"**
   - ระบบจะดาวน์โหลดไฟล์ `.csv` เข้ารหัสแบบ **UTF-8 with BOM** ทำให้เปิดใน **Microsoft Excel ภาษาไทยได้ทันทีโดยสระไม่เพี้ยน**
   - ประกอบด้วยข้อมูลครบถ้วน: เลขประจำตัว, ชื่อ-สกุล, ห้อง, เลขที่, คะแนนรวม, คะแนนปรนัย, คะแนนอัตนัย, ผลการประเมิน, เวลาที่ใช้ และสถิติทุจริต

#### ตัวอย่างหน้าต่างพิมพ์ประกาศผลสอบและส่งออก Excel (A4 & Export Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ เมนูพิมพ์ใบประกาศผล A4 & ส่งออกไฟล์ Excel (CSV UTF-8 BOM)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Print & Export</span>
  </div>
  
  <!-- Filter & Action Bar -->
  <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(15,23,42,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 14px; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
    <div style="display: flex; align-items: center; gap: 8px; font-size: 11px;">
      <span style="color: #cbd5e1; font-weight: bold;">เลือกห้อง:</span>
      <span style="padding: 4px 8px; border-radius: 6px; background: rgba(255,255,255,0.1); color: #fff;">ม.4/1 ▼</span>
      <span style="color: #cbd5e1; font-weight: bold; margin-left: 6px;">รอบสอบ:</span>
      <span style="padding: 4px 8px; border-radius: 6px; background: rgba(255,255,255,0.1); color: #fff;">รอบที่ 1 ▼</span>
    </div>
    <div style="display: flex; gap: 8px; font-size: 11px;">
      <span style="padding: 6px 14px; border-radius: 8px; background: rgba(56,189,248,0.2); color: #38bdf8; border: 1px solid rgba(56,189,248,0.4); font-weight: bold; cursor: pointer;">🖨️ สั่งพิมพ์ใบประกาศ (A4)</span>
      <span style="padding: 6px 14px; border-radius: 8px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; font-weight: bold; cursor: pointer;">📥 ส่งออกคะแนน (Excel/CSV)</span>
    </div>
  </div>

  <!-- Simulated Official A4 White Paper -->
  <div style="max-width: 580px; margin: 0 auto; background: #ffffff; color: #1e293b; border-radius: 10px; padding: 22px; box-shadow: 0 8px 25px rgba(0,0,0,0.35); font-family: 'Sarabun', sans-serif;">
    <div style="text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 12px;">
      <div style="font-size: 20px; margin-bottom: 2px;">🏫</div>
      <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #0f172a;">ประกาศผลคะแนนการสอบวัดผลสัมฤทธิ์ทางการเรียน</h4>
      <p style="margin: 2px 0 0; font-size: 12px; font-weight: 600; color: #334155;">รายวิชา ว30291 การสร้าง Web Application (สอบกลางภาค 1/2569)</p>
      <p style="margin: 2px 0 0; font-size: 10px; color: #64748b;">ห้อง ม.4/1 | คะแนนเต็ม: 20 คะแนน | เกณฑ์ผ่าน: 50% (10 คะแนน)</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 10px; text-align: left; margin-bottom: 14px;">
      <thead>
        <tr style="background: #f1f5f9; border-bottom: 1.5px solid #cbd5e1; color: #334155;">
          <th style="padding: 5px; text-align: center;">ลำดับ</th>
          <th style="padding: 5px;">เลขประจำตัว</th>
          <th style="padding: 5px;">ชื่อ - สกุล</th>
          <th style="padding: 5px; text-align: center;">เลขที่</th>
          <th style="padding: 5px; text-align: center;">ปรนัย (15)</th>
          <th style="padding: 5px; text-align: center;">อัตนัย (5)</th>
          <th style="padding: 5px; text-align: center; font-weight: bold;">รวม (20)</th>
          <th style="padding: 5px; text-align: center;">ผลประเมิน</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: 1px solid #f1f5f9;">
          <td style="padding: 5px; text-align: center;">1</td>
          <td style="padding: 5px; font-family: monospace;">55012</td>
          <td style="padding: 5px; font-weight: bold;">นายสมชาย ใจดี</td>
          <td style="padding: 5px; text-align: center;">12</td>
          <td style="padding: 5px; text-align: center;">14.0</td>
          <td style="padding: 5px; text-align: center;">5.0</td>
          <td style="padding: 5px; text-align: center; font-weight: bold; color: #0284c7;">19.0</td>
          <td style="padding: 5px; text-align: center;"><span style="color: #15803d; font-weight: bold;">ผ่าน</span></td>
        </tr>
        <tr style="border-bottom: 1px solid #f1f5f9;">
          <td style="padding: 5px; text-align: center;">2</td>
          <td style="padding: 5px; font-family: monospace;">55005</td>
          <td style="padding: 5px; font-weight: bold;">น.ส.กานดา สดใส</td>
          <td style="padding: 5px; text-align: center;">5</td>
          <td style="padding: 5px; text-align: center;">12.0</td>
          <td style="padding: 5px; text-align: center;">4.5</td>
          <td style="padding: 5px; text-align: center; font-weight: bold; color: #0284c7;">16.5</td>
          <td style="padding: 5px; text-align: center;"><span style="color: #15803d; font-weight: bold;">ผ่าน</span></td>
        </tr>
      </tbody>
    </table>

    <div style="display: flex; justify-content: space-between; font-size: 10px; color: #64748b; padding-top: 6px;">
      <div>วันเวลาที่พิมพ์: 14/09/2569 15:30 น.</div>
      <div style="text-align: center;">
        ลงชื่อ..........................................................ครูผู้สอน<br>
        (กลุ่มสาระการเรียนรู้วิทยาศาสตร์และเทคโนโลยี)
      </div>
    </div>
  </div>
  
</div>


---

### 2.10 การตรวจสอบความเสี่ยงและประวัติทุจริต (Anti-Cheating & Risk Logs)
คลิกที่เมนู **"🚨 บันทึกความเสี่ยง/ทุจริต"**:
* แสดงบันทึกเหตุการณ์ความปลอดภัยทั้งหมด เช่น:
  - การสลับแท็บเบราว์เซอร์ (Visibility Hidden)
  - การคลิกออกนอกหน้าจอสอบ (Window Blur)
  - ความพยายามกดปุ่ม F12 หรือเข้า DevTools
  - วันเวลาที่เกิดเหตุ พร้อมข้อมูลเบราว์เซอร์และระบบปฏิบัติการ
* หากนักเรียนทำผิดกฎจนถูกตัดสิทธิ์ ครูสามารถตรวจสอบเหตุผลได้จากหน้านี้ และหากต้องการให้นักเรียนสอบใหม่ สามารถไปที่เมนูผลสอบแล้วลบผลสอบเดิมออกเพื่อให้นักเรียนลงทะเบียนใหม่ได้

#### ตัวอย่างหน้าจอบันทึกความเสี่ยงและพฤติกรรมทุจริต (Risk Logs Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ บันทึกความเสี่ยงและพฤติกรรมทุจริต (Anti-Cheating Logs)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Risk Logs</span>
  </div>
  
  <!-- Stat Counters -->
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 14px; font-size: 11px;">
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px;">
      <span style="color: #94a3b8; font-size: 10px; display: block;">เหตุการณ์สลับจอทั้งหมด</span>
      <span style="font-size: 16px; font-weight: 800; color: #fbbf24;">14 ครั้ง</span>
    </div>
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px;">
      <span style="color: #94a3b8; font-size: 10px; display: block;">ผู้เข้าสอบเสี่ยงสูง (> 2 ครั้ง)</span>
      <span style="font-size: 16px; font-weight: 800; color: #f87171;">1 คน</span>
    </div>
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px;">
      <span style="color: #94a3b8; font-size: 10px; display: block;">ระงับสิทธิ์สอบ (Disqualified)</span>
      <span style="font-size: 16px; font-weight: 800; color: #34d399;">0 คน</span>
    </div>
  </div>

  <!-- Log Table -->
  <div style="background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 10px; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 11px; text-align: left;">
      <thead>
        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #94a3b8;">
          <th style="padding: 6px;">วัน-เวลา</th>
          <th style="padding: 6px;">ผู้เข้าสอบ</th>
          <th style="padding: 6px;">ประเภทเหตุการณ์</th>
          <th style="padding: 6px;">IP Address</th>
          <th style="padding: 6px;">Strike ปัจจุบัน</th>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); color: #cbd5e1;">
          <td style="padding: 6px; font-family: monospace; font-size: 10px;">14/09/2026 10:15:22</td>
          <td style="padding: 6px; font-weight: bold; color: #fff;">นายสุรศักดิ์ (4/2 #15)</td>
          <td style="padding: 6px;"><span style="padding: 2px 6px; border-radius: 4px; background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); font-size: 10px; font-weight: bold;">⚠️ Visibility Hidden (สลับแท็บ)</span></td>
          <td style="padding: 6px; font-family: monospace; font-size: 10px;">192.168.1.45</td>
          <td style="padding: 6px;"><span style="color: #fbbf24; font-weight: bold;">1 / 3 ครั้ง</span></td>
        </tr>
        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); color: #cbd5e1;">
          <td style="padding: 6px; font-family: monospace; font-size: 10px;">14/09/2026 10:18:05</td>
          <td style="padding: 6px; font-weight: bold; color: #fff;">นายสุรศักดิ์ (4/2 #15)</td>
          <td style="padding: 6px;"><span style="padding: 2px 6px; border-radius: 4px; background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); font-size: 10px; font-weight: bold;">⚠️ Window Blur (คลิกออกนอกจอ)</span></td>
          <td style="padding: 6px; font-family: monospace; font-size: 10px;">192.168.1.45</td>
          <td style="padding: 6px;"><span style="color: #f87171; font-weight: bold;">2 / 3 ครั้ง 🚨</span></td>
        </tr>
      </tbody>
    </table>
  </div>
  
</div>


---

### 2.11 การตั้งค่าระบบส่วนกลาง (Global Settings)
เข้าใช้งานผ่านเมนู **"⚙️ ตั้งค่าส่วนกลาง"** หรือ URL `/teacher/settings`:
* **ชื่อเว็บไซต์**: ชื่อระบบที่แสดงบนหัวเว็บ
* **ลิงก์โลโก้**: URL รูปภาพโลโก้ของโรงเรียน
* **รหัสผ่านผู้ดูแลระบบ (Admin Password)**: รหัสผ่านสำหรับเข้าสู่ระบบสำรอง
* **Google Client ID**: รหัส OAuth สำหรับการล็อกอินด้วย Google Workspace
* **Gemini API Key**: คีย์ API ของ Google AI Studio สำหรับเปิดใช้งานระบบตรวจข้อเขียนอัจฉริยะ

---

## 3. คู่มือสำหรับนักเรียนผู้เข้าสอบ (Student Portal)

### 3.1 การเลือกลงทะเบียนเข้าห้องสอบ (Registration)
1. เข้าเว็บไซต์ระบบสอบผ่านเบราว์เซอร์ (แนะนำ Google Chrome บนคอมพิวเตอร์ หรือ Safari/Chrome บนมือถือและแท็บเล็ต)
2. เลือกลิสต์ **"รายวิชาที่ต้องการเข้าสอบ"**
3. กรอกข้อมูลส่วนตัวให้ถูกต้อง:
   - **ชื่อ - นามสกุล**
   - **เลขประจำตัวนักเรียน (Student ID)**
   - **ห้องเรียน (เช่น 4/1)**
   - **เลขที่**
   - **อีเมล** (ใช้เป็นรหัสอ้างอิงในการบันทึกผลสอบ)
4. คลิก **"เข้าสู่ห้องสอบ 🚀"**

#### ตัวอย่างหน้าจอลงทะเบียนเข้าสอบของนักเรียน (Student Register Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ https://exam.skj.ac.th/ (Student Portal)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Student Register</span>
  </div>
  
  <div style="max-width: 440px; margin: 15px auto; background: rgba(30, 41, 59, 0.75); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 24px; box-shadow: 0 12px 30px rgba(0,0,0,0.4); text-align: center;">
    <div style="font-size: 32px; margin-bottom: 6px;">📝</div>
    <h4 style="margin: 0; font-size: 17px; font-weight: 800; color: #fff;">ลงทะเบียนเข้าสู่ห้องสอบ</h4>
    <p style="margin: 3px 0 16px; font-size: 11px; color: #94a3b8;">กรุณากรอกข้อมูลส่วนตัวให้ถูกต้องเพื่อบันทึกผลคะแนน</p>

    <div style="display: flex; flex-direction: column; gap: 10px; text-align: left; font-size: 11px; margin-bottom: 16px;">
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">เลือกวิชาสอบ:</label>
        <div style="padding: 8px 10px; border-radius: 8px; background: rgba(0,0,0,0.4); border: 1px solid rgba(56,189,248,0.3); color: #38bdf8; font-weight: bold;">
          ว30291 การสร้าง Web Application (กลางภาค) ▼
        </div>
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">เลขประจำตัวนักเรียน:</label>
        <input type="text" value="55012" readonly style="width: 100%; box-sizing: border-box; padding: 8px 10px; border-radius: 8px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div>
        <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">ชื่อ - นามสกุล:</label>
        <input type="text" value="นายสมชาย ใจดี" readonly style="width: 100%; box-sizing: border-box; padding: 8px 10px; border-radius: 8px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
        <div>
          <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">ห้อง (เช่น 4/1):</label>
          <input type="text" value="4/1" readonly style="width: 100%; box-sizing: border-box; padding: 8px 10px; border-radius: 8px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
        </div>
        <div>
          <label style="color: #cbd5e1; font-weight: bold; display: block; margin-bottom: 4px;">เลขที่:</label>
          <input type="text" value="12" readonly style="width: 100%; box-sizing: border-box; padding: 8px 10px; border-radius: 8px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.15); color: #fff; font-size: 11px;">
        </div>
      </div>
    </div>

    <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; padding: 8px 12px; margin-bottom: 14px; font-size: 10px; color: #fca5a5; text-align: left;">
      🛡️ <b>กฎข้อสำคัญ:</b> ระบบตรวจจับการสลับหน้าจอ (โควตา 3 ครั้ง) หากสลับจอเกินกำหนด ระบบจะส่งข้อสอบและระงับสิทธิ์ทันที
    </div>

    <button style="width: 100%; padding: 10px; border-radius: 10px; background: linear-gradient(135deg, #ec4899, #0284c7); color: #fff; font-weight: bold; font-size: 13px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(236,72,153,0.35);">
      เข้าสู่ห้องสอบ 🚀
    </button>
  </div>
  
</div>


---

### 3.2 ห้องพักคอย (Waiting Lobby)
* **เมื่อใช้งานบนคอมพิวเตอร์ (PC / Laptop)**:
  - จะเข้าสู่ **มินิเกม 2D Canvas**: นักเรียนสามารถใช้ปุ่มลูกศร (Arrow Keys) หรือแป้น `W`, `A`, `S`, `D` ในการบังคับตัวละครเดินเล่นในห้องพักคอย
  - ตัวละครของเพื่อนๆ ในห้องจะปรากฏบนหน้าจอแบบเรียลไทม์
* **เมื่อใช้งานบนโทรศัพท์มือถือ (Mobile)**:
  - หน้าจอจะแสดงการ์ดรอสอบพร้อมนาฬิกาดิจิทัลนับเวลาอย่างสวยงาม
* **เมื่อคุณครูสั่งเริ่มการสอบ**:
  - หน้าจอจะนับถอยหลัง 5 วินาทีโดยอัตโนมัติ และนำเข้าสู่ข้อสอบทันทีโดยไม่ต้องกดรีเฟรช

#### ตัวอย่างหน้าจอห้องพักคอย 2D Lobby (Student Lobby Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ https://exam.skj.ac.th/lobby (ห้องพักคอย 2D Lobby)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Waiting Lobby</span>
  </div>
  
  <div style="background: #090d16; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 18px; text-align: center; position: relative;">
    <div style="display: inline-block; padding: 4px 14px; border-radius: 9999px; background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.4); font-size: 12px; font-weight: bold; margin-bottom: 10px;">
      ⏳ กำลังรอคุณครูผู้คุมสอบสั่งเริ่มการสอบ...
    </div>
    <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #fff;">วิชา ว30291 การสร้าง Web Application</h4>
    <p style="margin: 4px 0 14px; font-size: 11px; color: #94a3b8;">ผู้เข้าสอบในห้องขณะนี้: <b>28 คน</b> | ใช้ปุ่มลูกศรหรือ W, A, S, D เพื่อบังคับตัวละคร</p>

    <!-- Mini 2D Canvas Map -->
    <div style="background: #020617; border: 1px solid rgba(56,189,248,0.25); border-radius: 12px; height: 140px; position: relative; overflow: hidden; margin-bottom: 12px;">
      <div style="position: absolute; inset: 0; opacity: 0.15; background-image: radial-gradient(#38bdf8 1px, transparent 1px); background-size: 14px 14px;"></div>
      <div style="position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
        <span style="font-size: 28px;">🧙‍♂️</span>
        <div style="font-size: 9px; font-weight: bold; background: rgba(56,189,248,0.3); color: #38bdf8; padding: 2px 6px; border-radius: 4px;">นายสมชาย (คุณ)</div>
      </div>
      <div style="position: absolute; top: 25%; left: 20%; text-align: center;">
        <span style="font-size: 24px;">👩‍🎓</span>
        <div style="font-size: 8px; color: #94a3b8;">กานดา</div>
      </div>
      <div style="position: absolute; top: 60%; left: 75%; text-align: center;">
        <span style="font-size: 24px;">🧑‍💻</span>
        <div style="font-size: 8px; color: #94a3b8;">พงศกร</div>
      </div>
    </div>

    <div style="display: flex; justify-content: center; gap: 8px; font-size: 10px; color: #64748b;">
      <span style="padding: 3px 8px; border-radius: 4px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">[ W ] เดินขึ้น</span>
      <span style="padding: 3px 8px; border-radius: 4px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">[ A ] เดินซ้าย</span>
      <span style="padding: 3px 8px; border-radius: 4px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">[ S ] เดินลง</span>
      <span style="padding: 3px 8px; border-radius: 4px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">[ D ] เดินขวา</span>
    </div>
  </div>
  
</div>


---

### 3.3 การทำข้อสอบโหมดมาตรฐาน (Classic Exam Interface)
1. **แถบด้านบน**: แสดงจำนวนข้อที่ทำไปแล้ว, แถบความคืบหน้า (Progress Bar) และเวลาที่เหลือในข้อปัจจุบัน
2. **ข้อสอบปรนัย (ตัวเลือก)**:
   - อ่านโจทย์คำถาม (หากมีรูปภาพ สามารถคลิกที่รูปเพื่อขยายใหญ่ได้)
   - คลิกเลือกคำตอบที่ถูกต้อง ก, ข, ค หรือ ง
   - กดปุ่ม **"ข้อถัดไป ➡️"**
   - *หมายเหตุ: หากเวลาประจำข้อหมดลง ระบบจะบันทึกคำตอบปัจจุบันและเปลี่ยนไปยังข้อถัดไปโดยอัตโนมัติ*
3. **ข้อสอบอัตนัย (เขียนตอบ)**:
   - อ่านโจทย์คำถาม
   - พิมพ์คำตอบลงในกล่องข้อความอย่างละเอียด
   - กดปุ่ม **"บันทึกและไปต่อ ➡️"**

#### ตัวอย่างหน้าจอทำข้อสอบโหมดมาตรฐาน (Classic Exam Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ https://exam.skj.ac.th/exam (หน้าทำข้อสอบมาตรฐาน)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Classic Exam</span>
  </div>
  
  <div style="max-width: 620px; margin: 0 auto; background: rgba(15,23,42,0.9); border: 1px solid rgba(255,255,255,0.1); border-radius: 18px; padding: 20px;">
    <!-- Top Progress Bar & Timer -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 11px;">
      <span style="color: #94a3b8; font-weight: bold;">ข้อที่ 5 จาก 20 ข้อ</span>
      <span style="padding: 3px 8px; border-radius: 6px; background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.4); font-family: monospace; font-weight: bold;">⏳ เวลาข้อนี้: 00:42 นาที</span>
    </div>
    <div style="height: 6px; width: 100%; border-radius: 3px; background: rgba(255,255,255,0.1); margin-bottom: 16px; overflow: hidden;">
      <div style="width: 25%; height: 100%; background: linear-gradient(90deg, #ec4899, #38bdf8);"></div>
    </div>

    <!-- Question Box -->
    <div style="background: rgba(30,41,59,0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 16px; margin-bottom: 14px;">
      <span style="font-size: 11px; font-weight: bold; color: #f472b6; display: block; margin-bottom: 4px;">คำถามข้อที่ 5 (ปรนัย 1 คะแนน):</span>
      <h4 style="margin: 0 0 10px; font-size: 14px; font-weight: 800; color: #fff; line-height: 1.5;">แท็ก HTML ใดต่อไปนี้ใช้สำหรับเชื่อมโยงไฟล์ภายนอก เช่น StyleSheet เข้ามาในเอกสาร?</h4>
    </div>

    <!-- Choices -->
    <div style="display: flex; flex-direction: column; gap: 8px; font-size: 12px; margin-bottom: 16px;">
      <div style="padding: 10px 14px; border-radius: 10px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; cursor: pointer;">
        <span style="font-weight: bold; color: #94a3b8; margin-right: 8px;">A.</span> &lt;script&gt;
      </div>
      <div style="padding: 10px 14px; border-radius: 10px; background: rgba(56,189,248,0.2); border: 1.5px solid #38bdf8; color: #fff; font-weight: bold; cursor: pointer;">
        <span style="font-weight: bold; color: #38bdf8; margin-right: 8px;">B.</span> &lt;link&gt; (เลือกข้อนี้แล้ว 🟢)
      </div>
      <div style="padding: 10px 14px; border-radius: 10px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; cursor: pointer;">
        <span style="font-weight: bold; color: #94a3b8; margin-right: 8px;">C.</span> &lt;style&gt;
      </div>
      <div style="padding: 10px 14px; border-radius: 10px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; cursor: pointer;">
        <span style="font-weight: bold; color: #94a3b8; margin-right: 8px;">D.</span> &lt;href&gt;
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 12px;">
      <span style="padding: 6px 12px; border-radius: 8px; background: rgba(255,255,255,0.06); color: #94a3b8; font-size: 11px; cursor: pointer;">🚩 ปักหมุดทบทวน</span>
      <span style="padding: 8px 18px; border-radius: 8px; background: linear-gradient(135deg, #0284c7, #2563eb); color: #fff; font-weight: bold; font-size: 12px; cursor: pointer;">บันทึกและข้อถัดไป ➡️</span>
    </div>
  </div>
  
</div>


---

### 3.4 การทำข้อสอบโหมดพิเศษ: Pokémon RPG Adventure
หากวิชานั้นถูกตั้งค่าเป็นโหมด Pokémon:
1. นักเรียนจะควบคุมตัวละครเทรนเนอร์เดินสำรวจในแผนที่เมือง 2D
2. เดินเข้าไปชนกับ **NPC อาจารย์** หรือ **โปเกมอนคู่ประลอง** ที่ประจำอยู่ตามจุดต่างๆ
3. ระบบจะตัดเข้าสู่ **ฉากแบทเทิลคำถาม (Battle Arena)**
4. เลือกท่าโจมตีซึ่งเป็นคำตอบของข้อสอบเพื่อพิชิตคะแนน
5. ทำโจทย์ให้ครบตามจำนวนข้อที่กำหนดเพื่อจบการผจญภัย

---

### 3.5 กฎเหล็กและระบบตรวจจับการทุจริต (Anti-Cheating Engine)
> [!CAUTION]
> **ระบบมีความเข้มงวดในการตรวจจับพฤติกรรมเสี่ยงทุจริตแบบเรียลไทม์:**
> * ❌ **ห้ามสลับแท็บเบราว์เซอร์ หรือเปิดหน้าต่างอื่น**
> * ❌ **ห้ามย่อหน้าต่าง หรือสลับไปเปิดแอปพลิเคชันอื่น**
> * ❌ **ห้ามคลิกขวา หรือพยายามคัดลอกข้อความ**
> * ❌ **ห้ามกดปุ่ม F12 หรือพยายามตรวจสอบโค้ดหน้าเว็บ**

* **ระบบการเตือน (Cheating Strikes)**:
  - หากนักเรียนสลับหน้าจอ ระบบจะส่งเสียงเตือนและแสดงหน้าต่างเตือนสีแดงทันที พร้อมหักโควตา Strike
  - ระบบจะส่งประวัติและวันเวลาไปยังคุณครูผู้คุมสอบแบบเรียลไทม์
  - **หากทำผิดกฎครบตามจำนวนครั้งที่กำหนด (เช่น ครบ 3 ครั้ง)**: ระบบจะยุติการสอบ ส่งกระดาษคำตอบทันที และปรับสถานะเป็น **"ระงับสิทธิ์สอบเนื่องจากพบการทุจริต (Disqualified)"**

#### ตัวอย่างหน้าต่างแจ้งเตือนการสลับหน้าจอ (Strike Warning Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">⚠️ หน้าต่างแจ้งเตือนระบบความปลอดภัย (Security Violation Alert)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Anti-Cheat Strike Alert</span>
  </div>
  
  <div style="max-width: 460px; margin: 10px auto; background: rgba(20, 10, 15, 0.95); border: 2px solid #ef4444; border-radius: 20px; padding: 24px; text-align: center; box-shadow: 0 0 40px rgba(239, 68, 68, 0.35);">
    <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(239,68,68,0.2); border: 2px solid #ef4444; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; font-size: 26px;">
      🚨
    </div>
    <h4 style="margin: 0; font-size: 17px; font-weight: 800; color: #f87171;">ตรวจพบพฤติกรรมเสี่ยงทุจริต!</h4>
    <p style="margin: 6px 0 14px; font-size: 12px; color: #fca5a5; line-height: 1.5;">
      ระบบตรวจพบว่าคุณได้ทำการสลับหน้าจอ (Switch Tab) หรือคลิกออกนอกหน้าต่างข้อสอบ ซึ่งเป็นการกระทำที่ผิดกฎระเบียบ
    </p>

    <div style="background: rgba(0,0,0,0.5); border: 1px solid rgba(239,68,68,0.4); border-radius: 12px; padding: 12px; margin-bottom: 16px;">
      <span style="font-size: 11px; color: #94a3b8; display: block; margin-bottom: 4px;">จำนวนครั้งที่กระทำผิด:</span>
      <span style="font-size: 20px; font-weight: 900; color: #ef4444;">1 / 3 ครั้ง ⚠️</span>
      <p style="margin: 4px 0 0; font-size: 10px; color: #94a3b8;">(หากครบ 3 ครั้ง ระบบจะระงับสิทธิ์สอบและส่งคะแนนปัจจุบันทันที)</p>
    </div>

    <button style="padding: 10px 22px; border-radius: 10px; background: #ef4444; color: #fff; font-weight: bold; font-size: 12px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(239,68,68,0.4);">
      ข้าพเจ้ารับทราบและจะกลับเข้าสู่ข้อสอบ ⚠️
    </button>
  </div>
  
</div>


---

### 3.6 การส่งข้อสอบและการดูรายงานผลคะแนน (Result Summary)
เมื่อทำข้อสอบครบทุกข้อ หรือกดยืนยันส่งข้อสอบ:
1. ระบบจะประเมินคะแนนของ **ตอนที่ 1: ข้อสอบปรนัย** ให้ทราบทันที
2. **ตอนที่ 2: ข้อสอบอัตนัย** จะแสดงสถานะ *"รอการตรวจจากครูผู้สอน"*
3. แสดงผลเวลาที่ใช้ทั้งหมด และสถานะผ่าน/ไม่ผ่านตามเกณฑ์
4. เมื่อครูผู้สอนตรวจข้อเขียนเสร็จสิ้น ผลคะแนนรวมฉบับสมบูรณ์จะได้รับการอัปเดตเข้าระบบ

#### ตัวอย่างหน้ารายงานผลคะแนนสอบ (Student Result Mockup):

<div style="background: #0b1120; border-radius: 16px; border: 1px solid rgba(255,255,255,0.12); padding: 16px; margin: 18px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Sarabun', sans-serif; color: #e2e8f0; box-shadow: 0 12px 30px -8px rgba(0,0,0,0.55); overflow: hidden;">
  <!-- Window Header Bar -->
  <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 10px; margin-bottom: 14px;">
    <div style="display: flex; align-items: center; gap: 6px;">
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #ef4444;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #f59e0b;"></span>
      <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; background: #10b981;"></span>
      <span style="margin-left: 8px; font-size: 11px; color: #94a3b8; font-family: monospace; font-weight: 600;">🖥️ https://exam.skj.ac.th/result (หน้ารายงานผลคะแนนสอบ)</span>
    </div>
    <span style="font-size: 10px; padding: 2px 8px; border-radius: 9999px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); font-weight: bold;">CSS UI: Student Result</span>
  </div>
  
  <div style="max-width: 480px; margin: 10px auto; background: rgba(15,23,42,0.9); border: 1px solid rgba(16,185,129,0.35); border-radius: 20px; padding: 24px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
    <div style="font-size: 34px; margin-bottom: 6px;">🎉</div>
    <span style="padding: 3px 10px; border-radius: 9999px; background: rgba(16,185,129,0.2); color: #34d399; border: 1px solid rgba(16,185,129,0.4); font-size: 11px; font-weight: bold;">
      ส่งกระดาษคำตอบเรียบร้อยแล้ว
    </span>
    <h4 style="margin: 8px 0 2px; font-size: 18px; font-weight: 800; color: #fff;">ผลการสอบ: ผ่านเกณฑ์ประเมิน 🌟</h4>
    <p style="margin: 0 0 16px; font-size: 11px; color: #94a3b8;">ว30291 การสร้าง Web Application (สอบกลางภาค)</p>

    <!-- Big Score Ring Simulation -->
    <div style="width: 110px; height: 110px; border-radius: 50%; border: 4px solid #10b981; margin: 0 auto 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(16,185,129,0.08); box-shadow: 0 0 25px rgba(16,185,129,0.2);">
      <span style="font-size: 24px; font-weight: 900; color: #fff;">18.5</span>
      <span style="font-size: 10px; color: #94a3b8;">เต็ม 20.0</span>
    </div>

    <!-- Breakdown Table -->
    <div style="background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 12px; font-size: 11px; margin-bottom: 16px; text-align: left;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
        <span style="color: #cbd5e1;">ตอนที่ 1 (ข้อสอบปรนัย):</span>
        <span style="font-weight: bold; color: #38bdf8;">14.5 / 15.0 คะแนน</span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
        <span style="color: #cbd5e1;">ตอนที่ 2 (ข้อสอบอัตนัย):</span>
        <span style="font-weight: bold; color: #c084fc;">4.0 / 5.0 คะแนน (ตรวจแล้ว)</span>
      </div>
      <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 6px;">
        <span style="color: #cbd5e1;">สถิติการสลับหน้าจอ:</span>
        <span style="font-weight: bold; color: #34d399;">0 ครั้ง (ปฏิบัติตามกฎดีเยี่ยม)</span>
      </div>
    </div>

    <button style="padding: 8px 24px; border-radius: 8px; background: rgba(255,255,255,0.1); color: #fff; font-size: 11px; font-weight: bold; border: 1px solid rgba(255,255,255,0.15); cursor: pointer;">
      🏠 กลับสู่หน้าหลัก
    </button>
  </div>
  
</div>


---

## 4. คำถามที่พบบ่อยและการแก้ไขปัญหา (FAQ & Troubleshooting)

### Q1: เข้าสู่ระบบสำหรับครูไม่ได้ เกิดข้อผิดพลาด Google Token
* **สาเหตุ**: บัญชี Google ที่ใช้ล็อกอินไม่ใช่อีเมลโดเมน `@skj.ac.th` หรือยังไม่มีข้อมูลบุคลากรในฐานข้อมูลของโรงเรียน
* **แนวทางแก้ไข**: 
  1. ตรวจสอบให้แน่ใจว่าล็อกอินด้วยอีเมล `@skj.ac.th`
  2. หรือคลิกที่ลิงก์ **"🔑 หรือเข้าสู่ระบบด้วยรหัสผ่านผู้ดูแลระบบ"** ด้านล่างปุ่ม Google แล้วกรอกรหัสผ่าน Master Admin (`admin1234`) เพื่อเข้าใช้งานได้ทันที

### Q2: นักเรียนหลุดออกจากข้อสอบ หรือเบราว์เซอร์ปิดตัวลงโดยไม่ตั้งใจ
* **แนวทางแก้ไข**: 
  - หากนักเรียนยังสอบไม่เสร็จและยังไม่ส่งข้อสอบ สามารถพิมพ์ข้อมูลเดิม (อีเมลเดิม) เข้ามาทำต่อได้
  - หากระบบส่งข้อสอบไปแล้วเนื่องจาก Strike ครบ หรือหมดเวลา ให้คุณครูไปที่เมนู **"📊 ผลสอบและตรวจอัตนัย"** ค้นหาชื่อนักเรียน แล้วคลิกปุ่ม **"🗑️ ลบผลสอบ"** ประวัติการลงทะเบียนและ Log ทุจริตจะถูกล้างออก ทำให้นักเรียนสามารถลงทะเบียนเข้าทำข้อสอบใหม่ได้ทันที

### Q3: ระบบตรวจข้อเขียนด้วย AI ไม่ตอบสนอง หรือขึ้น Error
* **สาเหตุ**: คีย์ Google Gemini API Key ในการตั้งค่าหมดอายุ หรือยังไม่ได้ระบุ
* **แนวทางแก้ไข**:
  1. เข้าสู่ระบบครู ไปที่เมนู **"⚙️ ตั้งค่าส่วนกลาง"**
  2. ตรวจสอบช่อง **Gemini API Key** ให้ถูกต้อง (สามารถขอรับฟรีได้จาก [Google AI Studio](https://aistudio.google.com/))
  3. *หมายเหตุ: หากไม่มี API Key ระบบจะมีอัลกอริทึม Text Similarity ตรวจจับคีย์เวิร์ดและความคล้ายคลึงของประโยคแบบออฟไลน์เป็นระบบสำรองให้อัตโนมัติ*

### Q4: ต้องการดาวน์โหลดคะแนนเพื่อนำไปใช้ใน Microsoft Excel แต่ภาษาไทยกลายเป็นตัวต่างดาว
* **แนวทางแก้ไข**:
  - ระบบในเวอร์ชันปัจจุบันได้ฝังค่า **UTF-8 BOM** ลงในไฟล์ `.csv` เรียบร้อยแล้ว เมื่อคลิกปุ่ม **"📥 ส่งออกคะแนน (Excel/CSV)"** และเปิดไฟล์ด้วย Microsoft Excel หรือ Google Sheets จะแสดงภาษาไทยได้อย่างถูกต้อง 100% โดยไม่ต้องแปลงรหัสภาษา

### Q5: การตรวจสอบการทำงานของระบบเซิร์ฟเวอร์ (Docker Container)
หากต้องการตรวจสอบสถานะบริการเซิร์ฟเวอร์:
```bash
# ตรวจสอบสถานะคอนเทนเนอร์
docker ps | grep exam_app

# ตรวจสอบการทำงานของระบบเว็บ
docker exec exam_app php -v
docker exec exam_app php spark db:table settings
```

---
*จัดทำขึ้นเพื่อใช้เป็นคู่มือมาตรฐานในการจัดการสอบออนไลน์ โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์*

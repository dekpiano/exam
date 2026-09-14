# 🎓 ระบบจัดการข้อสอบออนไลน์ (Online Exam System)
ระบบจัดการและจัดสอบออนไลน์อัจฉริยะ พัฒนาด้วย **CodeIgniter 4**, **MySQL / MariaDB**, **TailwindCSS**, **HTML5 Canvas** และ **Google Gemini 2.0 Flash AI**

---

## 🌟 ฟีเจอร์เด่น (Key Highlights)
- 🏫 **Teacher / Admin Portal**: จัดการรายวิชา คลังข้อสอบ ปรนัย/อัตนัย นำเข้าข้อสอบจาก Excel สั่งเปิด/หยุด/ปิดสอบ
- 🤖 **AI-Assisted Grading**: ช่วยคุณครูประเมินข้อเขียน (อัตนัย) ด้วย Google Gemini 2.0 Flash พร้อมคำอธิบายเหตุผล
- 🛡️ **Anti-Cheating Engine**: ตรวจจับการสลับแท็บ/ย่อหน้าจอ บล็อกคลิกขวา บล็อก DevTools บันทึก Strike และตัดสิทธิ์อัตโนมัติ
- 🎮 **2D Waiting Lobby & Pokémon RPG Mode**: ห้องพักคอย 2D เดินเล่นแบบเรียลไทม์ และโหมดสอบแนวผจญภัยโปเกมอน
- 📊 **Official Print & Excel Export**: พิมพ์ใบประกาศผลสอบ A4 สวยงาม และส่งออกคะแนนเป็นไฟล์ CSV/Excel (UTF-8 BOM ภาษาไทยสมบูรณ์)

---

## 📖 เอกสารคู่มือการใช้งาน (Documentation)
* 📘 [คู่มือการใช้งานระบบฉบับสมบูรณ์ (USER_MANUAL.md)](file:///d:/SkjSystem/exam/USER_MANUAL.md) - ครอบคลุมการใช้งานของคุณครูและนักเรียนอย่างละเอียด
* 📋 [รายละเอียดสถานะระบบและสถาปัตยกรรม (PROJECT_STATUS.md)](file:///d:/SkjSystem/exam/PROJECT_STATUS.md) - บันทึกเชิงเทคนิค โครงสร้างไฟล์ และฐานข้อมูล

---

## 🚀 การติดตั้งและเปิดใช้งานผ่าน Docker (Getting Started)

ระบบรันอยู่บน Docker Container: `exam_app` (พอร์ต `8200`)

```bash
# ตรวจสอบสถานะการทำงานของคอนเทนเนอร์
docker ps | grep exam_app

# ทดสอบตรวจสอบการเชื่อมต่อฐานข้อมูล
docker exec exam_app php spark db:table settings

# ดูบันทึกการทำงาน (Logs)
docker logs -f exam_app
```

### การเข้าใช้งาน:
* **ฝั่งนักเรียน**: `http://localhost:8200/`
* **ฝั่งคุณครู / ผู้ดูแลระบบ**: `http://localhost:8200/teacher`
  - ล็อกอินด้วยบัญชี Google Workspace โรงเรียน (`@skj.ac.th`)
  - หรือคลิกเข้าสู่ระบบด้วยรหัสผ่านผู้ดูแลระบบ (รหัสผ่านเริ่มต้น: `admin1234`)

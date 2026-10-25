# D51 Construction — d51cons.com

เว็บไซต์บริษัทรับก่อสร้าง D51 Construction: บ้าน พูลวิลล่า อาคารพาณิชย์ และรีโนเวต

สถานะ: บรีฟสำหรับเริ่มพัฒนาและภาพแนวคิด ยังไม่มี application หรือ production deployment

## Start here

1. [บรีฟพัฒนาเว็บไซต์](docs/WEBSITE_BRIEF.md) — scope, UX, architecture, data, acceptance criteria
2. [CI และระบบภาพ](docs/BRAND_AND_ASSETS.md) — design tokens, image inventory, references
3. [GitHub → FTPS deployment](docs/DEPLOYMENT_FTPS.md) — build, upload, server setup, rollback

Stack: PHP + Laravel + MySQL; Blade SSR, Vite, CSS และ JavaScript เท่าที่จำเป็น; Resend สำหรับ transactional email. Node.js ใช้ build assets เป็นหลัก ไม่ต้องมี Node server สำหรับ MVP

เริ่มพัฒนาจากเอกสารนี้โดยตรวจ hosting capability ก่อนเลือกเวอร์ชัน Laravel/PHP และยึด lockfiles เมื่อสร้างโปรเจกต์แล้ว ไม่เพิ่ม framework หรือ infrastructure โดยไม่มี use case จริง

ภาพใน `public/images/concepts/` เป็นภาพสร้างด้วย AI สำหรับประกอบแนวคิด ไม่ใช่หลักฐานผลงานก่อสร้างจริง ห้ามแสดงเป็น completed projects ของบริษัท

วันที่บรีฟ: 25 ตุลาคม 2026 (Asia/Bangkok)

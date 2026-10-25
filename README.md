# D51 Construction — d51cons.com

เว็บไซต์บริษัทรับก่อสร้าง D51 Construction: บ้าน พูลวิลล่า อาคารพาณิชย์ และรีโนเวต

สถานะ: backend (Laravel 13) พร้อมใช้งานแล้ว หน้าเว็บสาธารณะยังเป็นโครง HTML รองาน frontend ยังไม่มี production deployment

## Start here

1. [บรีฟพัฒนาเว็บไซต์](docs/WEBSITE_BRIEF.md) — scope, UX, architecture, data, acceptance criteria
2. [CI และระบบภาพ](docs/BRAND_AND_ASSETS.md) — design tokens, image inventory, references
3. [GitHub → FTPS deployment](docs/DEPLOYMENT_FTPS.md) — build, upload, server setup, rollback

Stack: PHP + Laravel + MySQL; Blade SSR, Vite, CSS และ JavaScript เท่าที่จำเป็น; Resend สำหรับ transactional email. Node.js ใช้ build assets เป็นหลัก ไม่ต้องมี Node server สำหรับ MVP

เริ่มพัฒนาจากเอกสารนี้โดยตรวจ hosting capability ก่อนเลือกเวอร์ชัน Laravel/PHP และยึด lockfiles เมื่อสร้างโปรเจกต์แล้ว ไม่เพิ่ม framework หรือ infrastructure โดยไม่มี use case จริง

ภาพใน `public/images/concepts/` เป็นภาพสร้างด้วย AI สำหรับประกอบแนวคิด ไม่ใช่หลักฐานผลงานก่อสร้างจริง ห้ามแสดงเป็น completed projects ของบริษัท

## Backend

Laravel 13 + MariaDB/MySQL, PHP 8.3 ขึ้นไป (hosting จริงเป็น PHP 8.4 + MariaDB 10.4 และ CI ทดสอบบนเวอร์ชันเดียวกัน)

### ติดตั้งบนเครื่องพัฒนา

```sh
composer install
npm ci
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # หรือใส่ค่า MySQL ใน .env
php artisan migrate --seed       # seed: บริการ 4 รายการ (ฉบับร่าง) + ภาพแนวคิด 3 รายการ
php artisan storage:link
php artisan admin:create         # สร้างบัญชีผู้ดูแล (ไม่มีรหัสผ่านเริ่มต้นใน repo)
npm run build
php artisan serve                # เว็บที่ /th, /en และหลังบ้านที่ /admin
```

ทดสอบ: `php artisan test` และ `vendor/bin/pint --test`

### สิ่งที่มี

- Public routes ภายใต้ `/{th|en}`: home, services, projects (กรองตามบริการ), project detail, about, contact, privacy และ `/sitemap.xml` แสดงเฉพาะเนื้อหาที่เผยแพร่ ภาพแนวคิด (`is_concept`) แยกจากผลงานจริงและติดป้ายเสมอ
- ฟอร์มติดต่อ: validate ฝั่ง server, CSRF, honeypot (`website`), rate limit 3 ครั้ง/นาที และ 20 ครั้ง/วันต่อ IP บันทึกลง `inquiries` ก่อนแล้วจึงส่งอีเมลแจ้งทีมผ่าน Resend หากส่งไม่สำเร็จข้อความยังอยู่ และ admin กดส่งซ้ำได้ (กันส่งซ้ำด้วยสถานะ `sending`)
- `/admin`: login (throttle 5 ครั้ง/นาที), ภาพรวม, บริการ, ผลงาน + แกลเลอรี, ข้อความติดต่อ (สถานะ new/contacted/closed, ส่งอีเมลซ้ำ), ตั้งค่า (เฉพาะ key ใน `config/d51.php`)
- อัปโหลดรับเฉพาะ JPEG/PNG/WebP ตรวจ MIME ขนาดไฟล์และ pixel dimensions ตั้งชื่อสุ่ม เก็บที่ disk `public` (`storage/app/public/uploads`)
- ตั้ง `SITE_NOINDEX=true` บน staging เพื่อส่ง `X-Robots-Tag: noindex` ทุกหน้า หน้า admin เป็น noindex เสมอ

### ค่าบน server (.env)

`MAIL_MAILER=resend`, `RESEND_API_KEY`, `MAIL_FROM_ADDRESS` (domain ที่ verify กับ Resend แล้ว), `MAIL_FROM_NAME`, `INQUIRY_TO_EMAIL`, `DB_CONNECTION=mariadb` และค่า `DB_*`, `SESSION_SECURE_COOKIE=true` ดูขั้นตอน deploy ที่ [DEPLOYMENT_FTPS.md](docs/DEPLOYMENT_FTPS.md)

### สำหรับงาน frontend

View ใน `resources/views/{pages,services,projects,contact,layouts}` เป็นโครง HTML ที่ใช้ข้อมูลจริงแล้ว แทนที่ markup และ style ได้เลย แต่คงไว้: SEO head ใน `layouts/site.blade.php`, ป้าย `<x-concept-label />`, honeypot และชื่อ field ของฟอร์ม ข้อความเนื้อหาใช้ `$model->localized('title')` (fallback เป็นภาษาไทย) ค่าติดต่อใช้ `Setting::allValues()` และแสดงเฉพาะค่าที่มีจริง Brand tokens อยู่ใน `resources/css/app.css`

วันที่บรีฟ: 25 ตุลาคม 2026 (Asia/Bangkok)

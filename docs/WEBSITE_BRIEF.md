# D51 Construction — Website Development Brief

วันที่: 25 ตุลาคม 2026 | Domain: d51cons.com | Repository: upskilltrade/d51cons

## 1. เป้าหมาย

สร้างเว็บไซต์บริษัทรับก่อสร้างที่ดูน่าเชื่อถือ ประณีต และใช้งานเร็ว เปลี่ยนผู้สนใจให้ติดต่อปรึกษา/ขอประเมินงาน พร้อมให้เจ้าของจัดการบริการ ผลงาน และข้อความติดต่อได้เอง ไม่ทำเป็นระบบบริหารงานก่อสร้างหรือ ERP

กลุ่มลูกค้า: เจ้าของบ้าน เจ้าของพูลวิลล่า นักลงทุน และเจ้าของกิจการที่ต้องการก่อสร้างหรือปรับปรุงอาคาร ทั้งไทยและต่างชาติ พื้นที่ให้บริการจริงต้องยืนยันก่อนเผยแพร่ ห้ามเดาว่าครอบคลุมทุกจังหวัด

## 2. หลักการพัฒนา

- Professional through clarity, consistency, maintainability. ใช้ Laravel conventions, controllers, Form Requests, Eloquent และ Blade components
- Laravel monolith + MySQL ฐานข้อมูลเดียว ไม่ทำ microservices, separate REST backend, SPA, Next.js, Redis, Kubernetes หรือ realtime โดยไม่มีความจำเป็น
- Blade server-side rendering; Vite build CSS/JS; ใช้ CSS ที่จัดระเบียบเป็น tokens/components และ vanilla JS; Alpine ใช้เฉพาะเมื่อมีประโยชน์ ไม่ติดตั้งซ้ำกับ library อื่น
- Node.js สำหรับ npm/Vite build บนเครื่องพัฒนาหรือ CI เท่านั้น ไม่ต้อง Passenger/Node production process สำหรับเว็บ Laravel นี้
- เลือก Laravel stable ที่ยังรองรับ security และเข้ากับ PHP/extensions บน hosting ณ วันที่ลงมือ บันทึกเวอร์ชันจริงและ commit composer.lock/package-lock.json ไม่บังคับเลขเวอร์ชันที่ยังไม่ตรวจ
- ไม่ลง Docker, headless CMS, repository/service layers ทุก model หรือ package ใหญ่เพื่อ CRUD ง่าย ๆ
- ไม่มี login ลูกค้า จ่ายเงิน ระบบ BOQ สัญญา procurement บัญชี หรือ DevPropertyPro integration ใน MVP

## 3. ขอบเขตหน้าเว็บ

ภาษาไทยเป็น default (`/th`) และอังกฤษ (`/en`), `/` redirect ไป `/th` อย่างคงที่ ใช้ Laravel translation files สำหรับ UI; field เนื้อหาใน MySQL แยก `_th`/`_en` โดยตรง ไม่สร้าง translation engine

| หน้า | เนื้อหาและพฤติกรรม |
|---|---|
| Home | Hero → ความเชี่ยวชาญ → บริการ → ผลงาน/ภาพแนวคิด → ขั้นตอน → คุณภาพ → CTA |
| Services | รวมบริการ และรายละเอียดรายบริการพร้อม CTA |
| Projects | รวม/กรองประเภท ผลงานจริงที่ publish เท่านั้น; แยกส่วนภาพแนวคิดอย่างชัดเจน |
| Project detail | brief, scope, type, area/location เท่าที่เจ้าของอนุญาต, gallery, status, CTA |
| About | ตัวตน วิธีทำงาน ความสัมพันธ์กับ D51 Studio ข้อมูลทีมที่ยืนยันแล้ว |
| Contact | ฟอร์มติดต่อ ช่องทางจริง พื้นที่รับงาน; แผนที่เมื่อมีที่อยู่จริง |
| Privacy | แจ้งวัตถุประสงค์ ข้อมูลที่เก็บ ระยะเวลา ช่องทางติดต่อ และผู้ให้บริการอีเมล ตามการใช้งานจริง |

หากยังไม่มีผลงานจริง หน้า Projects แสดง empty state ที่สวยและคำเชิญปรึกษา ไม่สร้าง case study ปลอม สามารถแสดงส่วน Design Inspiration แยกพร้อม label ภาพแนวคิดทุกภาพ

## 4. Homepage art direction

อ้างอิง `docs/references/website-mockup.jpg` สำหรับ mood และลำดับหน้า ไม่คัดลอกภาพ screenshot มาทำเว็บทั้งหน้า

1. Header: โลโก้, บริการ, ผลงาน, ขั้นตอน, เกี่ยวกับเรา, TH/EN, ปุ่มปรึกษา; mobile มี menu ใช้ keyboard ได้
2. Hero: ภาพ villa เต็มความกว้าง ความสูง desktop ประมาณ 70–85vh; mobile ควบคุม crop ให้ยังเห็นอาคาร; headline ไทย “ใส่ใจทุกรายละเอียด ตั้งแต่แบบถึงงานสร้าง” / EN “Built with precision.”; CTA หลักปรึกษาโครงการ รองดูบริการ ไม่ทำ autoplay video
3. Intro: “จากสิ่งที่คุณคิด สู่พื้นที่ที่สร้างขึ้นอย่างใส่ใจ” และสามประเด็น ขอบเขตชัดเจน / ใส่ใจงานก่อสร้าง / ติดตามความคืบหน้า
4. Services: บ้านพักอาศัย, พูลวิลล่า, อาคารพาณิชย์, รีโนเวตและตกแต่ง — ยืนยันบริการก่อน publish
5. Projects: พื้นกราไฟต์ ภาพใหญ่ editorial grid; แสดงสถานะจริงและ label concept ถ้ามี
6. Process: ปรึกษา → กำหนดขอบเขตและเสนอราคา → ก่อสร้างและตรวจงาน → ส่งมอบ; ไม่ให้คำรับประกันระยะเวลาที่ไม่มีข้อมูล
7. Quality: ภาพตรวจงานและวิธีทำงาน ไม่ใช้ certification seals/ตัวเลขประสบการณ์ที่ไม่มีหลักฐาน
8. Contact CTA + footer: กรอกแบบฟอร์มหรือช่องทางจริง; ไม่แสดงปุ่ม LINE/WhatsApp/โทรที่ยังไม่มีค่า

Motion: subtle fade/translate 12–20px, 200–400ms, hover ภาพเล็กน้อย; ใช้ IntersectionObserver หากจำเป็นและเคารพ prefers-reduced-motion; หลีกเลี่ยง scroll hijack, loader บังหน้า, heavy parallax

## 5. UI/UX และคุณภาพ

- Responsive 360/390/768/1024/1440px; ไม่มี horizontal overflow; max-width ประมาณ 1200–1280px และ spacing scale 8px
- Typography ไทยอ่านง่าย, body 16–18px, line-height 1.6–1.8; ปุ่มแตะง่าย มี focus state และ contrast ที่อ่านได้; HTML semantic, labels, alt, keyboard navigation
- รูปใช้ WebP/AVIF เมื่อเหมาะสม, srcset/sizes, width/height ป้องกัน layout shift; hero ไม่ lazy load, ภาพล่าง lazy load; ไม่โหลดทุก gallery ตั้งแต่แรก
- เป้าหมาย LCP <=2.5s, CLS <=0.1, INP <=200ms ภายใต้เงื่อนไขวัดที่บันทึก; prelaunch ใช้ Lighthouse เป็นตัวชี้วัดห้องทดสอบ ไม่อ้างว่ามี field INP แล้ว
- SEO: title/description ต่อภาษา, canonical, hreflang th/en, sitemap ของ published pages, robots, OG image; schema Organization/Service ตามข้อมูลจริง ห้าม fake reviews
- Staging ต้องจำกัดการเข้าถึงและ noindex; production มี HTTPS และ redirect canonical host

## 6. ฟอร์มติดต่อและ Resend

Fields: name(required), email(required), phone(optional), service type, location, budget range(optional), message(required), privacy acknowledgement(required), locale. ไม่รับไฟล์ใน MVP เพื่อลดภาระและความเสี่ยง

Flow: validate ฝั่ง server → CSRF + honeypot + rate limit → บันทึก inquiry ใน MySQL → ส่งแจ้งเตือนทีมผ่าน Resend → แสดงผลรับเรื่อง หากส่งเมลไม่ได้ inquiry ต้องยังอยู่และ user ไม่ต้องส่งซ้ำ บันทึกสถานะ email_failed ให้ admin retry ได้; สำเร็จของ provider หมายถึง accepted ไม่ใช่ delivered

ใช้ Laravel Mailable ผ่าน Resend transport ที่รองรับในเวอร์ชันที่เลือก; ติดตั้งเฉพาะ dependency ที่เอกสารเวอร์ชันนั้นต้องใช้ ไม่ติดตั้งทั้ง SDK และ transport ซ้ำโดยไม่จำเป็น ตั้งค่า MAIL_MAILER, RESEND_API_KEY, MAIL_FROM_ADDRESS, MAIL_FROM_NAME และ INQUIRY_TO_EMAIL ผ่าน server environment

From เป็น address ของ domain ที่ verify แล้ว; email ผู้ติดต่อเป็น Reply-To เท่านั้น ห้ามให้ browser เรียก Resend API หรือส่ง API key ไป frontend. จำกัดความยาวข้อมูล escape output, ไม่เอา raw input ใส่ mail header. ใช้ timeout ที่เหมาะสมและไม่ส่งข้อมูลส่วนบุคคลลง log

MVP ส่งแบบ synchronous หลัง persist พร้อม error handling; เมื่อปริมาณมากขึ้นค่อยใช้ database queue และ cron/worker ที่ hosting รองรับ ไม่ต้อง Redis. Retry ต้องป้องกันส่งซ้ำด้วยสถานะและ provider idempotency ตามที่ integration รองรับ. ไม่มี auto-reply ให้ผู้กรอกใน MVP เพื่อลดการใช้ฟอร์มยิงเมลไปบุคคลอื่น

## 7. Admin แบบเล็กแต่ครบ

`/admin` login สำหรับผู้ดูแล บัญชีสร้างผ่าน CLI/seed ที่ปลอดภัย ไม่มี public registration หรือ default password ใน repo. ใช้ session auth, password hashing, login throttling และ authorization ทุก admin route

- Dashboard เรียบง่าย: inquiries ใหม่/งานที่ publish
- Services CRUD: ข้อความ TH/EN, ภาพ, ลำดับ, publish
- Projects CRUD: title/summary TH/EN, category, location, status, is_concept, cover/gallery, publish
- Inquiries: รายการ/รายละเอียด, new/contacted/closed, email status, manual resend
- Settings: ข้อมูลติดต่อ โซเชียล พื้นที่บริการ ข้อความเกี่ยวกับเรา ค่า SEO; จำกัด key ตามที่ระบบใช้ ไม่ทำ page builder
- Media: upload เฉพาะ JPEG/PNG/WebP, ตรวจ MIME/ขนาดและ pixel dimensions, ชื่อสุ่ม, ห้าม SVG/ไฟล์ executable จากฟอร์ม; เก็บผ่าน Laravel filesystem

ใช้ Blade admin ธรรมดาเป็น default ไม่ต้องสร้าง CMS ใหม่หรือเพิ่ม dashboard package เพื่อไม่กี่หน้า

## 8. Data model ขั้นต้น

| Table | Fields สำคัญ |
|---|---|
| users | name, email(unique), password, timestamps; admin-only MVP |
| services | slug(unique), title_th/en, summary_th/en, body_th/en, image_path, sort_order, is_published |
| projects | slug(unique), service_id(nullable), title_th/en, summary_th/en, body_th/en, location, area_sqm(nullable), status, is_concept, cover_path, sort_order, published_at(nullable) |
| project_images | project_id, path, alt_th/en, caption_th/en, sort_order |
| inquiries | name, email, phone, service_type, location, budget_range, message, locale, privacy_acknowledged_at, status, mail_status, provider_message_id(nullable), notified_at(nullable), timestamps |
| settings | key(unique), value; allowlisted keys only |

MySQL utf8mb4, migrations + indexes ตาม query จริง, foreign keys, eager loading gallery; ไม่ต้อง JSON multilingual schema หรือ universal metadata architecture. Rich text ถ้าใช้ต้อง sanitize allowlist; MVP plain text/paragraphs เพียงพอ

## 9. ขอบเขตการส่งมอบและ acceptance

1. README มีคำสั่งติดตั้ง PHP/Composer/npm/MySQL, .env.example ไม่มี secrets, migrate/seed/build/test และวิธีสร้าง admin
2. TH/EN navigation, detail pages, 404 และ published-only visibility ใช้งานได้จริง ทั้งมือถือและ desktop
3. Admin แก้ข้อมูลได้ และ unauthorized access ถูกปฏิเสธ; test login protection, validation, concept labeling และ unpublished visibility
4. Form valid ถูก persist และส่ง notification; provider failure ไม่ทำข้อมูลหาย; invalid/CSRF/rate-limit ป้องกันได้; test โดย mock mail provider ไม่ส่งเมลจริงใน CI
5. รูปไม่มี 404, alt/crop เหมาะสม, logo ไม่ยืด, contact links มีปลายทางจริงเท่านั้น
6. CI รัน tests, build assets และ dependency checks; deploy workflow ต้องไม่เริ่มจริงจน hosting paths/secrets/setup พร้อม
7. README deployment ระบุ FTPS, migration, persistent uploads, cache, backup, rollback และข้อจำกัด ไม่อ้างว่า FTPS รันคำสั่ง server ได้
8. ตรวจภาพหน้าเว็บจริง desktop/mobile ก่อนส่ง ไม่ทดสอบ implementation รายละเอียดปลีกย่อยที่ไม่ช่วยจับ regression

## 10. ลำดับลงมือ

1. ตรวจ PHP/extensions/MySQL/Composer/CLI/docroot/FTPS และเลือก versions
2. Scaffold Laravel + tokens + header/footer + Home ให้ใกล้ mockup
3. Services/Projects/TH-EN + seed ภาพแนวคิดแยกชัด
4. Contact persistence + Resend + admin CRUD
5. SEO/accessibility/performance + meaningful tests
6. Build artifact และทดลอง FTPS บน staging; setup server/DB แล้วจึง production

สิ่งที่รอข้อมูลจริง: ชื่อนิติบุคคล/เลขทะเบียน, ที่อยู่, เบอร์โทร, LINE/WhatsApp, พื้นที่บริการ, ทีมงาน, ผลงานจริง, FTPS host/port/path, PHP และ Laravel ที่ hosting รองรับ, ความสามารถ CLI/cron, Resend verified sender. ใช้ TODO ใน admin/doc; ซ่อนส่วนที่ยังไม่พร้อมบน public page

## Technical references

- https://laravel.com/docs/deployment
- https://laravel.com/docs/vite
- https://laravel.com/docs/mail
- https://resend.com/docs/send-with-laravel

ตรวจเอกสารตรงกับเวอร์ชันที่เลือกอีกครั้งเมื่อเริ่ม coding

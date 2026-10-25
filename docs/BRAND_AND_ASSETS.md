# Brand & Asset Guide — D51 Construction

## Identity

Brand: D51 Construction / ดีไฟว์วัน คอนสตรัคชั่น. Website: d51cons.com.
Concept: Modern Precision — แข็งแรง ประณีต เป็นระบบ.
TH: ใส่ใจทุกรายละเอียด ตั้งแต่แบบถึงงานสร้าง.
EN: Built with precision.

| Token | HEX | Usage |
|---|---|---|
| graphite | #25282A | headings, dark sections, footer |
| warm-white | #F5F2EB | primary background |
| brick | #B65C3A | CTA, accents; approximately 10% |
| concrete | #A5A7A3 | decorative lines/surfaces, not small body text on white |

Use Manrope for English headings, Noto Sans Thai for Thai/body. Verify distribution license, keep license notice when self-hosting, and avoid excess weights. Body text should primarily be graphite. Check contrast of each actual button/text pair rather than assuming all palette combinations pass.

Logo direction: bold architectural D51 with small brick square at top-left and CONSTRUCTION below. References are raster concepts, not vector master artwork. Recreate as a proper approved SVG before production; do not trace the whole brand board into a logo or claim a screenshot is a master. Keep clear space at least the brick square height on each side. Do not distort, add shadow/gradient, or substitute another typeface in the logo. Small avatars use D51 only.

## Reference files

- `references/ci-board.jpg`: proposed identity system
- `references/brand-applications.jpg`: mockups for signage, uniform, vehicle and stationery
- `references/website-mockup.jpg`: desktop homepage visual direction

Reference screenshots are for design guidance only; don't put them in a public website page as functional UI. They may contain approximate lettering; exact HEX values and text in this document take precedence.

## Website image assets

All files below are new AI-generated fictional concepts, not completed work or real employees. No external client/photographer attribution is implied. Use a visible label “ภาพแนวคิดประกอบ / Illustrative concept” close to rendered scenes, particularly in project/inspiration sections; write accurate alt text, not marketing keywords. Replace with authorized actual site/project photos as available.

| File under public/images/concepts | Intended use | Suggested alt TH | Crop |
|---|---|---|---|
| hero-pool-villa.webp | Hero, villa service | ภาพแนวคิดพูลวิลล่าสไตล์โมเดิร์นทรอปิคอล | source 3:2; desktop 16:9/21:9 with dark text-safe left; mobile tune object-position |
| commercial-building.webp | Commercial service | ภาพแนวคิดอาคารพาณิชย์ร่วมสมัย | 3:2 or 4:3 |
| construction-quality.webp | Process and quality | ภาพจำลองการตรวจวัดงานก่อสร้าง | 3:2 or 4:3 |

Source prompts: `IMAGE_PROMPTS.md`. Generated without logos/text; apply real HTML text above images. Never bake CTA or headings into photographs. Do not present the illustrative worker as a real company team member. Other service cards can reuse suitable crops temporarily; avoid unrelated stock filler.

Optimize production variants (480/768/1280/1920 as appropriate, never upscale beyond source), preserve dimensions, use picture/srcset and lazy-loading below fold. Keep only public-sized variants in runtime; original references stay in docs. Choose text-safe crops per screen size and test a real 390px view.

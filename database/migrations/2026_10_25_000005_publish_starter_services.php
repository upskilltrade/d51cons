<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * The owner confirmed the four starter services on 2026-10-25. Publish them and
 * fill detail text only where it is still empty, so admin edits are never overwritten.
 */
return new class extends Migration
{
    private const BODIES = [
        'homes' => [
            'th' => "รับก่อสร้างบ้านพักอาศัยสไตล์โมเดิร์น ตั้งแต่การทำความเข้าใจความต้องการ การกำหนดขอบเขตงานและใบเสนอราคา ไปจนถึงงานก่อสร้างและส่งมอบ\n\nเราวางแผนงานแต่ละขั้นให้ชัดเจน ตรวจงานตามขั้นตอนหน้างาน และรายงานความคืบหน้าให้เจ้าของบ้านทราบอย่างสม่ำเสมอ",
            'en' => "Construction of modern private homes, from understanding your needs and agreeing the scope and quotation to building and handover.\n\nEach stage is planned clearly, inspected on site and reported to the owner regularly.",
        ],
        'pool-villas' => [
            'th' => "ก่อสร้างพูลวิลล่าและบ้านพักพร้อมสระว่ายน้ำ ที่ออกแบบสำหรับสภาพอากาศเขตร้อน ทั้งเพื่ออยู่อาศัยเองและเพื่อการลงทุน\n\nให้ความสำคัญกับโครงสร้างสระ ระบบกันซึม ระบบน้ำ และงานรายละเอียดรอบสระ เพื่อการใช้งานที่ดีในระยะยาว",
            'en' => "Construction of pool villas and homes with pools designed for the tropical climate, for your own use or as an investment.\n\nWe pay close attention to pool structure, waterproofing, water systems and the finishing around the pool for long-term use.",
        ],
        'commercial' => [
            'th' => "ก่อสร้างอาคารพาณิชย์และพื้นที่สำหรับธุรกิจ เช่น อาคารสำนักงาน ร้านค้า และอาคารใช้งานผสม\n\nวางแผนงานให้สอดคล้องกับการใช้งานจริงของธุรกิจ งบประมาณ และกำหนดเวลาที่ตกลงร่วมกัน",
            'en' => "Construction of commercial buildings and business spaces such as offices, shops and mixed-use buildings.\n\nWork is planned around how the business will actually use the space, the budget and the agreed schedule.",
        ],
        'renovation' => [
            'th' => "ปรับปรุง ต่อเติม และตกแต่งพื้นที่เดิม ทั้งบ้านพักอาศัยและพื้นที่เชิงพาณิชย์\n\nเริ่มจากสำรวจสภาพพื้นที่จริง กำหนดขอบเขตงานให้ชัดเจน แล้วจึงลงมือปรับปรุงอย่างประณีต",
            'en' => "Renovation, extension and fit-out of existing homes and commercial spaces.\n\nWe start by surveying the existing space, agree a clear scope, then carry out the work with care.",
        ],
    ];

    public function up(): void
    {
        foreach (self::BODIES as $slug => $body) {
            $service = DB::table('services')->where('slug', $slug)->first();

            if (! $service) {
                continue;
            }

            DB::table('services')->where('id', $service->id)->update(array_filter([
                'is_published' => true,
                'body_th' => blank($service->body_th) ? $body['th'] : null,
                'body_en' => blank($service->body_en) ? $body['en'] : null,
                'updated_at' => now(),
            ], fn ($value) => $value !== null));
        }
    }

    public function down(): void
    {
        // Content stays; publishing is managed in admin from here on.
    }
};

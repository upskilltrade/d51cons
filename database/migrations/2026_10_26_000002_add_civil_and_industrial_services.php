<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * The owner added seven more service lines on 2026-10-26, each with an AI concept image,
 * plus two concept projects. Fresh installs get the same rows from the seeder, so this only
 * extends a site that already has the starter services. Existing rows are never changed.
 */
return new class extends Migration
{
    private const SERVICES = [
        ['housing-developments', 5, 'โครงการบ้านจัดสรร', 'Housing Developments',
            'ก่อสร้างบ้านหลายหลังในโครงการ พร้อมถนน รั้ว และพื้นที่ส่วนกลาง', 'Multi-home estates with roads, gates and shared areas.',
            "รับก่อสร้างบ้านในโครงการบ้านจัดสรร ตั้งแต่ตัวบ้าน ถนนภายในโครงการ ซุ้มทางเข้า รั้ว ไปจนถึงพื้นที่ส่วนกลาง\n\nวางแผนการก่อสร้างเป็นเฟสให้สอดคล้องกับการขายของโครงการ และควบคุมมาตรฐานงานให้เท่ากันทุกหลัง",
            "Construction of homes in housing estates, from the houses themselves to estate roads, entrance gates, fencing and shared areas.\n\nWork is phased to match the project's sales plan, with the same standard of work on every home.",
            'images/concepts/service-housing-development.webp'],
        ['warehouses', 6, 'โกดังและโรงงาน', 'Warehouses & Factories',
            'อาคารโครงสร้างเหล็กสำหรับเก็บสินค้า โลจิสติกส์ และอุตสาหกรรม', 'Steel-frame buildings for storage, logistics and industry.',
            "ก่อสร้างโกดัง คลังสินค้า และอาคารโรงงาน ทั้งงานฐานราก พื้นคอนกรีตรับน้ำหนัก โครงสร้างเหล็ก หลังคา และผนัง\n\nออกแบบลำดับงานให้พื้นที่พร้อมใช้งานตามกำหนด และรองรับการขยายในอนาคต",
            "Construction of warehouses, distribution centres and factory buildings: foundations, heavy-duty floor slabs, steel frames, roofing and cladding.\n\nWork is sequenced so the space is ready on schedule and can be extended later.",
            'images/concepts/service-warehouse.webp'],
        ['concrete-works', 7, 'งานพื้นและลานคอนกรีต', 'Concrete Slabs & Yards',
            'พื้นคอนกรีต ลานจอดรถ และลานหน้าโกดัง ผิวเรียบได้ระดับ', 'Floor slabs, car parks and yards finished level and true.',
            "เทพื้นคอนกรีตและลานคอนกรีต เช่น ลานจอดรถ ลานหน้าโกดัง และพื้นอาคาร พร้อมขัดมันผิวหน้า\n\nควบคุมการบดอัดชั้นรองพื้น ระดับ ความหนา และรอยต่อ เพื่อให้พื้นแข็งแรงและใช้งานได้นาน",
            "Concrete floor slabs and yards such as car parks, warehouse forecourts and building floors, with power-trowelled finishes.\n\nWe control sub-base compaction, levels, thickness and joints so the slab stays strong for years.",
            'images/concepts/service-concrete.webp'],
        ['roads', 8, 'งานถนน', 'Road Construction',
            'ถนนคอนกรีตและถนนภายในโครงการ พร้อมงานชั้นพื้นทาง', 'Concrete and estate roads, including the layers beneath.',
            "ก่อสร้างถนนคอนกรีตและถนนภายในโครงการ ตั้งแต่ปรับระดับ ชั้นรองพื้นทาง ชั้นพื้นทาง ไปจนถึงผิวถนน ขอบทาง และทางเท้า\n\nวางแนวระบายน้ำร่วมกับงานถนน เพื่อไม่ให้น้ำขังและยืดอายุการใช้งาน",
            "Concrete roads and estate roads, from grading, sub-base and base layers to the road surface, kerbs and footpaths.\n\nDrainage is planned together with the road so water runs off and the road lasts longer.",
            'images/concepts/service-roads.webp'],
        ['drainage', 9, 'งานระบบระบายน้ำ', 'Drainage & Utilities',
            'วางท่อระบายน้ำ บ่อพัก และงานระบบใต้ดิน', 'Drainage pipes, manholes and underground utilities.',
            "วางท่อระบายน้ำคอนกรีต บ่อพัก รางระบายน้ำ และงานระบบใต้ดินสำหรับโครงการและพื้นที่ก่อสร้าง\n\nตรวจแนวและระดับท้องท่อก่อนกลบ เพื่อให้น้ำไหลได้ดีตามที่ออกแบบ",
            "Concrete drainage pipes, manholes, channels and underground utility works for projects and building sites.\n\nPipe lines and invert levels are checked before backfilling so water flows as designed.",
            'images/concepts/service-drainage.webp'],
        ['earthworks', 10, 'งานดินและปรับพื้นที่', 'Earthworks & Site Preparation',
            'ขุด ถม บดอัด และปรับระดับพื้นที่ก่อนเริ่มก่อสร้าง', 'Excavation, filling, compaction and grading before building.',
            "งานขุด ถมดิน บดอัด และปรับระดับพื้นที่ เพื่อเตรียมพื้นที่ให้พร้อมสำหรับงานก่อสร้าง\n\nใช้เครื่องจักรที่เหมาะกับหน้างาน และตรวจระดับพื้นที่ตามแบบก่อนส่งต่องานโครงสร้าง",
            "Excavation, filling, compaction and grading to get the site ready for construction.\n\nWe use machinery suited to the site and check levels against the drawings before structural work begins.",
            'images/concepts/service-earthworks.webp'],
        ['sports-courts', 11, 'สนามกีฬา', 'Sports Courts',
            'สนามบาสเกตบอลและสนามอเนกประสงค์ พร้อมรั้วและไฟส่องสว่าง', 'Basketball and multi-use courts with fencing and lighting.',
            "ก่อสร้างสนามบาสเกตบอล สนามอเนกประสงค์ และพื้นที่ออกกำลังกาย ทั้งพื้นคอนกรีต ผิวสนาม รั้ว และไฟส่องสว่าง\n\nเหมาะสำหรับโครงการหมู่บ้าน โรงเรียน และพื้นที่ส่วนกลาง",
            "Basketball courts, multi-use courts and exercise areas, including the concrete base, court surface, fencing and lighting.\n\nSuited to housing estates, schools and shared community areas.",
            'images/concepts/service-sports-court.webp'],
    ];

    private const CONCEPTS = [
        ['concept-estate-gateway', 'housing-developments', 'ซุ้มทางเข้าโครงการบ้านจัดสรร', 'Housing Estate Entrance Gate', 'images/concepts/concept-estate-gateway.webp', 5],
        ['concept-warehouse-forecourt', 'concrete-works', 'ลานคอนกรีตหน้าโกดัง', 'Concrete Warehouse Forecourt', 'images/concepts/concept-warehouse-forecourt.webp', 6],
    ];

    public function up(): void
    {
        if (! DB::table('services')->where('slug', 'homes')->exists()) {
            return;
        }

        foreach (self::SERVICES as [$slug, $order, $th, $en, $summaryTh, $summaryEn, $bodyTh, $bodyEn, $image]) {
            if (DB::table('services')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('services')->insert([
                'slug' => $slug,
                'title_th' => $th,
                'title_en' => $en,
                'summary_th' => $summaryTh,
                'summary_en' => $summaryEn,
                'body_th' => $bodyTh,
                'body_en' => $bodyEn,
                'image_path' => $image,
                'sort_order' => $order,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! DB::table('projects')->where('slug', 'concept-modern-pool-villa')->exists()) {
            return;
        }

        foreach (self::CONCEPTS as [$slug, $serviceSlug, $th, $en, $cover, $order]) {
            if (DB::table('projects')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('projects')->insert([
                'slug' => $slug,
                'service_id' => DB::table('services')->where('slug', $serviceSlug)->value('id'),
                'title_th' => $th,
                'title_en' => $en,
                'summary_th' => 'ภาพแนวคิดประกอบ ไม่ใช่ผลงานก่อสร้างจริงของบริษัท',
                'summary_en' => 'Illustrative concept, not a completed company project.',
                'status' => 'planning',
                'is_concept' => true,
                'cover_path' => $cover,
                'sort_order' => $order,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // The generic inspection image moves to the end, if it is still where it was put.
        DB::table('projects')->where('slug', 'concept-quality-inspection')->where('sort_order', 5)->update(['sort_order' => 7]);
    }

    public function down(): void
    {
        DB::table('projects')->whereIn('slug', array_column(self::CONCEPTS, 0))->delete();
        DB::table('services')->whereIn('slug', array_column(self::SERVICES, 0))->delete();
    }
};

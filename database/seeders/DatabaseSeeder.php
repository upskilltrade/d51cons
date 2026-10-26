<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Starter content. Safe to run more than once.
     *
     * The owner confirmed the four services on 2026-10-25 and seven more on 2026-10-26, so they start published. Concept projects
     * use the AI-generated images and are always flagged is_concept.
     * No admin account is seeded: use `php artisan admin:create`.
     */
    public function run(): void
    {
        $services = [
            ['homes', 'บ้านพักอาศัย', 'Custom Homes', 'บ้านโมเดิร์นที่ใช้งานได้จริง ออกแบบตามไลฟ์สไตล์ของคุณ', 'Modern, functional homes built around your lifestyle.', 'images/concepts/service-custom-home.webp'],
            ['pool-villas', 'พูลวิลล่า', 'Pool Villas', 'พื้นที่พักอาศัยพร้อมสระว่ายน้ำ สำหรับชีวิตในเขตร้อน', 'Living spaces with pools, designed for tropical life.', 'images/concepts/service-pool-villa.webp'],
            ['commercial', 'อาคารพาณิชย์', 'Commercial Spaces', 'อาคารสำหรับธุรกิจที่ใช้งานได้ดีและมีมูลค่าระยะยาว', 'Professional spaces for business and long-term value.', 'images/concepts/commercial-building.webp'],
            ['renovation', 'รีโนเวตและตกแต่ง', 'Renovation & Fit-out', 'ปรับปรุงและยกระดับพื้นที่เดิมอย่างประณีต', 'Transform and upgrade existing spaces with precision.', 'images/concepts/service-renovation.webp'],
            ['housing-developments', 'โครงการบ้านจัดสรร', 'Housing Developments', 'ก่อสร้างบ้านหลายหลังในโครงการ พร้อมถนน รั้ว และพื้นที่ส่วนกลาง', 'Multi-home estates with roads, gates and shared areas.', 'images/concepts/service-housing-development.webp'],
            ['warehouses', 'โกดังและโรงงาน', 'Warehouses & Factories', 'อาคารโครงสร้างเหล็กสำหรับเก็บสินค้า โลจิสติกส์ และอุตสาหกรรม', 'Steel-frame buildings for storage, logistics and industry.', 'images/concepts/service-warehouse.webp'],
            ['concrete-works', 'งานพื้นและลานคอนกรีต', 'Concrete Slabs & Yards', 'พื้นคอนกรีต ลานจอดรถ และลานหน้าโกดัง ผิวเรียบได้ระดับ', 'Floor slabs, car parks and yards finished level and true.', 'images/concepts/service-concrete.webp'],
            ['roads', 'งานถนน', 'Road Construction', 'ถนนคอนกรีตและถนนภายในโครงการ พร้อมงานชั้นพื้นทาง', 'Concrete and estate roads, including the layers beneath.', 'images/concepts/service-roads.webp'],
            ['drainage', 'งานระบบระบายน้ำ', 'Drainage & Utilities', 'วางท่อระบายน้ำ บ่อพัก และงานระบบใต้ดิน', 'Drainage pipes, manholes and underground utilities.', 'images/concepts/service-drainage.webp'],
            ['earthworks', 'งานดินและปรับพื้นที่', 'Earthworks & Site Preparation', 'ขุด ถม บดอัด และปรับระดับพื้นที่ก่อนเริ่มก่อสร้าง', 'Excavation, filling, compaction and grading before building.', 'images/concepts/service-earthworks.webp'],
            ['sports-courts', 'สนามกีฬา', 'Sports Courts', 'สนามบาสเกตบอลและสนามอเนกประสงค์ พร้อมรั้วและไฟส่องสว่าง', 'Basketball and multi-use courts with fencing and lighting.', 'images/concepts/service-sports-court.webp'],
        ];

        foreach ($services as $order => [$slug, $th, $en, $summaryTh, $summaryEn, $image]) {
            Service::query()->firstOrCreate(['slug' => $slug], [
                'title_th' => $th,
                'title_en' => $en,
                'summary_th' => $summaryTh,
                'summary_en' => $summaryEn,
                'image_path' => $image,
                'sort_order' => $order + 1,
                'is_published' => true,
            ]);
        }

        $concepts = [
            ['concept-modern-pool-villa', 'pool-villas', 'พูลวิลล่าโมเดิร์นทรอปิคอล', 'Modern Tropical Pool Villa', 'images/concepts/hero-pool-villa.webp'],
            ['concept-modern-tropical-home', 'homes', 'บ้านโมเดิร์นระแนงไม้สัก', 'Modern Home with Teak Screens', 'images/concepts/service-custom-home.webp'],
            ['concept-commercial-building', 'commercial', 'อาคารพาณิชย์ร่วมสมัย', 'Contemporary Commercial Building', 'images/concepts/commercial-building.webp'],
            ['concept-tropical-living-renovation', 'renovation', 'ห้องนั่งเล่นรีโนเวตสไตล์ทรอปิคอล', 'Tropical Living Room Renovation', 'images/concepts/service-renovation.webp'],
            ['concept-estate-gateway', 'housing-developments', 'ซุ้มทางเข้าโครงการบ้านจัดสรร', 'Housing Estate Entrance Gate', 'images/concepts/concept-estate-gateway.webp'],
            ['concept-warehouse-forecourt', 'concrete-works', 'ลานคอนกรีตหน้าโกดัง', 'Concrete Warehouse Forecourt', 'images/concepts/concept-warehouse-forecourt.webp'],
            ['concept-quality-inspection', null, 'การตรวจวัดคุณภาพงานก่อสร้าง', 'Construction Quality Inspection', 'images/concepts/construction-quality.webp'],
        ];

        foreach ($concepts as $order => [$slug, $serviceSlug, $th, $en, $cover]) {
            Project::query()->firstOrCreate(['slug' => $slug], [
                'service_id' => $serviceSlug ? Service::query()->where('slug', $serviceSlug)->value('id') : null,
                'title_th' => $th,
                'title_en' => $en,
                'summary_th' => 'ภาพแนวคิดประกอบ ไม่ใช่ผลงานก่อสร้างจริงของบริษัท',
                'summary_en' => 'Illustrative concept, not a completed company project.',
                'status' => ProjectStatus::Planning,
                'is_concept' => true,
                'cover_path' => $cover,
                'sort_order' => $order + 1,
                'published_at' => now(),
            ]);
        }
    }
}

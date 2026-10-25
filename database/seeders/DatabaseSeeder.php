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
     * The owner confirmed the four services on 2026-10-25, so they start published. Concept projects
     * use the AI-generated images and are always flagged is_concept.
     * No admin account is seeded: use `php artisan admin:create`.
     */
    public function run(): void
    {
        $services = [
            ['homes', 'บ้านพักอาศัย', 'Custom Homes', 'บ้านโมเดิร์นที่ใช้งานได้จริง ออกแบบตามไลฟ์สไตล์ของคุณ', 'Modern, functional homes built around your lifestyle.', null],
            ['pool-villas', 'พูลวิลล่า', 'Pool Villas', 'พื้นที่พักอาศัยพร้อมสระว่ายน้ำ สำหรับชีวิตในเขตร้อน', 'Living spaces with pools, designed for tropical life.', 'images/concepts/hero-pool-villa.webp'],
            ['commercial', 'อาคารพาณิชย์', 'Commercial Spaces', 'อาคารสำหรับธุรกิจที่ใช้งานได้ดีและมีมูลค่าระยะยาว', 'Professional spaces for business and long-term value.', 'images/concepts/commercial-building.webp'],
            ['renovation', 'รีโนเวตและตกแต่ง', 'Renovation & Fit-out', 'ปรับปรุงและยกระดับพื้นที่เดิมอย่างประณีต', 'Transform and upgrade existing spaces with precision.', null],
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
            ['concept-commercial-building', 'commercial', 'อาคารพาณิชย์ร่วมสมัย', 'Contemporary Commercial Building', 'images/concepts/commercial-building.webp'],
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

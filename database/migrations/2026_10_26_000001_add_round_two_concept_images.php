<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/*
 * Second set of AI concept images (2026-10-26). Services get their own image
 * when they still have none or the shared hero; two concept projects are added
 * to the labeled inspiration section. Anything changed in admin is left alone.
 */
return new class extends Migration
{
    private const SERVICE_IMAGES = [
        'homes' => [null, 'images/concepts/service-custom-home.webp'],
        'pool-villas' => ['images/concepts/hero-pool-villa.webp', 'images/concepts/service-pool-villa.webp'],
        'renovation' => [null, 'images/concepts/service-renovation.webp'],
    ];

    private const CONCEPTS = [
        ['concept-modern-tropical-home', 'homes', 'บ้านโมเดิร์นระแนงไม้สัก', 'Modern Home with Teak Screens', 'images/concepts/service-custom-home.webp', 2],
        ['concept-tropical-living-renovation', 'renovation', 'ห้องนั่งเล่นรีโนเวตสไตล์ทรอปิคอล', 'Tropical Living Room Renovation', 'images/concepts/service-renovation.webp', 4],
    ];

    public function up(): void
    {
        foreach (self::SERVICE_IMAGES as $slug => [$from, $to]) {
            DB::table('services')->where('slug', $slug)
                ->where(fn ($query) => $from ? $query->where('image_path', $from) : $query->whereNull('image_path'))
                ->update(['image_path' => $to, 'updated_at' => now()]);
        }

        // Fresh installs get these from the seeder; only extend a site that has the seeded concepts.
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

        // Keep the seeded concepts in a clear order around the new ones.
        foreach (['concept-commercial-building' => [2, 3], 'concept-quality-inspection' => [3, 5]] as $slug => [$seeded, $order]) {
            DB::table('projects')->where('slug', $slug)->where('sort_order', $seeded)->update(['sort_order' => $order]);
        }
    }

    public function down(): void
    {
        DB::table('projects')->whereIn('slug', array_column(self::CONCEPTS, 0))->delete();
    }
};

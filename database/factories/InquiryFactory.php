<?php

namespace Database\Factories;

use App\Enums\InquiryStatus;
use App\Enums\MailStatus;
use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'message' => fake()->paragraph(),
            'locale' => 'th',
            'privacy_acknowledged_at' => now(),
            'status' => InquiryStatus::New,
            'mail_status' => MailStatus::Sent,
        ];
    }

    public function mailFailed(): static
    {
        return $this->state(['mail_status' => MailStatus::Failed]);
    }
}

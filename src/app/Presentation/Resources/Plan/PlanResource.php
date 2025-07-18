<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Plan;

use App\Domain\Plan\Entities\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function __construct(private Plan $plan)
    {
        parent::__construct($plan);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->plan->getId()->value(),
            'name' => $this->plan->getName()->value,
            'monthly_price' => [
                'amount' => $this->plan->getMonthlyPrice()->amount,
                'currency' => $this->plan->getMonthlyPrice()->currency,
            ],
            'user_limit' => $this->plan->getUserLimit()->value,
            'features' => $this->plan->getFeatures()->toArray(),
            'created_at' => $this->plan->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->plan->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}
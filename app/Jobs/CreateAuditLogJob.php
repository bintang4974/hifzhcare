<?php

namespace App\Jobs;

use App\Models\AuditLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateAuditLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected ?int $pesantrenId,
        protected ?int $userId,
        protected string $event,
        protected string $auditableType,
        protected int $auditableId,
        protected ?array $oldValues = null,
        protected ?array $newValues = null,
        protected ?string $ipAddress = null,
        protected ?string $userAgent = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            AuditLog::create([
                'pesantren_id' => $this->pesantrenId,
                'user_id' => $this->userId,
                'event' => $this->event,
                'auditable_type' => $this->auditableType,
                'auditable_id' => $this->auditableId,
                'old_values_json' => $this->oldValues ? json_encode($this->oldValues) : null,
                'new_values_json' => $this->newValues ? json_encode($this->newValues) : null,
                'ip_address' => $this->ipAddress,
                'user_agent' => $this->userAgent,
            ]);

            Log::info("Audit log created successfully", [
                'event' => $this->event,
                'model' => $this->auditableType,
                'model_id' => $this->auditableId,
                'user_id' => $this->userId,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to create audit log", [
                'event' => $this->event,
                'model' => $this->auditableType,
                'model_id' => $this->auditableId,
                'error' => $e->getMessage(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Audit log job failed permanently", [
            'event' => $this->event,
            'model' => $this->auditableType,
            'model_id' => $this->auditableId,
            'error' => $exception->getMessage(),
        ]);
    }
}

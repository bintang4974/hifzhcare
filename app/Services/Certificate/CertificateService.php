<?php

namespace App\Services\Certificate;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Jobs\Certificate\GenerateCertificatePdfJob;
use Illuminate\Support\Facades\DB;

class CertificateService
{
    /**
     * Request certificate (for santri).
     */
    public function requestCertificate(int $userId, int $juzCompleted): Certificate
    {
        DB::beginTransaction();

        try {
            $user = \App\Models\User::findOrFail($userId);

            // Get template
            $template = CertificateTemplate::where('pesantren_id', $user->pesantren_id)
                ->where('type', 'santri_juz')
                ->where('status', 'active')
                ->first();

            if (!$template) {
                throw new \Exception('Template sertifikat tidak ditemukan.');
            }

            // Generate certificate number
            $certificateNumber = $this->generateCertificateNumber($user->pesantren_id);

            // Create certificate
            $certificate = Certificate::create([
                'pesantren_id' => $user->pesantren_id,
                'certificate_template_id' => $template->id,
                'user_id' => $userId,
                'certificate_number' => $certificateNumber,
                'type' => 'santri_juz',
                'juz_completed' => $juzCompleted,
                'status' => 'pending', // Requires approval
            ]);

            DB::commit();

            // TODO: Notify ustadz for approval

            return $certificate;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve certificate.
     */
    public function approveCertificate(int $certificateId, int $ustadzId): Certificate
    {
        DB::beginTransaction();

        try {
            $certificate = Certificate::findOrFail($certificateId);

            $certificate->update([
                'status' => 'approved',
                'approved_by_ustadz_id' => $ustadzId,
                'approved_at' => now(),
            ]);

            // Dispatch job to generate PDF
            GenerateCertificatePdfJob::dispatch($certificate->id);

            DB::commit();

            return $certificate->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject certificate.
     */
    public function rejectCertificate(int $certificateId, int $ustadzId, string $reason): Certificate
    {
        $certificate = Certificate::findOrFail($certificateId);

        $certificate->update([
            'status' => 'rejected',
            'approved_by_ustadz_id' => $ustadzId,
            'approved_at' => now(),
            'metadata_json' => array_merge(
                $certificate->metadata_json ?? [],
                ['rejection_reason' => $reason]
            ),
        ]);

        return $certificate;
    }

    /**
     * Generate certificate number.
     */
    protected function generateCertificateNumber(?int $pesantrenId): string
    {
        $prefix = $pesantrenId ? "CERT-{$pesantrenId}" : "CERT-GEN";
        $year = date('Y');
        $month = date('m');

        // Get last number for this month
        $lastCert = Certificate::where('certificate_number', 'like', "{$prefix}-{$year}{$month}-%")
            ->orderBy('certificate_number', 'desc')
            ->first();

        if ($lastCert) {
            $lastNumber = intval(substr($lastCert->certificate_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AuditFinancialDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --pesantren= limits output to one tenant id.
     */
    protected $signature = 'audit:financial-data {--pesantren=}';

    /**
     * The console command description.
     */
    protected $description = 'Audit financial data availability for stakeholder reporting.';

    public function handle(): int
    {
        $pesantrenOption = $this->option('pesantren');
        $pesantrenId = is_numeric($pesantrenOption) ? (int) $pesantrenOption : null;

        $this->info('Financial Data Audit');
        $this->line('DB: ' . config('database.default') . ' / ' . config('database.connections.mysql.database'));

        $baseQuery = DB::table('users')
            ->select('id', 'name', 'user_type', 'pesantren_id')
            ->where('user_type', 'stakeholder');

        if ($pesantrenId !== null) {
            $baseQuery->where('pesantren_id', $pesantrenId);
        }

        $stakeholders = $baseQuery->orderBy('id')->get();

        if ($stakeholders->isEmpty()) {
            $this->warn('No stakeholder users found for the selected filter.');
            return self::SUCCESS;
        }

        $rows = [];

        foreach ($stakeholders as $stakeholder) {
            $tenantId = (int) $stakeholder->pesantren_id;

            $donationsCount = DB::table('donations')->where('pesantren_id', $tenantId)->count();
            $donationsAmount = (float) DB::table('donations')->where('pesantren_id', $tenantId)->sum('amount');

            $legacyCount = DB::table('appreciation_funds')->where('pesantren_id', $tenantId)->count();
            $legacyAmount = (float) DB::table('appreciation_funds')->where('pesantren_id', $tenantId)->sum('amount');

            $paymentsCount = DB::table('payments')->where('pesantren_id', $tenantId)->count();
            $paymentsAmount = (float) DB::table('payments')->where('pesantren_id', $tenantId)->sum('amount');

            $rows[] = [
                'stakeholder_id' => $stakeholder->id,
                'stakeholder' => $stakeholder->name,
                'pesantren_id' => $tenantId,
                'donations_count' => $donationsCount,
                'donations_amount' => number_format($donationsAmount, 0, ',', '.'),
                'legacy_count' => $legacyCount,
                'legacy_amount' => number_format($legacyAmount, 0, ',', '.'),
                'payments_count' => $paymentsCount,
                'payments_amount' => number_format($paymentsAmount, 0, ',', '.'),
            ];
        }

        $this->table([
            'Stakeholder ID',
            'Stakeholder',
            'Pesantren ID',
            'Donations Rows',
            'Donations Amount',
            'Legacy Rows',
            'Legacy Amount',
            'Payments Rows',
            'Payments Amount',
        ], $rows);

        $noData = collect($rows)->every(function (array $row) {
            return ((int) $row['donations_count'] === 0)
                && ((int) $row['legacy_count'] === 0)
                && ((int) $row['payments_count'] === 0);
        });

        if ($noData) {
            $this->warn('No financial records found for selected stakeholder tenant(s).');
            $this->line('Action: verify the active DB in .env or import transactions into donations/appreciation_funds.');
        } else {
            $this->info('Financial records detected. Stakeholder financial summary should render non-zero data.');
        }

        return self::SUCCESS;
    }
}

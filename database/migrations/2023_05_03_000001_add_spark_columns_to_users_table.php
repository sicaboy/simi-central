<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSparkColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->string('pm_expiration')->nullable();
            $table->text('extra_billing_information')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_address_line_2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code', 25)->nullable();
            $table->string('billing_country', 2)->nullable();
            $table->string('vat_id', 50)->nullable();
            $table->text('receipt_emails')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Drop index first if it exists (for SQLite compatibility)
            if (Schema::hasColumn('teams', 'stripe_id')) {
                $table->dropIndex(['stripe_id']);
            }

            $columns = [
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'pm_expiration',
                'extra_billing_information',
                'trial_ends_at',
                'billing_address',
                'billing_address_line_2',
                'billing_city',
                'billing_state',
                'billing_postal_code',
                'billing_country',
                'vat_id',
                'receipt_emails',
            ];

            // Only drop columns that exist
            $existingColumns = [];
            foreach ($columns as $column) {
                if (Schema::hasColumn('teams', $column)) {
                    $existingColumns[] = $column;
                }
            }

            if (! empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
}

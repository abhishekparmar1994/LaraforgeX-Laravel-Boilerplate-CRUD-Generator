<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('crm_solar_product_groups')) {
            Schema::create('crm_solar_product_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150)->unique();
                $table->string('code', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('crm_solar_customer_groups')) {
            Schema::create('crm_solar_customer_groups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_group_id')->nullable()->constrained('crm_solar_product_groups')->nullOnDelete();
                $table->string('name', 150);
                $table->string('code', 50)->nullable();
                $table->text('description')->nullable();
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }

        Schema::table('crm_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_leads', 'product_group_id')) {
                $table->foreignId('product_group_id')->nullable()->after('product_group')->constrained('crm_solar_product_groups')->nullOnDelete();
            }
            if (!Schema::hasColumn('crm_leads', 'customer_group_id')) {
                $table->foreignId('customer_group_id')->nullable()->after('customer_group')->constrained('crm_solar_customer_groups')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            if (Schema::hasColumn('crm_leads', 'customer_group_id')) {
                $table->dropForeign(['customer_group_id']);
                $table->dropColumn('customer_group_id');
            }
            if (Schema::hasColumn('crm_leads', 'product_group_id')) {
                $table->dropForeign(['product_group_id']);
                $table->dropColumn('product_group_id');
            }
        });

        Schema::dropIfExists('crm_solar_customer_groups');
        Schema::dropIfExists('crm_solar_product_groups');
    }
};

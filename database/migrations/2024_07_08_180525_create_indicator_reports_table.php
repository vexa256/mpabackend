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
        Schema::create('indicator_reports', function (Blueprint $table) {
            $table->id();
            $table->string('RID', 255);
            $table->string('IID', 255);
            $table->text('Entity');
            $table->text('ReportedBy')->nullable();
            $table->text('Response');
            $table->text('Comments');
            $table->string('IndicatorResponsePercentageScore', 200);
            // $table->timestamps();
            $table->string('ApprovalStatus', 200)->default('false');
            $table->string('ResponseType', 200)->nullable();
            $table->timestamps();
        });

        \DB::unprepared('
        CREATE TRIGGER before_insert_indicator_reports
        BEFORE INSERT ON indicator_reports
        FOR EACH ROW
        BEGIN
            SET NEW.RID = CONCAT(
                SUBSTRING(MD5(RAND()), 1, 8),
                SUBSTRING(MD5(RAND()), 1, 4),
                SUBSTRING(MD5(RAND()), 1, 4),
                SUBSTRING(MD5(RAND()), 1, 4)
            );
        END;
    ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicator_reports');
        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_indicator_reports');

    }
};
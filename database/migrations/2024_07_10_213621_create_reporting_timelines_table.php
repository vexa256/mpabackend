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
        Schema::create('reporting_timelines', function (Blueprint $table) {
            $table->id();
            $table->string('ReportName');
            $table->string('Type');
            $table->string('Description');
            $table->string('ReportingID');
            $table->string('status')->default('active');
            $table->integer('Year');
            $table->timestamps();
        });

        \DB::unprepared('
        CREATE TRIGGER before_insert_reporting_timelines
        BEFORE INSERT ON reporting_timelines
        FOR EACH ROW
        BEGIN
            SET NEW.ReportingID = CONCAT(
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
        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_reporting_timelines');

        Schema::dropIfExists('reporting_timelines');
    }
};
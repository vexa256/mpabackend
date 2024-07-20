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
        Schema::create('project_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('IndicatorPrimaryCategory', 255)->nullable();
            $table->string('IndicatorSecondaryCategory', 255)->nullable();
            $table->string('EntityID', 255);
            $table->string('IID', 255);
            $table->string('Indicator', 255)->nullable();
            $table->string('IndicatorDefinition');
            $table->string('IndicatorQuestion');
            $table->string('RemarksComments');
            $table->string('SourceOfData', 255)->nullable();
            // $table->string('ReportingRequirements', 255)->nullable();
            $table->string('ResponseType', 255);

            $table->string('ReportingPeriod', 200)->nullable();
            $table->timestamps();
        });

        \DB::unprepared('
        CREATE TRIGGER before_insert_project_indicators
        BEFORE INSERT ON project_indicators
        FOR EACH ROW
        BEGIN
            SET NEW.IID = CONCAT(
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

        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_project_indicators');

        Schema::dropIfExists('project_indicators');
    }
};
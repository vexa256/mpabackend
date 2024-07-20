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

        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('Entity', 255);
            $table->string('EntityID', 255);
            $table->text('EntityProjectDetails');
            $table->timestamps();
        });

        DB::unprepared('CREATE TRIGGER before_insert_entities
BEFORE INSERT ON entities
FOR EACH ROW
BEGIN
    SET NEW.EntityID = CONCAT(
        SUBSTRING(MD5(RAND()), 1, 8),
        SUBSTRING(MD5(RAND()), 1, 4),
        SUBSTRING(MD5(RAND()), 1, 4),
        SUBSTRING(MD5(RAND()), 1, 4)
    );
END');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_entities');

    }
};
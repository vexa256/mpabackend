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

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('EntityID')->nullable();
            $table->string('UserCode')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Nationality')->nullable();
            $table->string('PhoneNumber')->nullable();
            $table->string('Address')->nullable();
            $table->string('ParentOrganization')->nullable();
            $table->string('Sex')->nullable();
            $table->string('JobTitle')->nullable();
            $table->string('AccountRole')->nullable();
            $table->string('UserID')->nullable();
            // $table->string('Email')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::dropIfExists('password_reset_tokens');

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::dropIfExists('sessions');

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        \DB::unprepared('CREATE TRIGGER before_insert_users
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    SET NEW.UserID = CONCAT(
        SUBSTRING(MD5(RAND()), 1, 8),
        SUBSTRING(MD5(RAND()), 1, 4),
        SUBSTRING(MD5(RAND()), 1, 4),
        SUBSTRING(MD5(RAND()), 1, 4)
    );
END');

        \DB::unprepared('
DROP PROCEDURE IF EXISTS GenerateUniqueUserCode;
CREATE PROCEDURE GenerateUniqueUserCode(OUT code VARCHAR(4))
BEGIN
    DECLARE newCode VARCHAR(4);
    DECLARE isUnique BOOLEAN DEFAULT FALSE;

    WHILE isUnique = FALSE DO
        SET newCode = LPAD(FLOOR(RAND() * 9000) + 1000, 4, "0");
        SELECT NOT EXISTS(
            SELECT 1 FROM users WHERE UserCode = newCode
        ) INTO isUnique;
    END WHILE;

    SET code = newCode;
END;

DROP TRIGGER IF EXISTS before_insert_user;
CREATE TRIGGER before_insert_user
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    CALL GenerateUniqueUserCode(NEW.UserCode);
END;
');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        \DB::unprepared('DROP TRIGGER IF EXISTS before_insert_users');

        \DB::unprepared('
            DROP TRIGGER IF EXISTS before_insert_user;
            DROP PROCEDURE IF EXISTS GenerateUniqueUserCode;
        ');
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
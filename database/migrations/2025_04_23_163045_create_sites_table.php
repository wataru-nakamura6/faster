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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('ラベル名');
            $table->integer('status')->default(0)->comment('表示状況');
            $table->bigInteger('user_id')->comment('ユーザー識別ID');
            $table->string('url')->comment('URL');
            $table->string('uuid')->unique()->comment('URL識別ID');
            $table->string('remark')->comment('備考');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};

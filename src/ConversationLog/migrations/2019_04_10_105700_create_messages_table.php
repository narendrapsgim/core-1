<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('user_id');
            $table->string('author');
            $table->text('message');
            $table->string('type');
            $table->binary('data')->nullable(true);
            $table->string('message_id')->nullable();
            $table->string('matched_intent')->nullable();
            $table->string('scene_id')->nullable();
            $table->string('conversation_id')->nullable();
            $table->text('user')->nullable();

            $table->index('message_id');
        });

        if (DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) == 'sqlite') {
            Schema::table('messages', function (Blueprint $table) {
                $table->timestamp('microtime', 6)->nullable();
            });
            return;
        };

        Schema::table('messages', function (Blueprint $table) {
            $table->timestamp('microtime', 6)->default(DB::raw('CURRENT_TIMESTAMP(6)'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}

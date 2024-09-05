<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appliance_id')->constrained('appliances')->onDelete('cascade');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->decimal('duration', 8, 2)->nullable(); // in hours
            $table->decimal('cost', 8, 2)->nullable(); // calculated cost
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usage_logs');
    }
};

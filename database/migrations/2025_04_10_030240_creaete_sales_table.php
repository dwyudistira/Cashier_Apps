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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string("invoice_number");
            $table->string("name");
            $table->foreignId("product_id")->nullable()->constrained("products")->onDelete("cascade");
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->jsonb('product_data');
            $table->unsignedInteger("quantity");
            $table->string("subtotal");
            $table->string("diskon_member")->default(0);
            $table->string("total_paid");
            $table->string("made_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

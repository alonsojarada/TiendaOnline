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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Relación con categorías: si se borra una categoría, se protegen los productos o se configuran
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            //$table->foreignId('category_id')->constrained('cat_productos', 'id_cat')->onDelete('cascade');
        
            $table->string('name'); // Nombre del producto
            $table->string('slug')->unique(); // URL amigable: 'tenis-running-nike'
            $table->text('description')->nullable(); // Detalles del producto
            $table->decimal('price', 10, 2); // Precio con hasta 10 dígitos en total y 2 decimales (ej: 1499.50)
            $table->integer('stock')->default(0); // Cantidad disponible en inventario
            $table->string('sku')->unique()->nullable(); // Código de barras o identificador interno de inventario
            $table->boolean('is_active')->default(true); // Para ocultar o mostrar el producto en la tienda
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

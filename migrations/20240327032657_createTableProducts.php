<?php

use Phpmig\Migration\Migration;

class CreateTableProducts extends Migration
{
     /**
     * get the the in instance of Capsule
     * from container
     * 
     * @return Capsule 
     */
    protected function capsule()
    {
        $container = $this->getContainer();
        $capsule = $container['db'];
        return $capsule;
    }

    /**
     * Do the migration
     */
    public function up()
    {
        $this->capsule()::schema()->create('products', function ($table) {
            $table->id();
            $table->string('title');
            $table->decimal('price');
            $table->string('description');
            $table->string('pictureLink');
            $table->dateTime('createdAt');
            $table->unsignedBigInteger('idUserFk');
            $table
                ->foreign('idUserFk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->capsule()::Schema()->drop('products');
    }
}

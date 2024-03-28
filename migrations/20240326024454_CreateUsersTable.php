<?php

use Phpmig\Migration\Migration;

class CreateUsersTable extends Migration
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
        $this->capsule()::schema()->create('users', function ($table) {
            $table->id();
            $table->string('login');
            $table->string('password');
        });

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $this->capsule()::Schema()->drop('users');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsAndTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'category_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_name' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->forge->addKey('category_id', true);
        $this->forge->createTable('categories');

        $this->forge->addField([
            'product_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'product_name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'qty_in_stock' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'price'        => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
        ]);
        $this->forge->addKey('product_id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'category_id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('products');

        $this->forge->addField([
            'transaction_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 50],
            'qty'            => ['type' => 'INT', 'constraint' => 11],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('transaction_id', true);
        $this->forge->addForeignKey('product_id', 'products', 'product_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
        $this->forge->dropTable('products');
        $this->forge->dropTable('categories');
    }
}
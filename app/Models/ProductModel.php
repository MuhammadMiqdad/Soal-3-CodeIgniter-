<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'product_id';
    protected $allowedFields = ['category_id', 'product_name', 'qty_in_stock', 'price'];
    protected $returnType    = 'array';
}
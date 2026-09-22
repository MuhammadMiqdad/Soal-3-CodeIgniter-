<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'transactions';
    protected $primaryKey    = 'transaction_id';
    protected $allowedFields = ['product_id', 'payment_method', 'qty', 'created_at'];
    protected $returnType    = 'array';
}
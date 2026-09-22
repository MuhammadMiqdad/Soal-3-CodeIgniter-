<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected ProductModel $productModel;
    protected TransactionModel $transactionModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->transactionModel = new TransactionModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categoryId = $this->request->getGet('category_id');

        $builder = $this->productModel
            ->select('products.*, categories.category_name')
            ->join('categories', 'categories.category_id = products.category_id', 'left');

        if ($categoryId) {
            $builder->where('products.category_id', $categoryId);
        }

        return view('products/index', [
            'products'   => $builder->findAll(),
            'categories' => $this->categoryModel->findAll(),
            'selectedCategory' => $categoryId,
        ]);
    }

    public function new()
    {
        $categories = $this->categoryModel->findAll();

        if (empty($categories)) {
            return redirect()->to('/categories/new')
                ->with('info', 'Belum ada kategori. Tambahkan kategori dulu sebelum membuat produk.');
        }

        return view('products/create', ['categories' => $categories]);
    }

    public function create()
    {
        $rules = [
            'category_id'  => 'required|is_natural_no_zero',
            'product_name' => 'required|min_length[2]|max_length[150]',
            'qty_in_stock' => 'required|is_natural',
            'price'        => 'required|decimal|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/products/new')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->productModel->save([
            'category_id'  => $this->request->getPost('category_id'),
            'product_name' => $this->request->getPost('product_name'),
            'qty_in_stock' => $this->request->getPost('qty_in_stock'),
            'price'        => $this->request->getPost('price'),
        ]);

        return redirect()->to('/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('products/edit', [
            'product'    => $this->productModel->find($id),
            'categories' => $this->categoryModel->findAll(),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'category_id'  => 'required|is_natural_no_zero',
            'product_name' => 'required|min_length[2]|max_length[150]',
            'qty_in_stock' => 'required|is_natural',
            'price'        => 'required|decimal|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/products/edit/' . $id)
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->productModel->update($id, [
            'category_id'  => $this->request->getPost('category_id'),
            'product_name' => $this->request->getPost('product_name'),
            'qty_in_stock' => $this->request->getPost('qty_in_stock'),
            'price'        => $this->request->getPost('price'),
        ]);

        return redirect()->to('/products')->with('success', 'Produk berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->productModel->delete($id);
        return redirect()->to('/products')->with('success', 'Produk berhasil dihapus.');
    }

    public function buy($id)
    {
        return view('products/buy', [
            'product' => $this->productModel->find($id),
        ]);
    }

    public function processBuy($id)
    {
        $product = $this->productModel->find($id);
        $isAjax = $this->request->isAJAX();

        if (!$product) {
            if ($isAjax) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.',
                ]);
            }
            return redirect()->to('/products')->with('error', 'Produk tidak ditemukan.');
        }

        $rules = [
            'qty'            => "required|is_natural_no_zero|less_than_equal_to[{$product['qty_in_stock']}]",
            'payment_method' => 'required|in_list[cash,transfer,qris]',
        ];
        $messages = [
            'qty' => [
                'less_than_equal_to' => 'Qty melebihi stok yang tersedia (stok: ' . $product['qty_in_stock'] . ').',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON([
                    'success' => false,
                    'errors'  => $this->validator->getErrors(),
                ]);
            }
            return redirect()->to('/products/buy/' . $id)
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $qty = (int) $this->request->getPost('qty');

        $this->transactionModel->insert([
            'product_id'     => $id,
            'payment_method' => $this->request->getPost('payment_method'),
            'qty'            => $qty,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        $this->productModel->update($id, [
            'qty_in_stock' => $product['qty_in_stock'] - $qty,
        ]);

        if ($isAjax) {
            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Pembelian berhasil dicatat.',
                'redirect' => '/products',
            ]);
        }

        return redirect()->to('/products')->with('success', 'Pembelian berhasil.');
    }
}
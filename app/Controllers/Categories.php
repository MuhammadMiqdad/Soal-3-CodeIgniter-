<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        return view('categories/index', [
            'categories' => $this->categoryModel->findAll(),
        ]);
    }

    public function new()
    {
        return view('categories/create');
    }

    public function create()
    {
        if (!$this->validate([
            'category_name' => 'required|min_length[2]|max_length[100]|is_unique[categories.category_name]',
        ])) {
            return redirect()->to('/categories/new')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'category_name' => $this->request->getPost('category_name'),
        ]);

        return redirect()->to('/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('categories/edit', [
            'category' => $this->categoryModel->find($id),
        ]);
    }

    public function update($id)
    {
        if (!$this->validate([
            'category_name' => "required|min_length[2]|max_length[100]|is_unique[categories.category_name,category_id,{$id}]",
        ])) {
            return redirect()->to('/categories/edit/' . $id)
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->update($id, [
            'category_name' => $this->request->getPost('category_name'),
        ]);

        return redirect()->to('/categories')->with('success', 'Kategori berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
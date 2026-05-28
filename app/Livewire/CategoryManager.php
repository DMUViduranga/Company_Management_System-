<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $name;
    public $categoryId;

    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditMode = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'categoryId', 'isEditMode']);
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($this->isEditMode) {
            $category = Category::findOrFail($this->categoryId);
            $category->update(['name' => $this->name]);
            session()->flash('success', 'Category updated successfully.');
        } else {
            Category::create(['name' => $this->name]);
            session()->flash('success', 'Category created successfully.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->categoryId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Category::findOrFail($this->categoryId)->delete();
        session()->flash('success', 'Category deleted successfully.');
        $this->closeModal();
    }

    public function render()
    {
       
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.category-manager', [
            'categories' => $categories
        ]);
    }
}
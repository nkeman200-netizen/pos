<?php
namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $name, $selected_id;
    public $isModalOpen = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|min:3|unique:categories,name',
    ];

    public function render()
    {
        return view('livewire.categories.index', [
            'categories' => Category::where('name', 'like', '%'.$this->search.'%')
                            ->latest()->paginate(10)
        ]);
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { 
        $this->isModalOpen = false; 
        $this->reset(['name', 'selected_id']);
    }

    public function store()
    {
        $this->validate();
        Category::updateOrCreate(['id' => $this->selected_id], ['name' => $this->name]);
        
        session()->flash('message', $this->selected_id ? 'Kategori diperbarui.' : 'Kategori ditambah.');
        $this->closeModal();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $category->name;
        $this->openModal();
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        session()->flash('message', 'Kategori dihapus.');
    }
}
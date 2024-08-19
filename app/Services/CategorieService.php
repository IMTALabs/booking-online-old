<?php

namespace App\Services;

use App\Models\Categorie;
use App\Traits\APIResponse;

class CategorieService
{
    use APIResponse;

    public function getAllCategorie()
    {
        return Categorie::query()->get();

    }

    public function getCategorieById($id)
    {
        return Categorie::find($id);
    }

    public function createCategorie($data)
    {

        return Categorie::create($data);
    }

    public function updateCategorie($id, $data)
    {
        return Categorie::find($id);

    }

    public function deleteCategorie($id)
    {
        return Categorie::find($id);

    }
}

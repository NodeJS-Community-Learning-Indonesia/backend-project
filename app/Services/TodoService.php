<?php

namespace App\Services;

use App\Models\Todo;

class TodoService {
    public function getAllTodo(){
        return Todo::all();
    }

    public function findTodoById($id){
        return Todo::findOrFail($id);
    }

    public function createOrUpdateTodo($data){
        $id = null;

        if(isset($data['id'])){
            $id = $data['id'];
            unset($data['id']);
        }

        return Todo::updateOrCreate(
            [
                'id' => $id
            ], 
            $data
        );
    }
}
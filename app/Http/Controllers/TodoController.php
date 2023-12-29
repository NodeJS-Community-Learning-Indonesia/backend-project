<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Services\TodoService;
use Error;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return TodoResource::collection($this->todoService->getAllTodo())->additional([
            'success' => true,
            'message' => 'Get all todos'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request)
    {
        $data = $request->validated();

        try {
            //code...
            $todo = $this->todoService->createOrUpdateTodo($data);

            $msg = $todo->wasRecentlyCreated ? 'added' : 'updated';

            return (new TodoResource($todo))->additional([
                'message' => "Todo {$msg} succesfully"
            ])->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            //throw $th;
            throw new Error($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (new TodoResource($this->todoService->findTodoById($id)))->additional([
            'message' => 'Get a todo'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = $this->todoService->findTodoById($id);

        try {
            $todo->delete();

            return (new TodoResource($todo))->additional([
                'message' => 'Todo has been deleted successfully'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            throw new Error($th->getMessage());
        }
    }
}

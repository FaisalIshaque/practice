<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;

class TasksController extends Controller
{
    //

    protected $tasks;

    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    /*public function index(Request $request){


        $tasks = Task::where('user_id', $request->user()->id)->get();

    	return view('task',compact('tasks'));

    }*/

    public function index(Request $request)
    {
        return view('task', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }
    
    public function store(Request $request){


		/*$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
		]);

		if ($validator->fails()) {
		return redirect('task')
		->withInput()
		->withErrors($validator);
    	}*/
        //the above validation and redirect can be done with the following code

         $this->validate($request, [
            
            'name' => 'required|max:255',
            
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name
            ]);

		return redirect('task');
}

    public function destroy(Task $task)
    {
        $this->authorize('destroy', $task);

    	$task->delete();

    	return redirect('task');    	
    }
}

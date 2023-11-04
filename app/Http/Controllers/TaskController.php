<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Throwable;

class TaskController extends Controller
{
  /** Create a new task */
  public function create(Request $request, Task $task)
  {
    $this->validate($request, [
      'title' => 'required|string',
      'order'=> 'required|numeric',
    ]);

    try {
      $newTask = $task->create([
        'user_id' => $request->user()->id,
        'title' => $request->title,
        'order' => $request->order
      ]);
    } catch (Throwable $e) {
      report($e);
      return response([
        'status' => false,
        'message' => 'Something went wrong when creating the task.'
      ], 500);
    }

    return response()->json([
      'status' => (bool) $newTask,
      'data' => $newTask,
      'message' => 'Task created.'
    ]);
  }

  /** Read all of the tasks of the current user */
  public function read(Request $request, Task $task)
  {
    $tasks = $task->where("user_id", $request->user()->id)
      ->orderBy('order')
      ->take($request->get('limit', 10))
      ->get();

    return view('task', compact('tasks'));
  }

  /** Update status of the task */
  public function updateStatus(Request $request, Task $task, $id)
  {
    $this->validate($request, [
      'status' => 'required|boolean'
    ]);

    try {
      $current_task = $task->find($id);
      $current_task->status = $request->status;
      $current_task->save();
    } catch (Throwable $e) {
      report($e);
      return response([
        'status' => (bool) $current_task,
        'message' => 'Something went wrong when updating the task.'
      ], 500);
    }

    return response()->json([
      'status' => (bool) $current_task,
      'data' => $current_task,
      'message' => 'Status updated successfully.'
    ]);
  }

  /** Update content of the task */
  public function updateContent(Request $request, Task $task, $id)
  {
    $this->validate($request, [
      'title' => 'required|string'
    ]);

    try {
      $current_task = $task->find($id);
      $current_task->title = $request->title;
      $current_task->save();
    } catch (Throwable $e) {
      report($e);
      return response([
        'status' => (bool) $current_task,
        'message' => 'Something went wrong when updating the task.'
      ], 500);
    }

    return response()->json([
      'status' => (bool) $current_task,
      'data' => $current_task,
      'message' => 'Status updated successfully.'
    ]);
  }

  /** Update tasks order */
  public function updateOrder(Request $request, Task $task)
  {
    $this->validate($request, [
      'tasks.*.order' => 'required|numeric'
    ]);

    $tasks = $task->all();

    foreach ($tasks as $taskitem) {
      $id = $taskitem->id;
      foreach ($request->tasks as $newTasks) {
        if ($newTasks['id'] == $id) {
          $taskitem->update(['order' => $newTasks['order']]);
        }
      }
    }

    return response('Updated Successfully.', 200);
  }

  /** Delete a task */
  public function destroy(Request $request, Task $task, $id)
  {
    try {
      $task->where('id', $id)->delete();
    } catch (Throwable $e) {
      report($e);
      return response([
        'status' => false,
        'message' => 'Something went wrong when deleting the task.'
      ], 500);
    }

    return response()->json([
      'status' => true,
      'data' => (int) $id,
      'message' => 'Task Deleted.'
    ]);
  }
}

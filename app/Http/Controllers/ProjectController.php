<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;
use auth;


class ProjectController extends Controller
{
    public function index()
    {
        try {
            $data = Project::all();
            return response()->json(['status' => 'Успешно', 'message' => $data]);
        }
        catch (\Exception $e){
            return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
        }

    }
    public function store(Request $request)
    {
        if (!empty(auth::user()->id)){
            $userId = auth::user()->id;
        }
        else{
            $userId = null;
        }
        try{
            $data = new Project();
            $data->name       = $request->name;
            $data->components = $request->components;
            $data->user_id    = $userId;
            $data->save();
            return response()->json(['status'  => 'Успешно',
                'name'         =>  $request->name,
                'components'   =>  $request->components,
                'user_id'      =>  $userId,
            ]);
        }
        catch (\Exception $e){
            return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
        }
    }
    public function update($id,Request $request)
    {
        if (!empty(auth::user()->id)){
            $userId = auth::user()->id;
        }
        else{
            $userId = null;
        }
        try{
            $data = Project::find($id);
            $data->name       = $request->name;
            $data->components = $request->components;
            $data->user_id    = $userId;
            $data->update();
            return response()->json(['status'  => 'Успешно',
                'name'         =>  $request->name,
                'components'   =>  $request->components,
                'user_id'      =>  $userId,
            ]);
        }
        catch (\Exception $e){
            return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            Project::find($id)->delete();
            return response()->json(['status' => 'Успешно', 'message' => 'Пункт удален']);
        }
        catch (\Exception $e){
            return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
        }
    }
    public function dublicat(Request $request,$id)
    {
        try {
            $task = Project::find($id);
            if (empty($task)){
                return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
            }
            $newTask = $task->replicate();
            $newTask->save();
            return response()->json(['status' => 'Успешно', 'message' => 'Дубликат готов']);
        }
        catch (\Exception $e){
            return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
        }

    }
    public function updateName($id,Request $request){
        try {
            Project::find($id)->update(['name' => $request->name]);
            return response()->json(['status' => 'Успешно', 'message' => 'Дубликат готов']);
        }catch (\Exception $e){
            return response()->json(['status' => 'Ошибка', 'message' => $e->getMessage()]);
        }
    }
}

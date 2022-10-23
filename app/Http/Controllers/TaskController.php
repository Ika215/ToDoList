<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator; //バリデ―ション

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$tasks = Task::all();//モデル名::all()でモデルのレコードを全部取得
        //return view('tasks.index', ['tasks' => $tasks]);でも上記と同じ内容

        $tasks = Task::where('status', false)->get();

        //$変数 = モデルクラス::where(カラム名, 値)->get(); // 複数のレコードを取得するとき
        //$変数 = モデルクラス::where(カラム名, 値)->first(); // 最初のレコードだけ取得するとき

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'task_name' => 'required|max:100',
            'deadline_date' => 'after:now',
        ];

        $messages = ['required' => '必須項目です', 'max' => '100文字以下にしてください。', 'deadline_date' => '現在よりも後の日時を入力してください'];
        Validator::make($request->all(), $rules, $messages)->validate();

        //モデルをインスタンス化
        $task = new Task;

        //モデル->カラム名　=　値で、データを割り当てる
        $task->name = $request->input('task_name');
        $task->deadline_at = $request->input('deadline_date');

        //データべ―スに保存
        $task->save();

        //リダイレクト
        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);//一致するレコード取得
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //「編集」ボタンをおしたとき
        if ($request->status === null) {
        //バリデートの処理
        $rules = [
            'task_name' => 'required|max:100',
        ];
        $messages = ['required' => '必須項目です', 'max' => '100文字以下にしてください。'];
        Validator::make($request->all(), $rules, $messages)->validate();

        //該当のタスクを検索
        $task = Task::find($id);

        //モデル->カラム名 = 値で、データを割り当てる
        $task->name = $request->input('task_name');

        //データベースに保存
        $task->save();
        } else {
            //「完了」ボタンをおしたとき
            //該当のタスクを検索
            $task = Task::find($id);

            //モデル->カラム名-> = 値で、データを割り当てる
            $task->status = true; //ture:完了、false:未完了

            //データベースに保存
            $task->save();
        }

        //リダイレクト
        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id)->delete();
        return redirect('/tasks');
    }
}

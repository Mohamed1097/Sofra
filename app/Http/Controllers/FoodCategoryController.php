<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;

class FoodCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $message=null;
        $foodCategories=new FoodCategory();
        if (request()->name) {
            $foodCategories=$foodCategories->where('name','like','%'.request()->name.'%');
        }
        if (!$foodCategories->count()) {
            $message='There Is No food Categories';
        }
        $foodCategories=$foodCategories->paginate();
        return view('foodCategories.index',['title'=>'Food Categories','message'=>$message,'foodCategories'=>$foodCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('foodCategories.create',['title'=>'Add New Food Category']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=validator()->make($request->all(),['name'=>'required|string']);
        if ($validator->fails()) {
            return redirect()->route('admin.food-categories.create')->withErrors($validator->errors());
        }
        FoodCategory::create($request->all());
        return redirect()->route('admin.food-categories.index');
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
        $foodCategory=FoodCategory::findOrFail($id);
        $title='Edit '.$foodCategory->name;
        return view('foodCategories.edit',['title'=>$title,'foodCategory'=>$foodCategory]);
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
        $validator=validator()->make($request->all(),['name'=>'required|string']);
        if ($validator->fails()) {
            return redirect()->route('admin.food-categories.edit',['food_category'=>$id])->withErrors($validator->errors());
        }
        $foodCategory=FoodCategory::findOrFail($id);
        if($foodCategory->name==$request->name)
        {
            return redirect(route('admin.food-categories.index'));
        }
        if ($foodCategory->restaurants->count()) {
            return redirect()->route('admin.food-categories.edit',['food_category'=>$id])->withErrors(['name'=>'You Can’t Change The name of this Category']);
        }
        $foodCategory->update($request->all());
        return redirect(route('admin.food-categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $foodCategory=FoodCategory::findOrFail($id);
        if ($foodCategory->restaurants->count()) {
            return responseJson(0,'لا تسطتيع مسح هذالصنف');
        }
        else
        {
            $foodCategory->delete();
            return responseJson(1,'تم مسح الصنف بنجاح');
        }
    }
}

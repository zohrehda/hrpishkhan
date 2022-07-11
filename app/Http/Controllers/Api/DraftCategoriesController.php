<?php

namespace App\Http\Controllers\Api;

use App\DraftCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DraftCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $categories = DraftCategory::where('user_id', $request->input('user_id'))->get()->toArray();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $rules = [
            'cat_name' => ['required', 'string', 'min:3', 'max:50'],
            'cat_id' => ['exists:draft_categories,id'],
        ];
        if (!$request->post('cat_update')) {
            $rules['cat_name'][] = 'unique:draft_categories,name';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $new = DraftCategory::updateOrcreate(
            ['id' => $request->post('cat_id')],
            ['name' => $request->post('cat_name'),
            'user_id' => $request->post('user_id')]);

        return response()->json($new);
    }

    public function destroy(Request $request, $id)
    {
        $includes_drafts = $request->input('includes_drafts');
        $category = DraftCategory::find($id);
        $drafts = $category->drafts ?: [];
        if ($includes_drafts) {
            $category->drafts()->delete();
        } else {
            $category->drafts()->update(['cat_id' => null]);
        }
        $category->delete();
        return response()->json($drafts);
    }
}









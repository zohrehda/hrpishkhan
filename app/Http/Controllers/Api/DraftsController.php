<?php

namespace App\Http\Controllers\Api;

use App\Draft;
use App\DraftCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Validator;

class DraftsController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->input('user_id');
        $categories = DraftCategory::where('user_id', $user_id)
            ->orWhereHas('drafts', function ($query) {
                return $query->where('public', '1');
            })
            ->get()->map(function ($item) use ($user_id) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'user_id' => $item->user_id,
                    'drafts' => $item->drafts()->where(function ($query) use ($user_id) {
                        $query->where('user_id', $user_id)->orWhere('public', "1");
                    })->get()->toArray()
                ];
            })->toArray();

        $withOutCat = Draft::whereDoesntHave('category')->where(function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->orWhere('public', '1');
        })->get()->toArray();

        $categories[] = [
            'id' => null,
            'name' => null,
            'drafts' => $withOutCat
        ];
        return response()->json($categories);
    }

    public function show($id)
    {
        $data = Draft::find($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $rules = [
            'draft_name' => ['required', 'string', 'min:3', 'max:50'],
            'draft_id' => 'exists:drafts,id',
            'draft_cat_id' => 'exists:draft_categories,id|nullable',
        ];
        if (!$request->post('update')) {
            $rules['draft_name'][] = 'unique:drafts,name';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $new = Draft::updateOrCreate(
            ['id' => $request->post('draft_id')],
            ['name' => $request->post('draft_name'),
                'cat_id' => $request->post('draft_cat_id'),
                'user_id' => $request->post('user_id'),
                'public' => $request->input('public_draft') ? '1' : '0',
            ]);

        if ($request->post('includes_main_form')) {
            $new->update([
                'draft' => json_encode($request->except(['draft_name', 'draft_cat_id', 'draft_public']))
            ]);
        }
        return response()->json($new);
    }

    public function destroy($id)
    {
        Draft::find($id)->delete();
    }
}

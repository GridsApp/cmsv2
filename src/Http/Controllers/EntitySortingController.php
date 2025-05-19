<?php

namespace twa\cmsv2\Http\Controllers;


use Illuminate\Support\Facades\DB;


class EntitySortingController extends Controller
{
    public function render($slug)
    {
        try {
            $entity = get_entity($slug);
        } catch (\Throwable $th) {
            abort(404);
        }
        if (!isset($entity->enableSorting) || (isset($entity->enableSorting) && !$entity->enableSorting)) {
            abort(404);
        }
        if (!isset($entity->sortingCardLabel) || (isset($entity->sortingCardLabel) && !$entity->sortingCardLabel)) {
            abort(404);
        }
        $rows =  DB::table($entity->tableName)->select('id', $entity->sortingCardLabel . ' AS label', 'orders')->orderBy('orders', 'ASC')->get();
        return view('CMSView::pages.entity.cms-entity-sorting', ['rows' => $rows, 'entity' => $entity]);
    }


    public function save($slug)
    {

        try {
            $entity = get_entity($slug);
        } catch (\Throwable $th) {
            return response()->json([], 400);
        }
        if (!isset($entity->enableSorting) || (isset($entity->enableSorting) && !$entity->enableSorting)) {
            return response()->json([], 400);
        }
        if (!isset($entity->sortingCardLabel) || (isset($entity->sortingCardLabel) && !$entity->sortingCardLabel)) {
            return response()->json([], 400);
        }
        $ids = request()->input('ids');
        if (!is_array($ids)) {
            $ids = [];
        }
        $cases = '';
        $idsString = [];
        foreach ($ids as $index => $id) {
            $id = (int) $id;
            $cases .= "WHEN {$id} THEN " . ($index + 1) . " ";
            $idsString[] = $id;
        }
        $idList = implode(',', $idsString);
        $tableName = $entity->tableName;
        $sql = "UPDATE {$tableName} SET orders = CASE id {$cases}END WHERE id IN ({$idList})";
        DB::update($sql);
        return response()->json([], 200);
    }
}

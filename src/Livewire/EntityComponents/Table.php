<?php

namespace twa\cmsv2\Livewire\EntityComponents;

// use App\Traits\ToastTrait;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use twa\cmsv2\Traits\ToastTrait;

class Table extends Component
{
    use WithPagination, ToastTrait;

    public $entity = null;
    public $columns = [];
    public $slug = null;

    public function mount()
    {

        if ($this->slug) {


            $currentClass = get_entity($this->slug);
            if (!$currentClass) {
                abort(404);
            }
            $this->entity = [
                'title' => $currentClass->entity,
                'table' => $currentClass->tableName,
                'slug' => $currentClass->slug,
                'gridRules' => $currentClass->gridRules,
                ...$currentClass->params
            ];

            $columns = $currentClass->columns();
            if (!$columns) {
                $columns = collect([]);
            }

            $columns = $columns->map(function ($field) {
                return [
                    "name" => $field['name'],
                    "label" => $field['label'],
                    "type" => $field['type'],
                    'info' => $field,
                    'translatable' => isset($field['translatable']) && $field['translatable']
                ];
            });

            $this->columns = $columns;
        }
    }

    public function handleDelete($selected)
    {


        if ($this->entity['gridRules'] ?? null) {

            $deleteRule = collect($this->entity['gridRules'])->where('operation', 'delete')->first();

            if ($deleteRule) {

                $ids =   DB::table($this->entity['table'])
                    ->where($deleteRule['condition']['field'], $deleteRule['condition']['operand'], $deleteRule['condition']['value'])
                    ->pluck('id');

                $intersectionFound = collect($selected)->intersect($ids)->count() > 0;

                if ($intersectionFound) {
                    $this->render();
                    $this->sendError("Not Deleted", "You don't have permission to delete this record");
                    return response()->json(["result" => true], 200);
                }
            }
        }


        // dd($selected);

        try {

            DB::beginTransaction();

            if (!is_array($selected)) {
                $selected = json_decode($selected, 1);
            }

            if (!is_array($selected)) {
                return;
            }

            DB::table($this->entity['table'])->whereIn('id', $selected)->update([
                'deleted_at' =>  now()
            ]);


            DB::commit();

            $this->render();

            $this->sendSuccess("Deleted", "Record sucessfully deleted");

            return response()->json(["result" => true], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            $this->render();

            $this->sendError("Not Deleted", "Record was not deleted");

            return response()->json(["result" => false], 200);
        }
    }

    public function render()
    {

        $rows = DB::table($this->entity['table'])->whereNull('deleted_at');


        if ($this->entity['conditions'] ?? null && is_array($this->entity['conditions']) && count($this->entity['conditions']) > 0) {
            foreach ($this->entity['conditions'] as $condition) {
                $rows->where($condition['field'], $condition['operand'], $condition['value']);
            }
        }

        $copyRows = $rows->limit($this->entity['pagination'] ?? 50);

        $selects =  collect($this->columns)

            ->filter(function ($q) {
                return str($q['type'])->contains("twa\cmsv2\Entities\FieldTypes\Select");
            })
            ->values();


        $select_values = [];

        foreach ($selects as $select) {
            if (isset($select['info']['multiple']) && $select['info']['multiple']) {
                $select_values[] = [
                    'field' => $select,
                    'values' =>  $copyRows->pluck($select['name'])->map(function ($item) {
                        return json_decode($item);
                    })->flatten()->unique()->values()->toArray()
                ];
            } else {
                $select_values[] = [
                    'field' => $select,
                    'values' => $copyRows->pluck($select['name'])->unique()->values()->toArray()
                ];
            }
        }

        $display_select = [];
        foreach ($select_values as $selected_value) {
            if (($selected_value['field']['info']['options']['type'] ?? '') != 'query') {
                continue;
            }

            $result = DB::table($selected_value['field']['info']['options']['table'])
                ->select('id', $selected_value['field']['info']['options']['field'] . ' as label')
                ->whereIn('id', $selected_value['values'])->get()->pluck('label', 'id')->toArray();

            $display_select[] = [
                'field' => $selected_value['field'],
                'result' => $result
            ];
        }




        $rows = $rows->paginate($this->entity['pagination'] ?? 50)->through(function ($row) use ($display_select) {

            foreach ($display_select as $select) {

                $key = $select['field']['name'];

                if (!isset($row->$key)) {

                    continue;
                }

                if (isset($select['field']['info']['multiple']) && $select['field']['info']['multiple']) {
                    $db_value = json_decode($row->$key);
                    if (!is_array($db_value)) {
                        $db_value = [];
                    }

                    foreach ($db_value as $db_val) {
                    }

                    $row->$key = collect($db_value)->map(function ($value) use ($select) {
                        return $select['result'][$value];
                    })->toArray();
                } else {
                    $row->$key = $select['result'][$row->$key] ?? null;
                }
            }

            return $row;
        });



        return view('CMSView::pages.entity.components.table', ['rows' => $rows]);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionSetting extends Model
{
    protected $guarded = [];
    private $primary;

    public static function schema(): array
    {
        $initial = config('requisition_items');
        $schema = [];
        foreach ($initial as $k => $v) {

            if (!empty($v['disabled'])) {
                continue;
            }
            if (empty($v['label'])) {
                $v['label'] = ucwords(str_replace('_', ' ', $k));
            }
            if (empty($v['value_type'])) {
                $v['value_type'] = 'string';
            }

            if (empty($v['dynamic'])) {
                $v['dynamic'] = false;
            }


            $schema[$k] = $v;
        }
        return $schema;
    }

    public function __construct($primary)
    {
        $this->primary = $primary;
        parent::__construct();
    }

    public function is_foreign(): bool
    {
        return (in_array($this->primary, ['attachment', 'determiners']));
    }

    public static function find($primary)
    {
        return (isset(self::schema()[$primary])) ? new self($primary) : null;
    }

    public static function validation_rules(): array
    {

        return array_map(function ($item) {
                return $item['validate_rules'];
            }
                , self::schema()

            )

            +
            ['competency.*' => 'required|array|min:2'];

    }

    public static function sections() : array
    {
        return [
            'requisition_information' => [
                'title' => 'Requisition Information',
                'items' => self::slice_items('department', 'level'),
            ],

            'position_information' => [
                'title' => 'Position Information',
                'items' => self::slice_items('en_title', 'replacement'),

            ],

            'job_requirements' => [
                'title' => 'Job Requirements',
                'items' => self::slice_items('field_of_study', 'comment'),
            ],

            'competency' => [
                'title' => 'Competency',
                'items' => self::slice_items('competency', 'competency'),
            ],
            'interviewers' => [
                'title' => 'Interviewers',
                'items' => self::slice_items('interviewers', 'interviewers'),
            ],
            'determiners' => [
                'title' => 'Approver Selection',
                'items' => self::slice_items('determiners', 'determiners'),
            ]
        ];
    }

    private static function slice_items($from, $to): array
    {
        $schema = self::schema();
        $keys = array_keys($schema);
        $from = array_search($from, $keys);
        $length = array_search($to, $keys) - $from + 1;
        return array_slice($schema, $from, $length);
    }

}

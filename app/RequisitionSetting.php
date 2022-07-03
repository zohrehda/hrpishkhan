<?php

namespace App;

use App\Classes\RequisitionItems;
use Illuminate\Database\Eloquent\Model;

class RequisitionSetting extends Model
{
    //
    protected $guarded = [];
    private $primary;

    //  private static $schema;

    public static function schema(): array
    {
        $initial = config('requisition_items');
        $schema = [];
        $i = 0;
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
    //    dd($primary);
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

}

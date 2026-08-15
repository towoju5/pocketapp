<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KycCustomField extends Model
{
    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'bool',
        'is_active' => 'bool',
    ];

    public const TYPES = [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'date' => 'Date',
        'select' => 'Dropdown',
        'file' => 'File upload',
        'checkbox' => 'Checkbox',
        'number' => 'Number',
    ];

    /** Option list for field_type=select, one per line in the admin form. */
    public function optionList(): array
    {
        return array_values(array_filter($this->options ?? []));
    }
}

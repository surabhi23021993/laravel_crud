<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

class PersonCommentType extends Model
{
    protected $fillable = ['name', 'active'];
    
    public static $validator = [
        'name' => 'required|string|max:200',
        'active' => 'boolean'
    ];
}

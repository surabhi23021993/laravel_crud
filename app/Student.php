<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;


class Student extends Model
{
    protected $table = 'students';

    protected $fillable = ['f_name','l_name','parent_name','mobile_no','standard','course', 'email'];

    private $rules = array(
        'f_name' => 'required',
        'l_name' => 'required',
        'parent_name' => 'required',
        'mobile_no' => 'required',
        'standard' => 'required',
        'course' => 'required',
        'email' => 'required|unique:students,email,',
    );

    private $errors;

    public function validate($data)
    {
        if ($this->id) {
            $this->rules['email'] .= $this->id;
        }

        $validator = Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors();
            return false;
        }
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

}

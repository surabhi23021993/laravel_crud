<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class StudentDocument extends Model
{
    //
    protected $fillable = [
        'file_name', 'real_file_name', 'student_id', 'path', 'timestamp',
    ];
    protected $table = 'student_documents';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    private $rules = array(
        'file_name.*' => 'required|mimes:jpeg,png,jpg,pdf|max:2000',
        'real_file_name.*' => 'required',
        'student_id.*' => 'required',
        'path.*' => 'required',
        'timestamp.*' => 'required',
    );
    private $errors;

    public function validate($data)
    {
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


    public function student()
    {
        return $this->belongsTo('App\Student', 'student_id');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class BackgroundChecklist extends Model
{
    //use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'mandatory', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

    protected $table = 'background_checklist';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    private $rules = array(
        'name' => 'required',
        'type' => 'required',
        'mandatory' => 'nullable',
        'status' => 'nullable',

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

    public function candidate_bg_documents()
    {
        return $this->hasMany('App\CandidatesChecklistDocs', 'bg_checklist_id');
    }
}

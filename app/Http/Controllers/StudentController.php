<?php

namespace App\Http\Controllers;


use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;


class StudentController extends BaseController
{
    /* Function to fetch student list */
    public function index(Request $request)
    {
        $page = $request->page;
        $limit = $request->limit;

        if (($page == null || $limit == null) || ($page == -1 || $limit == -1)) {
            $students = Student::paginate(50);
        } else {
            $students = Student::paginate($limit);
        }

        if ($students->first()) {
            return $this->dispatchResponse(200, "Student List", $students);
        } else {
            // return $this->dispatchResponse(404, "No Records Found!!");
            return response()->json(['status_code' => 404, 'message' => 'No Records Found!!']);
        }
    }

    /* Function to Get Student details by id */
    public function view($id)
    {
        $model = Student::find((int) $id);

        if ($model) {
            return $this->dispatchResponse(200, "Records Found...!!", $model);
        } else {
            return $this->dispatchResponse(400, 'No Records Found!!');
        }
    }

    /* Function to create students details */
    public function create()
    {
        $posted_data = Input::all();
        DB::beginTransaction();
        try {
            $student = new Student();

            if ($student->validate($posted_data)) {
                $model = Student::create($posted_data);
                
                DB::commit();
                if ($model) {
                    return $this->dispatchResponse(200, "Student details saved Successfully...!!", $model);
                }

            } else {
                DB::rollback();
                return $this->dispatchResponse(400, "Something went wrong.", $student->errors());
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /* Function to update Student by id */
    public function update($id)
    {
        $posted_data = Input::all();
        try {
            DB::beginTransaction();
            $model = Student::find((int) $id);

            if ($model->validate($posted_data)) {
                if ($model->update($posted_data)) {
                    DB::commit();
                    return $this->dispatchResponse(200, "Student details Updated Successfully...!!", $model);
                }
            } else {
                DB::rollback();
                return $this->dispatchResponse(400, "Something went wrong.", $model->errors());
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /* Function to delete by id */
    public function destroy($id)
	{
	    $data = Student::find($id);    	    

	    if($data != null && $data->delete()) {
	        return $this->dispatchResponse(200, "Student details Has Been Delete", $data);
	    }else {
	        return $this->dispatchResponse(404, "No record Found");
	    }
	}
}

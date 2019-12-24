<?php

namespace App\Http\Controllers;

use App\StudentDocument;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;

class StudentDocumentController extends BaseController
{
    
	/*
     * To save Filled background check pdf file form on server
     */
    public function uploadStudentDocument(Request $request)
    {
        $object = new StudentDocument();
        DB::beginTransaction();
        try {
            $fileArray = [];
            $posted_data = [];
            // print_r($request->file('file_name'));
            // die();
            // if($request->hasfile('file_name'))
            // {

            foreach ($request->file('file_name') as $file => $value) {
                $fullName = $value->getClientOriginalName();
                $fileName = explode('.', $fullName)[0];
                $ext = $value->getClientOriginalExtension();
                $fileArray['file_name'] = $request['student_name']  . '_' . $file . '_' . time() . '.' . $ext;

               
                $fileArray['real_file_name'] = $fullName;
                $fileArray['student_id'] = $request['student_id'];
                $fileArray['timestamp'] = $request['timestamp'];
                $destinationPath = public_path('/student_documents');

                $value->move($destinationPath, $fileArray['file_name']);
                // $file->move($destinationPath, $name);
                $fileArray['path'] = $destinationPath;
                $fileArray["created_at"] = new DateTime();
                $fileArray["updated_at"] = new DateTime();
                array_push($posted_data, $fileArray);

            }
            // return $posted_data;
            // unset($request['bg_name']);
            unset($request['student_name']);


            if ($object->validate($posted_data)) {
                $model = StudentDocument::insert($posted_data);
                DB::commit();
                if ($model) {
                    return response()->json(['status_code' => 200, 'message' => 'Document uploaded successfully', 'data' => $model]);
                } else {
                    return response()->json(['status_code' => 401, 'message' => 'Unable to upload files.']);
                }
            } else {
                DB::rollback();
                return response()->json(['status_code' => 404, 'message' => 'Something went wrong.', 'error' => $object->errors()]);
            }
            // }else{
            //     DB::rollback();
            //     return response()->json(['status_code' => 404, 'message' => 'Please Select atleast one file.']);
            // }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}

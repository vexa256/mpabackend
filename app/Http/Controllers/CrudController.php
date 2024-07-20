<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    public function MassInsert(Request $request)
    {
        $tableName = $request->TableName;
        $tableColumns = Schema::getColumnListing($tableName);
        $data = $request->except(['_token', 'id', 'TableName']);
        $rules = $this->buildValidationRules($request, $tableColumns);
        $uploadedFiles = [];

        // Validate request data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Process form data
        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $uploadedFiles[$key] = $this->moveUploadedFile($request->file($key));
            }
        }

        // Insert data into the table
        try {
            $insertData = array_merge($data, $uploadedFiles);
            DB::table($tableName)->insert($insertData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to insert data ' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Data inserted successfully'], 201);
    }

    public function MassUpdate(Request $request)
    {
        $tableName = $request->TableName;
        $tableColumns = Schema::getColumnListing($tableName);
        $data = $request->except(['_token', 'id', 'TableName']);
        $rules = $this->buildValidationRules($request, $tableColumns);
        $uploadedFiles = [];

        // Validate request data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Process form data
        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $uploadedFiles[$key] = $this->moveUploadedFile($request->file($key));
            }
        }

        // Update data in the table
        try {
            $updateData = array_merge($data, $uploadedFiles);
            DB::table($tableName)->where('id', $request->id)->update($this->removeNullValues($updateData));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update data ' . $e->getMessage(),
                'errors' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Data updated successfully'], 200);
    }

    public function MassDelete($tableName, $id)
    {
        try {
            DB::table($tableName)->where('id', $id)->delete();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete data',
                'errors' => $e->getMessage(),
            ], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'The record was deleted successfully'], 200);
    }

    private function buildValidationRules(Request $request, $tableColumns)
    {
        $rules = [];
        foreach ($tableColumns as $column) {
            if ($request->hasFile($column)) {
                $rules[$column] = 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:80000';
            } else {
                $rules[$column] = 'nullable';
            }
        }
        return $rules;
    }

    private function moveUploadedFile($file)
    {
        if (!$file) {
            return null;
        }

        $destinationPath = public_path('assets/docs');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $fileName);

        return 'assets/docs/' . $fileName;
    }

    private function removeNullValues($array)
    {
        return array_filter($array, function ($value) {
            return !is_null($value);
        });
    }
}
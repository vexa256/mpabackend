<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppController extends Controller
{
    /**
     * Returns all columns of a table except the ones specified.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function RemovedColumns(Request $request)
    {
        $tableName = $request->TableName;
        $excludeColumns = $request->ExcludeColumns;

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table does not exist',
            ], 404);
        }

        $tableColumns = Schema::getColumnListing($tableName);
        $columnsToReturn = array_diff($tableColumns, $excludeColumns);

        return response()->json([
            'status' => 'success',
            'columns' => $columnsToReturn,
        ], 200);
    }

    /**
     * Returns all columns and data associated with the table.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function MassFetch(Request $request)
    {
        $tableName = $request->TableName;
        // $excludeColumns = $request->ExcludeColumns;

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table does not exist',
            ], 404);
        }

        $tableColumns = Schema::getColumnListing($tableName);
        // $columnsToReturn = array_diff($tableColumns, $excludeColumns);

        $data = DB::table($tableName)->select($tableColumns)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function FetchUpdateData(Request $request)
    {
        $tableName = $request->input('TableName');
        $recordId = $request->input('id');
        $excludeColumns = $request->input('ExcludeColumns', []);

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table does not exist',
            ], 404);
        }

        $tableColumns = Schema::getColumnListing($tableName);
        $columnsToReturn = array_diff($tableColumns, $excludeColumns);

        $data = DB::table($tableName)->select($columnsToReturn)->find($recordId);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function FetchSpecificData(Request $request)
    {
        $tableName = $request->input('TableName');
        $columnName = $request->input('ColumnName');
        $value = $request->input('Value');

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table does not exist',
            ], 404);
        }

        if (!Schema::hasColumn($tableName, $columnName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Column does not exist',
            ], 404);
        }

        $data = DB::table($tableName)
            ->where($columnName, $value)
            ->get();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function FetchSpecificDataOneRecord(Request $request)
    {
        $tableName = $request->input('TableName');
        $columnName = $request->input('ColumnName');
        $value = $request->input('Value');

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table does not exist',
            ], 404);
        }

        if (!Schema::hasColumn($tableName, $columnName)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Column does not exist',
            ], 404);
        }

        $data = DB::table($tableName)
            ->where($columnName, $value)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function FetchIndicators(Request $request)
    {
        // $tableName = $request->input('TableName');
        // $columnName = $request->input('ColumnName');
        // $value = $request->input('Value');

        $data = DB::table('project_indicators AS I')
            ->join('entities AS E', 'E.EntityID', 'I.EntityID')
            ->where('E.EntityID', $request->EntityID)
            ->select('E.Entity', 'E.EntityID', 'I.*')
            ->get();

        $Entity = DB::table('entities')
            ->where('EntityID', $request->EntityID)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'Entity' => $Entity->Entity,
        ], 200);
    }

    public function FetchUpdateIndicators(Request $request)
    {
        // $tableName = $request->input('TableName');
        // $columnName = $request->input('ColumnName');
        // $value = $request->input('Value');

        $data = DB::table('project_indicators AS I')
            ->join('entities AS E', 'E.EntityID', 'I.EntityID')
            ->where('E.id', $request->id)
            ->get();

        $Entity = DB::table('entities')
            ->where('EntityID', $request->EntityID)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'Entity' => $Entity->Entity,
        ], 200);
    }

}
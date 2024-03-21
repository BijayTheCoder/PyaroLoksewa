<?php

namespace App\Http\Controllers\Importers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelController extends Controller
{
    public function importCategories(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        // dd($file);
        Excel::import(new CategoryImport, $request->file('file'));
        return back()->with('massage', 'Categories Imported Successfully');
    }

    public function importServices(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        // dd($file);
        Excel::import(new ServiceImport, $request->file('file'));
        return back()->with('massage', 'Services Imported Successfully');
    }

    public function importLevels(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        // dd($file);
        Excel::import(new LevelImport, $request->file('file'));
        return back()->with('massage', 'Levels Imported Successfully');
    }

    public function importGroups(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        // dd($file);
        Excel::import(new GroupImport, $request->file('file'));
        return back()->with('massage', 'Levels Imported Successfully');
    }
    
    public function importPositions(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $file = $request->file('file');
        // dd($file);
        Excel::import(new PositionImport, $request->file('file'));
        return back()->with('massage', 'Positions Imported Successfully');
    }
}

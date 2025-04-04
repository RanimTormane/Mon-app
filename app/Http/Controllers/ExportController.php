<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{


    
    public function exportDashboardCsv(Request $request)
{
    $html = $request->input('html_content');
    $textContent = strip_tags($html);
    $lines = preg_split('/\r\n|\r|\n/', $textContent);
    $csvData = array_map(function($line) {
        return [trim(preg_replace('/\s+/', ' ', $line))];
    }, $lines);

    $callback = function() use ($csvData) {
        $file = fopen('php://output', 'w');
        foreach ($csvData as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="dashboard-export.csv"'
    ]);
}
}
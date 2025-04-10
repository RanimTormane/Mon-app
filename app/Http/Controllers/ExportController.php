<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Writer;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportDashboardCsv(Request $request)
    {
        $data = [
            ['Column 1', 'Column 2'],
            ['Value 1', 'Value 2'],
            ['Value 3', 'Value 4'],
        ];

        // Créer un fichier temporaire
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertAll($data);

        // Retourner la réponse HTTP avec le bon header pour forcer le téléchargement
        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="dashboard_data.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

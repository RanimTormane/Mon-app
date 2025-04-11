<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use League\Csv\Writer;
use Barryvdh\DomPDF\Facade as PDF;


use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

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
  
   // Dans votre contrôleur Laravel
public function generatePdf(Request $request)
{
    $validated = $request->validate([
        'html' => 'required|string',
        'charts' => 'required|array',
        'charts.*.id' => 'required|string',
        'charts.*.data' => 'required|string', // base64 image
        'charts.*.width' => 'sometimes|integer',
        'charts.*.height' => 'sometimes|integer',
    ]);

    // Remplacer les placeholders dans le HTML par les images
    $html = $validated['html'];
    foreach ($validated['charts'] as $chart) {
        $imgTag = sprintf(
            '<img src="%s" width="%d" height="%d" />',
            $chart['data'],
            $chart['width'] ?? 600,
            $chart['height'] ?? 400
        );
        $html = str_replace($chart['id'], $imgTag, $html);
    }

    // Générer le PDF
    $pdf = PDF::loadHTML($html);
    return response($pdf->output(), 200, [
        'Content-Type' => 'application/pdf',
    ]);
}}
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
  
    public function generatePdf(Request $request)
{
    try {
        // Valider les données du formulaire
        $validated = $request->validate([
            'html' => 'required|string',
            'charts' => 'required|array',
            'charts.*.id' => 'required|string',
            'charts.*.data' => 'required|string', // base64 image
            'charts.*.width' => 'sometimes|integer',
            'charts.*.height' => 'sometimes|integer',
        ]);

        // Log des informations une fois que la validation est réussie
        Log::info('HTML reçu : ' . $validated['html']);
        Log::info('Charts reçus : ' . print_r($validated['charts'], true));

        // Remplacer les placeholders dans le HTML par les images
        $html = $validated['html'];
        foreach ($validated['charts'] as $chart) {
            $imgTag = sprintf(
                '<img src="%s" width="%d" height="%d" />',
                $chart['data'],
                $chart['width'] ?? 600,
                $chart['height'] ?? 400
            );
            // Pour éviter une mauvaise substitution
            $html = str_replace($chart['id'], $imgTag, $html);
        }

        // Générer le PDF
        $pdf = Pdf::loadHTML($html);

        // Retourner le PDF généré
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="dashboard.pdf"');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Si la validation échoue, retourne une erreur claire
        return response()->json([
            'error' => 'Validation échouée',
            'messages' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        // Log erreur côté serveur
        Log::error('Erreur génération PDF: ' . $e->getMessage());

        // Retourner une réponse avec une erreur serveur
        return response()->json([
            'error' => 'Erreur serveur',
            'message' => $e->getMessage(),
        ], 500);
    }
}
}
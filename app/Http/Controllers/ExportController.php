<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{


    
    public function exportDashboardCsv(Request $request)
{
   
    $data = [
        ['Column 1', 'Column 2'], // En-têtes de colonnes
        ['Value 1', 'Value 2'],
        ['Value 3', 'Value 4'],
        // Ajoutez d'autres lignes de données ici
    ];

    // Créer un fichier temporaire en mémoire pour y écrire les données CSV
    $csv = Writer::createFromFileObject(new \SplTempFileObject());
    $csv->insertAll($data); // Insère toutes les données dans le fichier CSV

    // Convertir le contenu CSV en chaîne de caractères
    $csvContent = $csv->__toString();

    // Retourner le fichier CSV avec les bons en-têtes pour le téléchargement
    return response($csvContent, 200, [
        'Content-Type' => 'text/csv', // Déclare le type MIME du fichier
        'Content-Disposition' => 'attachment; filename="dashboard_data.csv"', // Déclare le nom du fichier téléchargé
        'Cache-Control' => 'no-cache, no-store, must-revalidate', // Empêche la mise en cache du fichier
        'Pragma' => 'no-cache', // Empêche la mise en cache
        'Expires' => '0', // Définit la date d'expiration à 0
    ]);
}}
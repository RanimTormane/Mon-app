<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
   public function chart(){
    $chart_options = [
        'chart_title' => 'Nombre de likes',
        'chart_type' => 'bar',
        'data' => [
            ['label' => '2025-03-01', 'value' => 100],
            ['label' => '2025-03-02', 'value' => 150],
            ['label' => '2025-03-03', 'value' => 200],
        ],
        'label' => 'Likes',
        'labels' => ['2025-03-01', '2025-03-02', '2025-03-03'],
    ];

    $chart = new LaravelChart($chart_options);

    return view('dashboard', compact('chart'));
}
   }


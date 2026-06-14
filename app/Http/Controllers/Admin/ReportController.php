<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sangh;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function show(Sangh $sangh): View
    {
        $sangh->load(['stoppages.serviceLogs', 'participants', 'volunteers']);

        $stats = [
            'registered' => $sangh->participants()->count(),
            'confirmed'  => $sangh->participants()->where('status', 'confirmed')->count(),
            'completed'  => $sangh->participants()->where('status', 'completed')->count(),
            'dropped'    => $sangh->participants()->where('status', 'dropped')->count(),
        ];

        $stoppageSummary = $sangh->stoppages->map(function ($stoppage) {
            return [
                'stoppage'  => $stoppage,
                'water'     => $stoppage->totalServicesCount('water'),
                'food'      => $stoppage->totalServicesCount('food'),
                'tea'       => $stoppage->totalServicesCount('tea'),
                'medical'   => $stoppage->totalServicesCount('medical'),
                'rest'      => $stoppage->totalServicesCount('rest'),
            ];
        });

        return view('admin.sangh.report', compact('sangh', 'stats', 'stoppageSummary'));
    }
}

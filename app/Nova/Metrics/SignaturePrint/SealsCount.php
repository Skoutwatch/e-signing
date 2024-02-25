<?php

namespace App\Nova\Metrics\SignaturePrint;

use App\Models\AppendPrint;
use App\Models\DocumentResourceTool;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class SealsCount extends Partition
{
    public function calculate(NovaRequest $request)
    {
        $prints = AppendPrint::withCount('tools')->get();

        return $this->result(
            $prints->flatMap(function ($print) {
                if ($print->type == 'NotaryDigitalSeal' || $print->type == 'NotaryTraditionalSeal') {
                    return [
                        $print->type.' - '.$print->category => DocumentResourceTool::where('append_print_id', $print->id)->count(),
                    ];
                }
            })->toArray()
        );
    }

    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
            'ALL' => 'All Time',
        ];
    }

    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}

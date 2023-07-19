<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeadsExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Lead::with(['project', 'comments', 'status', 'user', 'creator'])->get();
    }


    public function map($lead): array
    {
        $project = $lead->project ? $lead->project->title : '';
        return [
            $lead->id,
            $lead->external_id,
            $lead->name,
            $lead->phone_1,
            $lead->phone_2,
            $lead->lead_source,
            $lead->lead_sub_source,
            $lead->description,
            $lead->status->title,
            $lead->user->name,
            $lead->creator->name,
            $lead->deadline->toDateTimeString(),
            $lead->interested_in_our == 1 ? 'our units' : 'other units',
            $project,
            $lead->created_at->toDateTimeString()

        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'External ID',
            'Name',
            'Primary Number',
            'Secondary Number',
            'Lead Source',
            'Lead Sub Source',
            'Description',
            'Status',
            'User',
            'Created By',
            'Deadline',
            'Interested In',
            'Project',
            'Created At',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Increase cell width for column A (External ID)
            'B' => NumberFormat::FORMAT_TEXT, // Increase cell width for column B (Name)
            'C' => NumberFormat::FORMAT_TEXT, // Increase cell width for column C (Company Name)
            // Add more columns here and adjust their respective width
        ];
    }
}

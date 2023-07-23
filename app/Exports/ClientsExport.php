<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientsExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Client::with(['primaryContact', 'user'])->get();
    }


    public function map($client): array
    {
        $primaryContact = $client->primaryContact;
        return [
            $client->id,
            $primaryContact->name,
            $primaryContact->email,
            $primaryContact->primary_number,
            $primaryContact->secondary_number,
            $client->external_id,
            $client->client_number,
            // $client->company_name,
            // $client->vat,
            // $client->address,
            // $client->zipcode,
            // $client->city,
            // $client->industry->name,
            // $client->company_type,
            $client->user->name, // Replace user_id with user->name
            $client->created_at->toDateTimeString(), // Convert created_at to string datetime
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Primary Number',
            'Secondary Number',
            'External ID',
            'Client Number',
            // 'Company Name',
            // 'VAT',
            // 'Address',
            // 'Zipcode',
            // 'City',
            // 'Industry',
            // 'Company Type',
            'User',
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

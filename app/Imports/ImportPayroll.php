<?php

namespace App\Imports;

use App\Helpers\CustomHelper;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Jobs\QueueMail;
use App\Models\Payroll;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ImportPayroll implements ToModel,WithHeadingRow, WithValidation,WithBatchInserts,WithCalculatedFormulas
{
    public function model(array $row)
    {
        Payroll::createUser($row);
        $payroll = Payroll::create([
            'nik'                           => $row['nik'],
            'rekening_bca'                  => $row['rekening_bca'],
            'bulan'                         => $row['bulan'],
            'jabatan'                       => $row['jabatan'],
            'telepon'                       => $row['telepon'],
            'status'                        => $row['status'],
            'gaji_pokok'                    => $row['gaji_pokok'],
            'jumlah_lembur'                 => $row['jumlah_lembur'],
            'total_lembur'                  => $row['total_lembur'],
            'jumlah_potongan_terlambat'     => $row['jumlah_potongan_terlambat'],
            'total_potongan_terlambat'      => $row['total_potongan_terlambat'],
            'total_potongan_kehadiran'      => $row['total_potongan_kehadiran'],
            'total_potongan_pinjaman'       => $row['total_potongan_pinjaman'],
            'total_potongan_denda'          => $row['total_potongan_denda'],
            'total_potongan_bpjs'           => $row['total_potongan_bpjs'],
            'tunjangan_pengganti'           => $row['tunjangan_pengganti'],
            'jumlah_transfer'               => $row['jumlah_transfer'],
        ]);

        $data = [
            'subject'   => 'Slip Gaji - '.$row['bulan'],
            'view'      => 'mail.slip',
            'result'    => $payroll->toArray(),
            'user'      => $payroll->user->toArray(),
        ];
        
        QueueMail::dispatch($row['email'],$row['nama'],$data);
        if($row['telepon']){
            CustomHelper::sendWhatsapp($row['telepon'],'Selamat gaji anda bulan '.$row['bulan'].' telah ditransfer dan slip telah dikirimkan ke email anda. Pesan ini adalah pesan otomatis, jangan membalas atau mengirimkan pesan kembali. Terima kasih.');
        }
    }
    public function rules(): array
    {
        return [
            '*.nik'                         => 'required|string',
            '*.email'                       => 'required|string',
            '*.nama'                        => 'required|string',
            '*.rekening_bca'                => 'required|string',
            '*.bulan'                       => 'required|string',
            '*.jabatan'                     => 'required|string',
            '*.status'                      => 'required|string',
            '*.gaji_pokok'                  => 'required|numeric',
            '*.jumlah_lembur'               => 'required|numeric',
            '*.total_lembur'                => 'required|numeric',
            '*.jumlah_potongan_terlambat'   => 'required|numeric',
            '*.total_potongan_terlambat'    => 'required|numeric',
            '*.total_potongan_kehadiran'    => 'required|numeric',
            '*.total_potongan_pinjaman'     => 'required|numeric',
            '*.total_potongan_denda'        => 'required|numeric',
            '*.total_potongan_bpjs'         => 'required|numeric',
            '*.tunjangan_pengganti'         => 'required|numeric',
            '*.jumlah_transfer'             => 'required|numeric',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $errors = [];

        foreach ($failures as $failure) {
            $errors[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }

        throw new ValidationException(null, null, $errors);
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
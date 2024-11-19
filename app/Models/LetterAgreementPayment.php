<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterAgreementPayment extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'letter_agreement_payments';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'letter_agreement_id',
        'termin',
        'percentage',
        'type',
        'include_tax',
    ];

    public function includeTax(){
        $include_tax = match ($this->include_tax) {
            '1' => 'Include Pajak',
            default => 'Exclude Pajak',
        };

        return $include_tax;
    }

    public function getNominal(){
        $totalProject = $this->letterAgreement->project->cost;
        $nominal = round(($this->percentage / 100) * $totalProject,2);
        return $nominal;
    }

    public function letterAgreement(){
        return $this->belongsTo('App\Models\LetterAgreement','letter_agreement_id','id')->withTrashed();
    }

    public function type(){
        $type = match ($this->type) {
            '1' => 'nilai kontrak dibayarkan pada saat penandatanganan kontrak dan setelah diterimanya invoice.',
            '2' => 'nilai kontrak dibayarkan pada saat PIHAK KEDUA menyerahkan laporan Analisis Dampak Lalu Lintas yang kondisinya siap disidangkan ke instansi terkait dengan menyertakan Tanda Terima Berkas oleh Dinas terkait.',
            '3' => 'nilai kontrak dibayarkan saat pekerjaan sudah selesai dan surat rekomendasi Analisis Dampak Lalu Lintas yang diterbitkan instansi terkait sudah terbit.',
            default => 'Invalid',
        };

        return $type;
    }
}
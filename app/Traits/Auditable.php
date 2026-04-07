<?php

namespace App\Traits;

use App\Models\AuditLogs;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    // Method ini otomatis dipanggil saat Model di-booting oleh Laravel
    public static function bootAuditable() //static agar ketika mau menggunakannnya, ga perlu new Auditable gitu
    {
        // Mencatat saat data dibuat (Created)
        static::created(function ($model) {
            $model->logAudit('created', null, $model->getAttributes());
        });

        // Mencatat saat data diubah (Updated)
        static::updated(function ($model) { //model ada event dari suatu objek, yang dimana objek tersebut sudah memiliki polimorp auditable
            $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
            $newValues = $model->getChanges(); //mengambil apa yang berubah aja

            if (!empty($newValues)) { // kalo ada perubahan maka update panggil metode logaudit
                $model->logAudit('updated', $oldValues, $newValues);
            }
        });

        // Mencatat saat data dihapus (Deleted)
        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getAttributes(), null);
        });
    }

    /**
     * Fungsi Helper untuk menyimpan ke tabel audit_logs
     */
    protected function logAudit($event, $oldValues, $newValues)
    {
        AuditLogs::create([
            'user_id'        => Auth::id(), // sistem pusat yang mengendalikan user. mengambil id user yang lagi login
            'event'          => $event,
            'auditable_id'   => $this->id, //this id akan memanggil id tabel pada setiap logaudit dimanapun dia dipanggil
            'auditable_type' => get_class($this), //return class tempat metode logaudit ini dipanggil
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}
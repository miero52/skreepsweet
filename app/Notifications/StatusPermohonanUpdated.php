<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Permohonan;

class StatusPermohonanUpdated extends Notification
{
    use Queueable;

    protected $permohonan;

    public function __construct(Permohonan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusText = [
            'menunggu' => 'Menunggu Verifikasi',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak'
        ];

        $statusColor = [
            'menunggu' => '#ffc107',
            'diproses' => '#17a2b8',
            'selesai' => '#28a745',
            'ditolak' => '#dc3545'
        ];

        return (new MailMessage)
            ->subject('Update Status Permohonan - ' . $this->permohonan->nomor_permohonan)
            ->greeting('Assalamu\'alaikum ' . $notifiable->name . ',')
            ->line('Status permohonan Anda telah diupdate.')
            ->line('**Detail Permohonan:**')
            ->line('Nomor: ' . $this->permohonan->nomor_permohonan)
            ->line('Perihal: ' . $this->permohonan->perihal)
            ->line('Status: **' . $statusText[$this->permohonan->status] . '**')
            ->when($this->permohonan->catatan_petugas, function ($message) {
                return $message->line('**Catatan Petugas:**')
                    ->line($this->permohonan->catatan_petugas);
            })
            ->when($this->permohonan->status === 'selesai', function ($message) {
                return $message->action('Unduh Surat', url('/masyarakat/dashboard'));
            })
            ->when($this->permohonan->status !== 'selesai', function ($message) {
                return $message->action('Lihat Detail', url('/masyarakat/dashboard'));
            })
            ->line('Terima kasih telah menggunakan layanan SILAP.')
            ->salutation('Wassalamu\'alaikum,<br>Tim Kemenag Palembang');
    }

    public function toDatabase($notifiable)
    {
        return [
            'permohonan_id' => $this->permohonan->id,
            'nomor_permohonan' => $this->permohonan->nomor_permohonan,
            'status' => $this->permohonan->status,
            'message' => 'Status permohonan ' . $this->permohonan->nomor_permohonan . ' telah diupdate menjadi ' . $this->permohonan->status,
        ];
    }
}

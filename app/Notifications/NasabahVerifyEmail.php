<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail; // Extend class bawaan
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

// Extend VerifyEmail agar kita mewarisi fungsionalitas verifikasi
class NasabahVerifyEmail extends VerifyEmail
{
    use Queueable;

    // Custom Subject & Text
    public function toMail($notifiable)
    {
        // Generate URL Verifikasi (Sama seperti bawaan)
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Pengajuan Rekening - SiFunding') 
            ->greeting('Halo, Calon Nasabah BSI!') 
            ->line('Terima kasih telah melakukan pengajuan pembukaan rekening melalui SiFunding.')
            ->line('Mohon klik tombol di bawah ini untuk memverifikasi email Anda agar data pengajuan dapat diproses oleh petugas kami.')
            ->action('Verifikasi Email Saya', $verificationUrl)
            ->line('Jika Anda tidak merasa melakukan pengajuan ini, mohon abaikan email ini.')
            ->salutation('Wassalam, Tim SiFunding');
    }
}
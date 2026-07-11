<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(private readonly string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = sprintf(
            '%s/reset-password?token=%s&email=%s',
            rtrim(config('app.frontend_url'), '/'),
            $this->token,
            urlencode($notifiable->getEmailForPasswordReset())
        );

        return (new MailMessage())
            ->subject('Đặt lại mật khẩu - Chấm Công')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình.')
            ->action('Đặt lại mật khẩu', $url)
            ->line('Liên kết này sẽ hết hạn sau 60 phút.')
            ->line('Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.');
    }
}

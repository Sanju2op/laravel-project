<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class DocumentUploadedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Document  $document
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($document, $user)
    {
        $this->document = $document;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(Storage::url($this->document->file_path));

        return (new MailMessage)
                    ->subject('Document Uploaded and Classified Successfully')
                    ->greeting('Hello!')
                    ->line('Hello ' . $this->user->name . ',')
                    ->line('Your Folder Of documents has been uploaded and classified successfully.')
                    ->action('View Folder', $url)
                    ->line('Thank you for using our application!')
                    ->line('Regards,')
                    ->line('Laravel')
                    ->line('')
                    ->line("If you're having trouble clicking the \"View Folder\" button, copy and paste the URL below into your web browser:")
                    ->line($url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'document_id' => $this->document->id,
            'user_id' => $this->user->id,
        ];
    }
}

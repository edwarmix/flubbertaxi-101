<?php

namespace App\Notifications;

use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class RideStatusChanged extends Notification
{
    use Queueable;

    protected $ride;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ride $ride)
    {
        $this->ride = $ride;
    }

    /**
     * Get the notification's ride channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

    /**
     *
     * @param  mixed  $notifiable
     */
    public function toFcm($notifiable)
    {
        $this->uploadRepository = new \App\Repositories\UploadRepository(app());
        $upload = $this->uploadRepository->getByUuid(setting('app_logo', ''));
        $appLogo = asset('img/logo_default.png');
        if ($upload && $upload->hasMedia('default')) {
            $appLogo = $upload->getFirstMediaUrl('default');
        }
        $message = new FcmMessage();
        $notification = [
            'title'        => __('Ride #:ride_id status changed for :status', ['ride_id' => $this->ride['id'], 'status' => trans('general.ride_status_list.' . $this->ride['ride_status'])]),
            'body'         => __('Ride status changed for :status', ['status' => trans('general.ride_status_list.' . $this->ride['ride_status'])]),
            'sound'        => 'default',
            'icon'         => $appLogo,
        ];
        return $message->content($notification)->data($notification)->priority(FcmMessage::PRIORITY_HIGH);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ride_id' => $this->ride['id'],
        ];
    }
}

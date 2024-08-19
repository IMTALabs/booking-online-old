<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-booking-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */

     public function handle()
     {
         $now = now();
         $fifteenMinutesLater = $now->copy()->addMinutes(15);

         Log::info('Current time: ' . $now->format('H:i:s'));

         $bookings = Booking::where('status', 'confirmed')
             ->whereDate('day', $now->toDateString())
             ->whereTime('time', '=', $fifteenMinutesLater->toTimeString())
             ->with(['user.storeInformation', 'bases'])
             ->get();

         Log::info('Found ' . $bookings->count() . ' bookings for sending email.');

         foreach ($bookings as $booking) {
             Log::info('Processing booking ID: ' . $booking->id);
             if ($booking->user && $booking->bases) {
                 foreach ($booking->bases as $base) {
                     Log::info('Sending email to: ' . $booking->user->email);
                     Mail::to($booking->user->email)->send(new AppointmentReminder($booking));
                 }
             }
         }

         $this->info('Emails have been sent successfully.');
     }


}

<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Request;
use DateInterval;
use DateTime;
use Src\Models\Reservation;

class ReservationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show_planning()
    {

        $days_offset = [
            'Mon' => 0,
            'Tue' => 1,
            'Wed' => 2,
            'Thu' => 3,
            'Fri' => 4,
            'Sat' => 5,
            'Sun' => 6,
        ];

        $slot_start = 8;
        $slot_end = 19;
        $slots = [];

        for ($i = $slot_start; $i < $slot_end; $i++) {
            $slots[] = "$i - " . $i + 1;
        }
        $date = new DateTime();
        $offset = $days_offset[$date->format('D')];
        $monday = $date->sub(DateInterval::createFromDateString("$offset days"));
        $days = [$monday];

        foreach ($days_offset as $day => $index) {
            if ($day !== 'Mon') {
                $date = new DateTime($monday->format("Y-m-d"));
                $date->add(DateInterval::createFromDateString("$index days"));
                $days[] = $date;
            }
        }
        // $tuesday = new DateTime($date)->sub()
        // $monday = $date->sub(new DateInterval('P2D'));
        // $monday = $monday->sub(DateInterval::createFromDateString("$offset days"));

        // var_dump($date->format("l d, M Y"));
        // var_dump($offset);
        // var_dump($days);
        $reservations = Reservation::get_week_reservation();
        var_dump(value: $reservations);
        $reservations_array = [];
        foreach ($reservations as  $reservation) {
            $start = new DateTime($reservation->start);
            $day = $start->format('D');
            $hour = $start->format('h');
            var_dump($day);
            var_dump($hour);
            $reservations_array[$day][(string)$hour] = $reservation;

            // $reservation_array
        }
        var_dump($reservations_array['Sat'][12]);
        $this->render_with_layout('reservation/planning', ['days' => $days, 'slots' => $slots, 'reservations' => $reservations_array]);
    }

    public function reserve(Request $request)
    {
        if ($request->method === 'POST') {
            $reservation = new Reservation();
        }
        $this->render_with_layout('reservation/reserve-form');
    }
}

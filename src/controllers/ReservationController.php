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

        $days_order = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $start_hour = 8;
        $end_hour = 19;
        $slots = [];

        for ($hour = $start_hour; $hour < $end_hour; $hour++) {
            $slots[$hour] = "$hour - " . $hour + 1 . "h";
        }

        $today = new DateTime();
        $today_day = $today->format('N');
        $monday = $today->modify("-" . ($today_day - 1) . " days");

        foreach ($days_order as $i => $day) {
            $days[$day] = (clone $monday)->modify("+ $i days");
        }

        $planning = [];
        foreach ($days_order as $day) {
            foreach (array_keys($slots) as $slot) {
                $planning[$day][$slot] = null;
            }
        }
        echo '<pre>';
        var_dump($planning);
        echo '</pre>';
        $reservations = Reservation::get_week_reservation();
        foreach ($reservations as $reservation) {
            $start_date = new DateTime($reservation->start);
            $end_date = new DateTime($reservation->end);
            $start_hour = $start_date->format('G');
            $end_hour = $end_date->format('G');
            $day = $start_date->format('D');

            for ($hour = $start_hour; $hour < $end_hour; $hour++) {
                $planning[$day][$hour] = $reservation;
            }
        }
        echo '<pre>';
        var_dump($planning);
        echo '</pre>';

        $this->render_with_layout('reservation/planning', [
            'days' => $days,
            'slots' => $slots,
            'planning' => $planning
        ]);
    }

    public function reserve(Request $request)
    {
        if ($request->method === 'POST') {
            $reservation = new Reservation();
        }
        $this->render_with_layout('reservation/reserve-form');
    }
}

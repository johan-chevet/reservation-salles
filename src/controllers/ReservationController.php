<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Request;
use Core\SessionManager;
use DateInterval;
use DateTime;
use Src\Models\Reservation;
use Src\Utils\ReservationUtils;

class ReservationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function show_planning()
    {

        $days_order = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        // $start_hour = 8;
        // $end_hour = 19;
        [$start_hour, $end_hour] = ReservationUtils::get_planning_hours();
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
            // $start_date = new DateTime($reservation->start);
            // $end_date = new DateTime($reservation->end);
            // $start_hour = $start_date->format('G');
            // $end_hour = $end_date->format('G');
            $start_hour = $reservation->start->format('G');
            $end_hour = $reservation->end->format('G');
            $day = $reservation->start->format('D');

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
        $errors = [];
        [$start_hour, $end_hour] = ReservationUtils::get_planning_hours();

        if ($request->method === 'POST') {
            $start = (int)$request->post['start'];
            $end = (int)$request->post['end'];
            $start_date = (new DateTime($request->post['date']))->setTime($start, 0);
            $end_date = (new DateTime($request->post['date']))->setTime($end, 0);
            $reservation = new Reservation();
            $reservation->title = $request->post['title'];
            $reservation->description = $request->post['description'];
            $reservation->start = $start_date;
            $reservation->end = $end_date;
            $reservation->user_id = SessionManager::get_user_id();
            if (!Reservation::is_slot_taken($start_date, $end_date)) {
                $reservation->save();
            } else {
                $errors['date'] = "Le créneau n'est pas disponible.";
            }
        }
        $this->render_with_layout('reservation/reserve-form', [
            'start_hour' => $start_hour,
            'end_hour' => $end_hour,
            'form' => $request->post,
            'errors' => $errors
        ]);
    }
}

<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\SessionManager;
use DateTime;
use Src\Models\Reservation;
use Src\Utils\ReservationUtils;

class ReservationController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function show_planning()
    {

        $days_order = ReservationUtils::get_days_order();
        // $start_hour = 8;
        // $end_hour = 19;
        [$start_hour, $end_hour] = ReservationUtils::get_planning_hours();
        $slots = [];

        for ($hour = $start_hour; $hour < $end_hour; $hour++) {
            $slots[$hour] = "$hour - " . $hour + 1 . "h";
        }

        $days = ReservationUtils::get_week_dates();

        $planning = [];
        foreach ($days_order as $day) {
            foreach (array_keys($slots) as $slot) {
                $planning[$day][$slot] = null;
            }
        }
        // echo '<pre>';
        // var_dump($planning);
        // echo '</pre>';
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
        // echo '<pre>';
        // var_dump($planning);
        // echo '</pre>';

        $response = $this->render_with_layout('reservation/planning', [
            'days' => $days,
            'slots' => $slots,
            'planning' => $planning
        ]);
        // var_dump($response->get_body());
        return $response;
    }

    public function reserve()
    {
        $errors = [];
        [$start_hour, $end_hour] = ReservationUtils::get_planning_hours();

        if ($this->request->method === 'POST') {
            $start = (int)$this->request->post['start'];
            $end = (int)$this->request->post['end'];
            $start_date = (new DateTime($this->request->post['date']))->setTime($start, 0);
            $end_date = (new DateTime($this->request->post['date']))->setTime($end, 0);
            $reservation = new Reservation();
            $reservation->title = $this->request->post['title'];
            $reservation->description = $this->request->post['description'];
            $reservation->start = $start_date;
            $reservation->end = $end_date;
            $reservation->user_id = SessionManager::get_user_id();
            if (Reservation::is_slot_taken($start_date, $end_date)) {
                $errors['date'] = "Le créneau n'est pas disponible.";
            }

            $day = $start_date->format('D');
            if ($day === 'Sat' || $day === 'Sun') {
                $errors['date'] = 'Impossible de réserver le week-end';
            }

            if (empty($errors)) {
                $reservation->save();
            }
        }
        return $this->render_with_layout('reservation/reserve-form', [
            'start_hour' => $start_hour,
            'end_hour' => $end_hour,
            'form' => $this->request->post,
            'errors' => $errors
        ]);
    }
}

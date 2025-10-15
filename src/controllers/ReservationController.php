<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\SessionManager;
use Core\Validator;
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

        $reservations = Reservation::get_week_reservation();
        foreach ($reservations as $reservation) {
            $start_hour = $reservation->start->format('G');
            $end_hour = $reservation->end->format('G');
            $day = $reservation->start->format('D');

            for ($hour = $start_hour; $hour < $end_hour; $hour++) {
                $planning[$day][$hour] = $reservation;
            }
        }

        return $this->render_with_layout('reservation/planning', [
            'days' => $days,
            'slots' => $slots,
            'planning' => $planning
        ]);
    }

    public function reserve()
    {
        $errors = [];
        [$start_hour, $end_hour] = ReservationUtils::get_planning_hours();

        if ($this->request->method === 'POST') {
            // $validator = new Validator($this->request->post);
            // $errors = $validator
            //     ->add('title')->required()
            //     ->add('date')->

            // $form_keys = ['title', 'date', 'start', 'end', 'description'];
            // $form = $this->request->post;
            // foreach ($form_keys as $key) {
            //     if (!isset($post[$key])) {
            //         $errors[$key] = "Le champ '$key' n'est pas défini";
            //         continue;
            //     }
            //     $value = $form[$key];
            //     $valid = match ($key) {
            //         'title' => !empty(trim($value))
            //     };
            // }
            $start = (int)$this->request->post['start'];
            $end = (int)$this->request->post['end'];
            if ($start >= $end) {
                $errors['end'] = 'Slot must be at least an hour';
            }
            $today = new Datetime();
            $start_date = (new DateTime($this->request->post['date']))->setTime($start, 0);
            $end_date = (clone $start_date)->setTime($end, 0);
            if ($today >= $start_date) {
                $errors['date'] = "Le créneau ne peut être assigner à une date antérieur.";
            }
            // $title =
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
        return $this->render_with_layout('reservation/reservation-form', [
            'start_hour' => $start_hour,
            'end_hour' => $end_hour,
            'form' => $this->request->post,
            'errors' => $errors
        ]);
    }
}

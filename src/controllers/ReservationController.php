<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Exception\ValidationException;
use Core\Http\Request;
use Core\SessionManager;
use Core\Validator;
use DateTime;
use Exception;
use PDOException;
use Src\Models\Reservation;
use Src\Models\User;
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
            $validator = new Validator($this->request->post);
            $errors = $validator
                ->add('title')
                ->required()
                ->add('date')
                ->required()
                ->is_date()
                ->add('start')
                ->is_int()
                ->range(8, 18, 'Le slot doit être commencer entre 8 et 18h')
                ->add('end')
                ->is_int()
                ->range(8, 18, 'Le slot doit être finir entre 9 et 19')
                ->validate();

            if (empty($errors)) {
                try {
                    $start = (int)$this->request->post['start'];
                    $end = (int)$this->request->post['end'];

                    if ($start >= $end) {
                        throw new ValidationException('end', 'Slot must be at least an hour');
                    }
                    $reservation = new Reservation();
                    $reservation->title = $this->request->post['title'];
                    $reservation->description = $this->request->post['description'] ?? '';
                    $today = new Datetime();
                    $start_date = (new DateTime($this->request->post['date']))->setTime($start, 0);

                    if ($today >= $start_date) {
                        throw new ValidationException('date', 'Le créneau ne peut être assigner à une date antérieur.');
                    }
                    $day = $start_date->format('D');

                    if ($day === 'Sat' || $day === 'Sun') {
                        throw new ValidationException('date', 'Impossible de réserver le week-end.');
                    }

                    $end_date = (clone $start_date)->setTime($end, 0);

                    if (Reservation::is_slot_taken($start_date, $end_date)) {
                        throw new ValidationException('date', "Le créneau n'est pas disponible.");
                    }

                    $reservation->start = $start_date;
                    $reservation->end = $end_date;
                    $reservation->user_id = SessionManager::get_user_id();
                    $reservation->save();
                } catch (ValidationException $e) {
                    [$key, $message] = $e->getKeyAndMessage();
                    $errors[$key] = $message;
                }
            }
        }
        return $this->render_with_layout('reservation/reservation-form', [
            'start_hour' => $start_hour,
            'end_hour' => $end_hour,
            'form' => $this->request->post,
            'errors' => $errors
        ]);
    }

    public function details(int $id)
    {
        $reservation = Reservation::find_by_id($id);
        if (!$reservation) {
            return $this->render('errors/404')->set_status(404);
        }
        $reservation->user = User::find_by_id($reservation->user_id);
        return $this->render_with_layout('reservation/details', ['reservation' => $reservation]);
    }
}

<?php

namespace Src\Models;

use Core\Database;
use Core\Model;
use DateTime;
use Src\Utils\ReservationUtils;

class Reservation extends Model
{
    public string $title;
    public string $description;
    public int $user_id;
    // public string $start;
    // public string $end;
    public DateTime $start;
    public DateTime $end;

    public ?User $user;

    public function __construct()
    {
        parent::__construct();
    }

    public static function get_week_reservation()
    {
        //TODO get start date of current week
        $days = ReservationUtils::get_week_dates();
        $monday = $days['Mon']->format(ReservationUtils::DATE_FORMAT);
        $sunday = $days['Sun']->format(ReservationUtils::DATE_FORMAT);
        $sql = "
            SELECT r.*, u.login 
            FROM reservations AS r 
            JOIN users AS u ON r.user_id = u.id
            WHERE start >= ? AND end <= ?
        ";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([$monday, $sunday]);
        $reservation_array = $stmt->fetchAll();
        $reservations = [];

        foreach ($reservation_array as $row) {
            $comment = new Reservation();
            $comment->id = $row['id'];
            $comment->title = $row['title'];
            $comment->description = $row['description'];
            $comment->start =  new DateTime($row['start']);
            $comment->end = new DateTime($row['end']);
            $comment->user_id = $row['user_id'];

            $user = new User();
            $user->id = $row['user_id'];
            $user->login = $row['login'];

            $comment->user = $user;

            $reservations[] = $comment;
        }
        return $reservations;
    }

    public static function is_slot_taken(DateTime $start_date, DateTime $end_date)
    {
        $sql = "SELECT id FROM reservations WHERE start >= ? AND end <= ?";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute([
            $start_date->format(ReservationUtils::DATE_FORMAT),
            $end_date->format(ReservationUtils::DATE_FORMAT)
        ]);
        $res = $stmt->fetchAll();
        return !empty($res);
    }
}

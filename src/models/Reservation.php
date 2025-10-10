<?php

namespace Src\Models;

use Core\Database;
use Core\Model;
use DateTime;

class Reservation extends Model
{
    public string $title;
    public string $description;
    public int $user_id;
    public string $start;
    public string $end;

    public ?User $user;

    public function __construct()
    {
        parent::__construct();
    }

    public static function get_week_reservation()
    {
        //TODO get start date of current week
        $sql = "
            SELECT r.*, u.login 
            FROM reservations AS r 
            JOIN users AS u ON r.user_id = u.id
        ";
        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute();
        $reservation_array = $stmt->fetchAll();
        $reservations = [];

        foreach ($reservation_array as $row) {
            $comment = new Reservation();
            $comment->id = $row['id'];
            $comment->title = $row['title'];
            $comment->description = $row['description'];
            $comment->start = $row['start'];
            $comment->end = $row['end'];
            $comment->user_id = $row['user_id'];

            $user = new User();
            $user->id = $row['user_id'];
            $user->login = $row['login'];

            $comment->user = $user;

            $reservations[] = $comment;
        }
        return $reservations;
    }
}

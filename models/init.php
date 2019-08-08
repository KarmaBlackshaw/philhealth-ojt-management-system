<?php

session_start();
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d H:i:s");
use Carbon\Carbon;

class init {
    protected $host = 'localhost';
    protected $username = 'root';
    protected $password = '';
    protected $database = 'ojt';

    private $conn;

    public function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            return $this->conn;
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
    public function inject($query) {
        $sql = mysqli_real_escape_string($this->conn, $query);
        $sql = trim($sql);
        return $sql;
    }
    public function checkKarma() {
        $count = $this->count("SELECT * FROM users WHERE name = 'Karma%Morningstar%Blackshaw' OR username = 'administrator' OR username = 'admin'");
        if ($count == 0) {
            $hash = $this->inject('$2y$10$w9x7uhNvJcg8U9BNoeQ4yOpNO7t0vXt5CqqM8XVpJPJRCuH3Zs0Va');
            $sql = $this->query("INSERT INTO users (user_id, name, office_id, position, username, password) VALUES(NULL, 'Karma%Morningstar%Blackshaw', '1', 'Admin', 'admin', '$hash')");
            if ($sql) {
                return true;
            }
        } elseif ($count > 1) {
            $remain = $count - 1;
            $sql = $this->query("DELETE FROM users WHERE username = 'admin' LIMIT $remain");
            if ($sql) {
                return true;
            }
        } else {
            return true;
        }
    }
    public function query($query) {
        $sql = $this->conn->query($query);
        return $sql;
    }
    public function count($query) {
        $sql = $this->query($query);
        $rows = $sql->num_rows;
        return $rows;
    }
    public function insert_id() {
        return $this->conn->insert_id;
    }
    public function getQuery($query) {
        $sql = $this->query($query);
        $array = [];
        while ($fetch = $sql->fetch_object()) {
            $array[] = $fetch;
        }
        return $array;
    }
    public function get($table) {
        $sql = $this->query("SELECT * FROM $table");
        $array = [];
        while ($fetch = $sql->fetch_object()) {
            $array[] = $fetch;
        }
        return $array;
    }
    public function error() {
        return $this->conn->error;
    }
    public function hash($password) {
        $hash = password_hash($password, CRYPT_BLOWFISH);
        return $hash;
    }
    
    function firstLetter($str) {
        $len = strlen($str);
        return substr($str, 0, 1) . str_repeat('*', $len - 2) . substr($str, $len - 1, 1);
    }

    function date($e) {
        $date = $e;
        $split = explode(' ', $date);
        $date = $split[0];
        return date('F d, Y', strtotime($date));
    }
    function time($e) {
        $date = $e;
        $split = explode(' ', $date);
        $time = $split[1];
        return date('h:i:s a', strtotime($time));
    }
    function audit($action, $trainee) {
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $sql = $this->query("INSERT INTO audit_trails VALUES (NULL, {$_SESSION['user_id']}, '$action', '$trainee', '$date')");
        return $sql;
    }
    function toHr($data) {
        if ($data == 0 || empty($data)) {
            return '';
        } else {
            $explode = explode(':', $data);
            return $explode[0];
        }
    }
    function toMin($data) {
        if ($data == 0 || empty($data)) {
            return '';
        } else {
            $explode = explode(':', $data);
            return $explode[1];
        }
    }
    
    function pad($num) {
        $new = str_pad($num, 2, 0, STR_PAD_LEFT);
        return $new;
    }
    function internName($id) {
        $intern = $this->getQuery("SELECT * FROM trainees WHERE trainee_id = '$id'");
        $name = "";
        foreach ($intern as $data) {
            $explode = explode('%', $data->name);
            foreach ($explode as $data) {
                $name.= $data . " ";
            }
        }
        return $name;
    }
    function toTime($time) {
        if ($time == 0) {
            return 0;
        } else {
            return date('H:i', mktime(0, $time));
        }
    }

    function isHoliday($date) {
        $sql = $this->count("SELECT * FROM holidays WHERE holidayDate = '$date' AND removed = 0");
        if ($sql > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function contain($needle, $haystack) {
        if (strpos($haystack, $needle) !== false) {
            return true;
        } else {
            return false;
        }
    }

    function acronym($var) {
        $explode = explode(' ', $var);
        $word = [];
        $letters = [];
        $uppercase = [];
        $acronym = '';
        foreach ($explode as $var) {
            array_push($word, $var);
        }
        foreach ($word as $var) {
            $first_letter = str_split($var);
            $letters[] = $first_letter[0];
        }
        foreach ($letters as $var) {
            if (ctype_upper($var)) {
                $uppercase[] = $var;
            }
        }
        foreach ($uppercase as $var) {
            $acronym.= $var;
        }
        return $acronym;
    }

    function addSuffix($num) {
        if (!in_array(($num % 100), array(11, 12, 13))) {
            switch ($num % 10) {
                case 1:
                    return $num . 'st';
                case 2:
                    return $num . 'nd';
                case 3:
                    return $num . 'rd';
            }
        }
        return $num . 'th';
    }
}
$init = new init;

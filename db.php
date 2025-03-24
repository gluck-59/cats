<?php
// представим что это типа модель в MVC
require_once 'config.php';

class Database extends Config {
    /**
     * добавить нового кота
     *
     * @param $fname
     * @param $birthDate
     * @param $gender
     * @param $father
     * @param $mother
     * @return true
     */
    public function insert($fname, $birthDate, $gender, $father, $mother) {
        $sql = 'INSERT INTO users (first_name, birthDate, gender, father, mother) VALUES (:fname, :birthDate, :gender, :father, :mother)';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fname' => $fname,
            'birthDate' => $birthDate,
            'gender' => (int) $gender,
            'father' => json_encode($father),
            'mother' => (int) $mother,
        ]);
        return true;
    }


    /**
     * получить всех
     *
     * @return array
     */

    public function read($order = false, $dir = 'asc') {
        $sql = 'SELECT *, TIMESTAMPDIFF(YEAR, birthDate, NOW()) age, JSON_LENGTH(father) qty FROM users ';
        if ($order) $sql .= 'ORDER BY '.$order .' '.$dir;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }


    /**
     * получить возможных родителей исключая самого себя
     *
     * @param $exclude
     * @return array
     */
    public function fetchParents($exclude = false) {
        $sql = 'SELECT id, first_name, gender FROM users ';
        if ($exclude) $sql .= 'WHERE id <> '.$exclude;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }


    /**
     * получить кота по его ID
     *
     * @param $id
     * @return mixed
     */
    public function readOne($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result;
    }


    /**
     * обновить кота по ID
     *
     * @param $id
     * @param $fname
     * @param $birthDate
     * @param $gender
     * @param $father
     * @param $mother
     * @return true
     */
    public function update($id, $fname, $birthDate, $gender, $father, $mother) {
        $sql = 'UPDATE users SET first_name = :fname, birthDate = :birthDate, gender = :gender, father = :father, mother = :mother WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fname' => $fname,
            'birthDate' => $birthDate,
            'gender' => (int) $gender,
            'father' => json_encode($father),
            'mother' => (int) $mother,
            'id' => $id
        ]);

        return true;
    }


    /**
     * удалить кота (плак-плак) по ID
     *
     * @param $id
     * @return true
     */
    public function delete($id) {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return true;
    }
}

?>
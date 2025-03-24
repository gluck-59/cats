<?php
// представьте что это типа контроллер в MVC, а все if isset это его методы

require_once 'db.php';
require_once 'util.php';

$db = new Database;
$util = new Util;


// method rest — post
if (isset($_POST['add'])) {
    $fname = $util->sanitize($_POST['fname']);
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];
    $father = $_POST['father'] ?? [];
    $mother = $_POST['mother'];

    if ($db->insert($fname, $birthDate, $gender, $father, $mother)) {
        echo $util->showMessage('success', 'Успешно');
    } else {
        echo $util->showMessage('danger', 'Что-то пошло не так');
    }
}



// method rest — get
// в реальном проекте я предпочитаю отдавать в клиента JSON, а HTML здесь для демонстрации "а что так можно было"
if (isset($_GET['read'])) {
    $users = $db->read($_GET['sorting'], $_GET['dir']);
    $output = '';
    $mothers = [];
    if ($users) {
        foreach ($users as $row) {
            $fathers = [];
            if (!is_null($row['father'])) {
                foreach (json_decode($row['father']) as $id) {
                    $fathers[] = $db->readOne($id)['first_name'];
                }
            }
            $output .= '<tr>
                <td>' . $row['first_name'] . '</td>
                <td>' . $row['age'] . '</td>
                <td>' . ($row['gender'] == 1 ? 'кот' : 'кошка') . '</td>
                <td>' . (!empty($fathers) ? implode(', ',$fathers): '-') . '</td>
                <td>' . ($row['mother'] > 0 ? $db->readOne($row['mother'])['first_name'] : '-') . '</td>
                <td>
                <a href="#" id="' . $row['id'] . '" class="btn btn-success btn-md py-0 editLink" data-bs-toggle="modal" data-bs-target="#editUserModal">Ред.</a>
                <a href="#" id="' . $row['id'] . '" class="btn btn-danger btn-md py-0 deleteLink">Удалить</a>
                </td>
            </tr>';
        }
        echo $output;
    } else {
        echo '<tr>
              <td colspan="7">Гладить некого</td>
            </tr>';
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['id'];

    $user = $db->readOne($id);
    echo json_encode($user);
}




// method rest — put/post
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $fname = $util->sanitize($_POST['fname']);
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];
    $father = $_POST['father'] ?? [];
    $mother = $_POST['mother'];


    if ($db->update($id, $fname, $birthDate, $gender, $father, $mother)) {
        echo $util->showMessage('success', 'Успешно');
    } else {
        echo $util->showMessage('danger', 'Что-то пошло не так');
    }
}



// method rest — delete
if (isset($_GET['delete'])) {
    $id = $_GET['id'];
    if ($db->delete($id)) {
        echo $util->showMessage('info', 'Успешно');
    } else {
        echo $util->showMessage('danger', 'Что-то пошло не так');
    }
}



// method rest — get
if (isset($_GET['fetchParents'])) {
    // exclude нужен чтобы никто не стал родителем самого себя
    $exclude = $util->sanitize($_GET['exclude']);

    $users = $db->fetchParents($exclude);
    $selected = $db->readOne($exclude);
    $mothers = [['id' => 0, 'first_name' => '-']]; // проще сделать эту заглушку здесь чем воевать с select2.js
    $fathers = [];
    foreach ($users as $user) {
        switch ($user['gender']) {
            case 1: $fathers[] = $user; break;
            case 2: $mothers[] = $user; break;

            default: $fathers[] = $user;
        }
    }
    $parents['fathers'] = $fathers;
    $parents['mothers'] = $mothers;
    $parents['selected'] = $selected;
    echo json_encode($parents);
}


function prettyDump($data = null, $die = false, $showStack = false)
{
//	if (in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', '::1', '0.0.0.0', 'localhost']))
    {
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        echo "<pre style='text-align: left;font-size: 14px;font-family: Courier, monospace; background-color: #f4f4f4; width: fit-content; opacity: .9; z-index: 999;position: relative;margin: 0 0 0 300px;'>";
        if ($showStack) print_r($stack);
        if ($stack[0]['function'] == 'prettyDump') {
            echo __FUNCTION__ . '() из ' . $stack[0]['file'] . ' line ' . $stack[0]['line'] . '<br>';
        } else {
//			print_r($stack);
            echo __FUNCTION__ . '() из ' . ($stack[1]['args'][0] ? $stack[1]['args'][0] : $stack[2]['file']) . ' строка ' . $stack[0]['line'] . ':<br>';
        }
        if (is_bool($data) || is_null($data) || empty($data)) var_dump($data);
        else print_r($data);
        echo "</pre>";
        if ($die) die;
    }
}


?>
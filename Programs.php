<?php
include("Header.html");
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Gymnastics Programs </h1>
        <br>
        <br>
        <form method="post">
            <table class="form-table">
                <tr>
                    <td><label for="id">Program ID(for update and delete):</label></td>
                    <td><input type="number" name="id" id="id"></td>
                </tr>
                <tr>
                    <td><label for="program_name">Program Name:</label></td>
                    <td><input type="text" name="program_name" id="program_name"></td>
                </tr>
                <tr>
                    <td><label for="description">Description:</label></td>
                    <td><textarea name="description" id="description"></textarea></td>
                </tr>
                <tr>
                    <td><label for="coach_name">Coach Name:</label></td>
                    <td><input type="text" name="coach_name" id="coach_name"></td>
                </tr>
                <tr>
                    <td><label for="email">Coach E-mail:</label></td>
                    <td><input type="email" name="email" id="email"></td>
                </tr>
                <tr>
                    <td><label for="duration">Duration(weeks):</label></td>
                    <td><input type="number" name="duration" id="duration"></td>
                </tr>
                 <tr>
                    <td><label for="skill">Skill Level:</label></td>
                    <td>
                        <select name="skill">
                            <option value="Select">Select</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <input type="submit" name="action" value="Add">
                        <input type="submit" name="action" value="Update">
                        <input type="submit" name="action" value="Delete">
                       <input type="submit" name="action" value="View">

                    </td>
                </tr>
                
               


            </table>
          
       
        </form>
    
</body>
</html>


<?php

$pdo = new PDO("mysql:host=localhost;port=3307;dbname=gymnastics_db",username: "root", password: "528_hloni");


function view_table($pdo): void{
    $stmt = $pdo->query("SELECT * FROM programs");
    echo "<table border='1'><tr><th>ID</th><th>Program Name</th><th>Description</th><th>Coach Name</th><th>Coach Email</th><th>Duration(weeks)</th><th>Skill Level</th>";
    foreach ($stmt as $row){
        echo "<tr>
                  <td>{$row['program_id']}</td>
                  <td>" . htmlentities($row['program_name']) . "</td>
                  <td>" . htmlentities($row['description']) . "</td>
                  <td>" . htmlentities($row['coach_name']) . "</td>
                  <td>{$row['coach_email']}</td>
                  <td>{$row['duration_weeks']}</td>
                  <td>" . htmlentities($row['skill_level']) . "</td>
             </tr>";
    }
    echo "</table><br>";   



    }


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $program_name = htmlentities(trim($_POST['program_name'] ?? ''));
    $description = htmlentities(trim($_POST['description'] ?? ''));
    $coach_name = htmlentities(trim($_POST['coach_name'] ?? ''));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $duration = filter_input(INPUT_POST, 'duration', FILTER_VALIDATE_INT);
    $skill = htmlentities(trim($_POST['skill'] ?? ''));

    if ($action === 'Add' && $program_name && $description && $coach_name && $email && $duration && $skill && $skill !== 'Select') {
        $stmt = $pdo->prepare("INSERT INTO programs (program_name,description,coach_name,coach_email,duration_weeks,skill_level) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$program_name, $description, $coach_name, $email, $duration, $skill]);
        echo "✅ Program added successfully!";
    } elseif ($action === 'Update' && $id && $program_name && $description && $coach_name && $email && $duration && $skill && $skill !== 'Select') {
        $stmt = $pdo->prepare("UPDATE programs SET program_name=?, description=?, coach_name=?, coach_email=?, duration_weeks=?, skill_level=? WHERE program_id=?");
        $stmt->execute([$program_name, $description, $coach_name, $email, $duration, $skill, $id]);
        echo "✅ Program updated successfully!";
    } elseif ($action === 'Delete' && $id) {
        $stmt = $pdo->prepare("DELETE FROM programs WHERE program_id=?");
        $stmt->execute([$id]);
        echo "✅ Program deleted successfully!";
    } elseif ($action === 'View') {
        view_table($pdo);
    } else {
        echo "⚠️ Please fill all fields correctly!";
    }
}





 



?>
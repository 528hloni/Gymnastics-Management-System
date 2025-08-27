<?php
include("Header.html");
?>



<?php 

$pdo = new PDO("mysql:host=localhost;port=3307;dbname=gymnastics_db",username: "root", password: "528_hloni");



// fetch programs
$sql = "SELECT program_id, program_name FROM programs";
$result = $pdo->query($sql);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   



    <h1>Enrol Gymnasts</h1>
    <br>
    <br>


     <form method="post">
            <table class="form-table">
                
                <tr>
                    <td><label for="name">Name:</label></td>
                    <td><input type="text" name="name" id="name"></td>
                </tr>
                <tr>
                    <td><label for="age">Age:</label></td>
                    <td><input type="number" name="age" id="age"></td>
                </tr>
                 <tr>
                    <td><label for="experience">Experience Level:</label></td>
                    <td>
                        <select name="experience">
                            <option value="Select">Select</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </td>
                </tr>
                 <tr>
                    <td><label for="program">Program:</label></td>
                    <td>
                        <select name="program_id">
                            <option value="">Select</option>
                            <?php
                            foreach ($result as $row) {
                                echo "<option value='" . $row['program_id'] . "'>" . htmlentities($row['program_name']) . "</option>";
                                
                                }
                            ?>
                           
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <input type="submit" name="action" value="Submit">
                    </td>    
                </tr>
               

              
                


            </table>
          
       
        </form>





</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    $name = htmlentities(trim($_POST['name'] ?? ''));
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $experience = htmlentities(trim($_POST['experience'] ?? ''));
    $program_id = filter_input(INPUT_POST,'program_id',FILTER_VALIDATE_INT);



if($action === 'Submit' && $name && $age && $experience && $experience !== 'Select' && $program_id && $program_id !== null){
    $stmt = $pdo->prepare("INSERT INTO gymnasts (name, age, experience_level, program_id) VALUES (?,?,?,?)");
    $stmt->execute([$name, $age, $experience, $program_id]);
    echo "Gymnast enrolled successfully!";

    } else {
        echo "⚠️ Please fill all fields correctly!";
    }
    
    

}







?>



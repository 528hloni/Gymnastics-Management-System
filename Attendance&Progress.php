

<?php
include("Header.html");
?>



<?php 
$pdo = new PDO("mysql:host=localhost;port=3307;dbname=gymnastics_db", "root", "528_hloni");
// fetch programs
$sql = "SELECT program_id, program_name, skill_level, duration_weeks FROM programs";
$programs = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>

</head>
<body>
    <h1>Mark Attendance</h1>
    <br><br>
    <form method="post">
        <table>
            <!-- Program Dropdown -->
            <tr>
                <td><label for="program">Program:</label></td>
                <td>
                    <select name="program_id" onchange="this.form.submit()">
                        <option value="">Select Program</option>
                        <?php
                        foreach ($programs as $row) {
                            $selected = (isset($_POST['program_id']) && $_POST['program_id'] == $row['program_id']) ? "selected" : "";
                            echo "<option value='" . $row['program_id'] . "' $selected>" 
                                . htmlentities($row['program_name']) . " (" . htmlentities($row['skill_level']) . ")</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            // If program is selected, show gymnasts + weeks
            if (isset($_POST['program_id']) && $_POST['program_id'] != "") {
                $program_id = (int) $_POST['program_id'];
                // fetch gymnasts in that program
                $stmt = $pdo->prepare("SELECT gymnast_id, name FROM gymnasts WHERE program_id = ?");
                $stmt->execute([$program_id]);
                $gymnasts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // fetch program duration
                $stmt2 = $pdo->prepare("SELECT duration_weeks FROM programs WHERE program_id = ?");
                $stmt2->execute([$program_id]);
                $program = $stmt2->fetch(PDO::FETCH_ASSOC);
                $weeks = $program ? $program['duration_weeks'] : 0;
            ?>
            <!-- Gymnast Dropdown -->
            <tr>
                <td><label for="gymnast">Gymnast:</label></td>
                <td>
                    <select name="gymnast_id">
                        <option value="">Select Gymnast</option>
                        <?php foreach ($gymnasts as $g) {
                            echo "<option value='" . $g['gymnast_id'] . "'>" . htmlentities($g['name']) . "</option>";
                        } ?>
                    </select>
                </td>
            </tr>
            <!-- Week Dropdown -->
            <tr>
                <td><label for="week">Week:</label></td>
                <td>
                    <select name="week_number">
                        <option value="">Select Week</option>
                        <?php for ($i=1; $i <= $weeks; $i++) {
                            echo "<option value='$i'>Week $i</option>";
                        } ?>
                    </select>
                </td>
            </tr>
            <!-- Search Button -->
            <tr>
                <td colspan="2"><br><input type="submit" name="action" value="Search"></td>
            </tr>
            <?php } ?>
        </table>
    </form>
    
    <!-- View Buttons -->
    <br><br>
    <form method="post">
        <input type="submit" name="action" value="View Coach Notes">
        <input type="submit" name="action" value="View Attendance History">
    </form>

    <?php
    // Show attendance table when Search button is pressed
    if (isset($_POST['action']) && $_POST['action'] == 'Search' && 
        isset($_POST['program_id']) && $_POST['gymnast_id'] && $_POST['week_number']) {
        
        $program_id = (int) $_POST['program_id'];
        $gymnast_id = (int) $_POST['gymnast_id'];
        $week_number = (int) $_POST['week_number'];
        
        // Get gymnast name for display
        $stmt = $pdo->prepare("SELECT name FROM gymnasts WHERE gymnast_id = ?");
        $stmt->execute([$gymnast_id]);
        $gymnast = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($gymnast) {
    ?>
            <br><hr><br>
            <h3>Attendance for: <?php echo htmlentities($gymnast['name']); ?> - Week <?php echo $week_number; ?></h3>
            
            <form method="post">
                <!-- Hidden fields to maintain context -->
                <input type="hidden" name="program_id" value="<?php echo $program_id; ?>">
                <input type="hidden" name="gymnast_id" value="<?php echo $gymnast_id; ?>">
                <input type="hidden" name="week_number" value="<?php echo $week_number; ?>">
                
                <table border="1">
                    <tr>
                        <th>Gymnast</th>
                        <th>Week</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                    <tr>
                        <td><?php echo htmlentities($gymnast['name']); ?></td>
                        <td>Week <?php echo $week_number; ?></td>
                        <td>
                            <label>
                                <input type="radio" name="status" value="present" required> Present
                            </label>
                            <br>
                            <label>
                                <input type="radio" name="status" value="absent" required> Absent
                            </label>
                        </td>
                        <td>
                            <textarea name="notes" placeholder="Enter notes here..."></textarea>
                        </td>
                    </tr>
                </table>
                
                <br>
                <input type="submit" name="action" value="Save Attendance">
            </form>
    <?php
        }
    }
    
    // Handle saving attendance 
    if (isset($_POST['action']) && $_POST['action'] == 'Save Attendance') {
        $program_id = (int) $_POST['program_id'];
        $gymnast_id = (int) $_POST['gymnast_id'];
        $week_number = (int) $_POST['week_number'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        
         $stmt = $pdo->prepare("INSERT INTO attendance (gymnast_id, program_id, week_number, status, notes) 
                             VALUES (?, ?, ?, ?, ?);
        //                     ON DUPLICATE KEY UPDATE status = ?, notes = ?");
         $stmt->execute([$gymnast_id, $program_id, $week_number, $status, $notes, $status, $notes]);
        
        echo "<div style='color: green; margin-top: 10px;'>Attendance saved successfully!</div>";
    }
    
    // Handle View Coach Notes
    if (isset($_POST['action']) && $_POST['action'] == 'View Coach Notes') {
        echo "<br><hr><br>";
        echo "<h3>Coach Notes</h3>";
        
        // Query to get all notes from attendance records
        $stmt = $pdo->prepare("
            SELECT g.name, p.program_name, a.week_number, a.notes 
            FROM attendance a
            JOIN gymnasts g ON a.gymnast_id = g.gymnast_id
            JOIN programs p ON a.program_id = p.program_id
            WHERE a.notes IS NOT NULL AND a.notes != ''
            ORDER BY p.program_name, g.name, a.week_number
        ");
        $stmt->execute();
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($notes) {
            echo "<table border='1'>";
            echo "<tr><th>Gymnast</th><th>Program</th><th>Week</th><th>Notes</th></tr>";
            foreach ($notes as $note) {
                echo "<tr>";
                echo "<td>" . htmlentities($note['name']) . "</td>";
                echo "<td>" . htmlentities($note['program_name']) . "</td>";
                echo "<td>Week " . $note['week_number'] . "</td>";
                echo "<td>" . htmlentities($note['notes']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No coach notes found.</p>";
        }
    }
    
    // Handle View Attendance History
    if (isset($_POST['action']) && $_POST['action'] == 'View Attendance History') {
        echo "<br><hr><br>";
        echo "<h3>Attendance History</h3>";
        
        // Query to get attendance statistics for each gymnast
        $stmt = $pdo->prepare("
            SELECT 
                g.name,
                p.program_name,
                p.duration_weeks,
                COUNT(CASE WHEN a.status = 'present' THEN 1 END) as sessions_attended,
                COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as sessions_missed,
                COUNT(a.status) as total_sessions_recorded
            FROM gymnasts g
            JOIN programs p ON g.program_id = p.program_id
            LEFT JOIN attendance a ON g.gymnast_id = a.gymnast_id
            GROUP BY g.gymnast_id, g.name, p.program_name, p.duration_weeks
            ORDER BY p.program_name, g.name
        ");
        $stmt->execute();
        $attendance_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($attendance_history) {
            echo "<table border='1'>";
            echo "<tr><th>Gymnast</th><th>Program</th><th>Duration (Weeks)</th><th>Sessions Attended</th><th>Sessions Missed</th><th>Progress to Complete</th></tr>";
            
            foreach ($attendance_history as $record) {
                $total_sessions = $record['sessions_attended'] + $record['sessions_missed'];
                $duration = $record['duration_weeks'];
                
                // Calculate progress percentage
                $progress_percentage = $duration > 0 ? round(($total_sessions / $duration) * 100) : 0;
                
                // Ensure progress doesn't exceed 100%
                if ($progress_percentage > 100) {
                    $progress_percentage = 100;
                }
                
                echo "<tr>";
                echo "<td>" . htmlentities($record['name']) . "</td>";
                echo "<td>" . htmlentities($record['program_name']) . "</td>";
                echo "<td>" . $record['duration_weeks'] . "</td>";
                echo "<td>" . $record['sessions_attended'] . "</td>";
                echo "<td>" . $record['sessions_missed'] . "</td>";
                echo "<td>";
                
                // Create progress bar using HTML
                echo "<div style='width: 200px; background-color: #f0f0f0; border: 1px solid #ccc;'>";
                echo "<div style='width: " . $progress_percentage . "%; background-color: #4CAF50; height: 20px; text-align: center; line-height: 20px; color: white;'>";
                echo $progress_percentage . "%";
                echo "</div>";
                echo "</div>";
                
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No attendance records found.</p>";
        }
    }
    ?>
</body>
</html>
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
                    <td><label for="id">Program ID:</label></td>
                    <td><input type="number" name="id" id="id"></td>
                </tr>
                <tr>
                    <td><label for="name">Program Name:</label></td>
                    <td><input type="text" name="name" id="name"></td>
                </tr>
                <tr>
                    <td><label for="description">Description:</label></td>
                    <td><textarea name="description" id="description"></textarea></td>
                </tr>
                <tr>
                    <td><label for="coach name">Coach Name:</label></td>
                    <td><input type="text" name="coach name" id="coach name"></td>
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
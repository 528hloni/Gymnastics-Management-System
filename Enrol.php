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
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </td>
                </tr>
                 <tr>
                    <td><label for="program">Program:</label></td>
                    <td>
                        <select name="program">
                           
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
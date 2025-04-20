<?php
require_once "config.php";
include ("session-checker.php");

$error_message = "";
$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_POST['btnsave']))
{
    if (!preg_match('/^\d{4}$/', $_POST['txtYearModel'])) {
        $error_message = "Year Model should be a 4-digit number.";
    } 
    else {
        $sql = "SELECT * FROM tblequipments WHERE AssetNumber = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtAssetNumber']);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 0){
                    $sql = "INSERT INTO tblequipments (AssetNumber, SerialNumber, Type, Manufacturer, YearModel, Description, Branch,
                    Department, Status, Createdby, DateCreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    if ($stmt = mysqli_prepare($link, $sql))
                    {
                        $status = 'WORKING';
                        $date = date("Y-m-d");
                        mysqli_stmt_bind_param($stmt, "sssssssssss", $_POST['txtAssetNumber'], $_POST['txtSerialNumber'], 
                        $_POST['cmbtype'], $_POST['txtManufacturer'], $_POST['txtYearModel'], $_POST['txtDescription'],  
                        $_POST['cmbbranch'], $_POST['cmbDepartment'], $status, $_SESSION['username'], $date);

                        if (mysqli_stmt_execute($stmt)) {
                            $log_sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                            
                            if ($log_stmt = mysqli_prepare($link, $log_sql)) {
                                $datelog = date("Y-m-d");
                                $timelog = date("H:i:s");
                                $action = "CREATE";
                                $module = "Equipment";
                                $performedto = $_POST['txtAssetNumber'];
                                $performedby = $_SESSION['username'];
                                mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $performedto, $performedby);
                                mysqli_stmt_execute($log_stmt);
                            }

                            $_SESSION['success_message'] = "Equipment Successfully Added!";
                            header("Location: equipment-management.php");
                            exit();
                            
                        } 
                        else {
                            $error_message = "ERROR on adding new account.";
                        }
                    }
                } else {
                    $error_message = "Asset Number is already in use.";
                }
            }
        } else {
            $error_message = "ERROR on validating if asset number exists.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Equipment Page - AU Equipment Management System</title>
    <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group textarea {
            resize: vertical;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-left, .form-right {
            flex: 1;
            min-width: 300px;
        }

        .btn-primary {
            background-color: #007bff;
            padding: 6.2px 19px;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <script>
        window.onload = function() {
            var errorMessage = "<?php echo $error_message; ?>";
            if (errorMessage !== "") {
                alert(errorMessage);
            }
        };
    </script>

    <div class="container">
        <h1>Add New Equipment</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-row">
                <div class="form-left">
                    <div class="form-group">
                        <label for="txtAssetNumber">Asset Number:</label>
                        <input type="text" name="txtAssetNumber" id="txtAssetNumber" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtSerialNumber">Serial Number:</label>
                        <input type="text" name="txtSerialNumber" id="txtSerialNumber" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtManufacturer">Manufacturer:</label>
                        <input type="text" name="txtManufacturer" id="txtManufacturer" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="txtYearModel">Year Model:</label>
                        <input type="text" name="txtYearModel" id="txtYearModel" class="form-control" required pattern="\d{4}" title="Year Model should be a 4-digit number">
                    </div>
                </div>

                <div class="form-right">
                    <div class="form-group">
                        <label for="cmbtype">Equipment Type:</label>
                        <select name="cmbtype" id="cmbtype" class="form-control" required>
                            <option value="">--Select Equipment Type--</option>
                            <option value="MONITOR">MONITOR</option>
                            <option value="CPU">CPU</option>
                            <option value="KEYBOARD">KEYBOARD</option>
                            <option value="MOUSE">MOUSE</option>
                            <option value="AVR">AVR</option>
                            <option value="MAC">MAC</option>
                            <option value="PRINTER">PRINTER</option>
                            <option value="PROJECTOR">PROJECTOR</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cmbbranch">Branches:</label>
                        <select name="cmbbranch" id="cmbbranch" class="form-control" required>
                            <option value="">--Select AU Branch--</option>
                            <option value="JUAN SUMULONG CAMPUS">JUAN SUMULONG CAMPUS</option>
                            <option value="JOSE RIZAL CAMPUS">JOSE RIZAL CAMPUS</option>
                            <option value="ELISA ESGUERRA CAMPUS">ELISA ESGUERRA CAMPUS</option>
                            <option value="ANDRES BONIFACIO CAMPUS">ANDRES BONIFACIO CAMPUS</option>
                            <option value="PLARIDEL CAMPUS">PLARIDEL CAMPUS</option>
                            <option value="APOLINARIO MABINI CAMPUS">APOLINARIO MABINI CAMPUS</option>
                            <option value="JOSE ABAD SANTOS CAMPUS">JOSE ABAD SANTOS CAMPUS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cmbDepartment">Departments:</label>
                        <select name="cmbDepartment" id="cmbDepartment" class="form-control" required>
                            <option value="">--Select AU College Department--</option>
                            <option value="COLLEGE OF ARTS AND SCIENCES">COLLEGE OF ARTS AND SCIENCES</option>
                            <option value="COLLEGE OF CRIMINAL JUSTICE">COLLEGE OF CRIMINAL JUSTICE</option>
                            <option value="COLLEGE OF ACCOUNTANCY">COLLEGE OF ACCOUNTANCY</option>
                            <option value="SCHOOL OF COMPUTER STUDIES">SCHOOL OF COMPUTER STUDIES</option>
                            <option value="SCHOOL OF BUSINESS ADMINISTRATION">SCHOOL OF BUSINESS ADMINISTRATION</option>
                            <option value="SCHOOL OF EDUCATION">SCHOOL OF EDUCATION</option>
                            <option value="SCHOOL OF LIBRARY SCIENCE">SCHOOL OF LIBRARY SCIENCE</option>
                            <option value="SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT">SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT</option>
                            <option value="SCHOOL OF NURSING">SCHOOL OF NURSING</option>
                            <option value="SCHOOL OF MIDWIFERY">SCHOOL OF MIDWIFERY</option>
                            <option value="SCHOOL OF PSYCHOLOGY">SCHOOL OF PSYCHOLOGY</option>
                            <option value="COLLEGE OF RADIOLOGIC TECHNOLOGY">COLLEGE OF RADIOLOGIC TECHNOLOGY</option>
                            <option value="COLLEGE OF PHARMACY">COLLEGE OF PHARMACY</option>
                            <option value="COLLEGE OF MEDICAL TECHNOLOGY">COLLEGE OF MEDICAL TECHNOLOGY</option>
                            <option value="COLLEGE OF PHYSICAL THERAPY">COLLEGE OF PHYSICAL THERAPY</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="txtDescription">Description:</label>
                        <textarea name="txtDescription" id="txtDescription" class="form-control" required></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group text-right mt-4">
                <input type="submit" name="btnsave" value="Save" class="btn btn-primary">
                <a href="equipment-management.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

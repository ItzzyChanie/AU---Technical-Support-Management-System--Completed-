<?php
require_once "config.php";
include ("session-checker.php");

$error_message = "";
$success_message = "";
$input_data = [
    'txtAssetNumber' => '',
    'txtSerialNumber' => '',
    'txtManufacturer' => '',
    'txtYearModel' => '',
    'txtDescription' => '',
    'cmbtype' => '',
    'cmbbranch' => '',
    'cmbDepartment' => ''
];

if (isset($_POST['btnsave'])) {
    $input_data = $_POST; // Preserve input data
    if (!preg_match('/^\d{4}$/', $_POST['txtYearModel'])) {
        $error_message = "Year Model should be a 4-digit number.";
    } else {
        $sql = "SELECT * FROM tblequipments WHERE AssetNumber = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtAssetNumber']);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 0) {
                    $sql = "INSERT INTO tblequipments (AssetNumber, SerialNumber, Type, Manufacturer, YearModel, Description, Branch,
                    Department, Status, Createdby, DateCreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    if ($stmt = mysqli_prepare($link, $sql)) {
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
                                $module = "Equipment Management";
                                $performedto = $_POST['txtAssetNumber'];
                                $performedby = $_SESSION['username'];
                                mysqli_stmt_bind_param($log_stmt, "ssssss", $datelog, $timelog, $action, $module, $performedto, $performedby);
                                mysqli_stmt_execute($log_stmt);
                            }

                            $success_message = "Equipment Successfully Added!";
                        } else {
                            $error_message = "ERROR on adding new equipment.";
                        }
                    }
                } else {
                    $error_message = "Asset Number already used.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
    <?php if (!empty($success_message)): ?>
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle"></i> Success</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0"><?php echo $success_message; ?></p>
                </div>
                <div class="modal-footer justify-content-end">
                    <a href="equipment-management.php" class="btn btn-success">Okay</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#successModal").modal("show");
        });
    </script>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel"><i class="fas fa-exclamation-circle"></i> Error</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0"><?php echo $error_message; ?></p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#errorModal").modal("show");
        });
    </script>
    <?php endif; ?>

    <div class="container">
        <h1>Add New Equipment</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-row">
                <div class="form-left">
                    <div class="form-group">
                        <label for="txtAssetNumber">Asset Number:</label>
                        <input type="text" name="txtAssetNumber" id="txtAssetNumber" class="form-control" value="<?php echo htmlspecialchars($input_data['txtAssetNumber']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="txtSerialNumber">Serial Number:</label>
                        <input type="text" name="txtSerialNumber" id="txtSerialNumber" class="form-control" value="<?php echo htmlspecialchars($input_data['txtSerialNumber']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="txtManufacturer">Manufacturer:</label>
                        <input type="text" name="txtManufacturer" id="txtManufacturer" class="form-control" value="<?php echo htmlspecialchars($input_data['txtManufacturer']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="txtYearModel">Year Model:</label>
                        <input type="text" name="txtYearModel" id="txtYearModel" class="form-control" value="<?php echo htmlspecialchars($input_data['txtYearModel']); ?>" required pattern="\d{4}" title="Year Model should be a 4-digit number">
                    </div>
                </div>

                <div class="form-right">
                    <div class="form-group">
                        <label for="cmbtype">Equipment Type:</label>
                        <select name="cmbtype" id="cmbtype" class="form-control" required>
                            <option value="">--Select Equipment Type--</option>
                            <option value="MONITOR" <?php if ($input_data['cmbtype'] == "MONITOR") echo "selected"; ?>>MONITOR</option>
                            <option value="CPU" <?php if ($input_data['cmbtype'] == "CPU") echo "selected"; ?>>CPU</option>
                            <option value="KEYBOARD" <?php if ($input_data['cmbtype'] == "KEYBOARD") echo "selected"; ?>>KEYBOARD</option>
                            <option value="MOUSE" <?php if ($input_data['cmbtype'] == "MOUSE") echo "selected"; ?>>MOUSE</option>
                            <option value="AVR" <?php if ($input_data['cmbtype'] == "AVR") echo "selected"; ?>>AVR</option>
                            <option value="MAC" <?php if ($input_data['cmbtype'] == "MAC") echo "selected"; ?>>MAC</option>
                            <option value="PRINTER" <?php if ($input_data['cmbtype'] == "PRINTER") echo "selected"; ?>>PRINTER</option>
                            <option value="PROJECTOR" <?php if ($input_data['cmbtype'] == "PROJECTOR") echo "selected"; ?>>PROJECTOR</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cmbbranch">Branches:</label>
                        <select name="cmbbranch" id="cmbbranch" class="form-control" required>
                            <option value="">--Select AU Branch--</option>
                            <option value="JUAN SUMULONG CAMPUS" <?php if ($input_data['cmbbranch'] == "JUAN SUMULONG CAMPUS") echo "selected"; ?>>JUAN SUMULONG CAMPUS</option>
                            <option value="JOSE RIZAL CAMPUS" <?php if ($input_data['cmbbranch'] == "JOSE RIZAL CAMPUS") echo "selected"; ?>>JOSE RIZAL CAMPUS</option>
                            <option value="ELISA ESGUERRA CAMPUS" <?php if ($input_data['cmbbranch'] == "ELISA ESGUERRA CAMPUS") echo "selected"; ?>>ELISA ESGUERRA CAMPUS</option>
                            <option value="ANDRES BONIFACIO CAMPUS" <?php if ($input_data['cmbbranch'] == "ANDRES BONIFACIO CAMPUS") echo "selected"; ?>>ANDRES BONIFACIO CAMPUS</option>
                            <option value="PLARIDEL CAMPUS" <?php if ($input_data['cmbbranch'] == "PLARIDEL CAMPUS") echo "selected"; ?>>PLARIDEL CAMPUS</option>
                            <option value="APOLINARIO MABINI CAMPUS" <?php if ($input_data['cmbbranch'] == "APOLINARIO MABINI CAMPUS") echo "selected"; ?>>APOLINARIO MABINI CAMPUS</option>
                            <option value="JOSE ABAD SANTOS CAMPUS" <?php if ($input_data['cmbbranch'] == "JOSE ABAD SANTOS CAMPUS") echo "selected"; ?>>JOSE ABAD SANTOS CAMPUS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cmbDepartment">Departments:</label>
                        <select name="cmbDepartment" id="cmbDepartment" class="form-control" required>
                            <option value="">--Select AU College Department--</option>
                            <option value="COLLEGE OF ARTS AND SCIENCES" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF ARTS AND SCIENCES") echo "selected"; ?>>COLLEGE OF ARTS AND SCIENCES</option>
                            <option value="COLLEGE OF CRIMINAL JUSTICE" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF CRIMINAL JUSTICE") echo "selected"; ?>>COLLEGE OF CRIMINAL JUSTICE</option>
                            <option value="COLLEGE OF ACCOUNTANCY" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF ACCOUNTANCY") echo "selected"; ?>>COLLEGE OF ACCOUNTANCY</option>
                            <option value="SCHOOL OF COMPUTER STUDIES" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF COMPUTER STUDIES") echo "selected"; ?>>SCHOOL OF COMPUTER STUDIES</option>
                            <option value="SCHOOL OF BUSINESS ADMINISTRATION" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF BUSINESS ADMINISTRATION") echo "selected"; ?>>SCHOOL OF BUSINESS ADMINISTRATION</option>
                            <option value="SCHOOL OF EDUCATION" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF EDUCATION") echo "selected"; ?>>SCHOOL OF EDUCATION</option>
                            <option value="SCHOOL OF LIBRARY SCIENCE" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF LIBRARY SCIENCE") echo "selected"; ?>>SCHOOL OF LIBRARY SCIENCE</option>
                            <option value="SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT") echo "selected"; ?>>SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT</option>
                            <option value="SCHOOL OF NURSING" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF NURSING") echo "selected"; ?>>SCHOOL OF NURSING</option>
                            <option value="SCHOOL OF MIDWIFERY" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF MIDWIFERY") echo "selected"; ?>>SCHOOL OF MIDWIFERY</option>
                            <option value="SCHOOL OF PSYCHOLOGY" <?php if ($input_data['cmbDepartment'] == "SCHOOL OF PSYCHOLOGY") echo "selected"; ?>>SCHOOL OF PSYCHOLOGY</option>
                            <option value="COLLEGE OF RADIOLOGIC TECHNOLOGY" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF RADIOLOGIC TECHNOLOGY") echo "selected"; ?>>COLLEGE OF RADIOLOGIC TECHNOLOGY</option>
                            <option value="COLLEGE OF PHARMACY" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF PHARMACY") echo "selected"; ?>>COLLEGE OF PHARMACY</option>
                            <option value="COLLEGE OF MEDICAL TECHNOLOGY" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF MEDICAL TECHNOLOGY") echo "selected"; ?>>COLLEGE OF MEDICAL TECHNOLOGY</option>
                            <option value="COLLEGE OF PHYSICAL THERAPY" <?php if ($input_data['cmbDepartment'] == "COLLEGE OF PHYSICAL THERAPY") echo "selected"; ?>>COLLEGE OF PHYSICAL THERAPY</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="txtDescription">Description:</label>
                        <textarea name="txtDescription" id="txtDescription" class="form-control" required><?php echo htmlspecialchars($input_data['txtDescription']); ?></textarea>
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

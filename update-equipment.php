<?php
// update-equipment.php
require_once "config.php";
include ("session-checker.php");

$current_page = basename($_SERVER['PHP_SELF']);

if (isset($_GET['AssetNumber'])) {
    $assetNumber = $_GET['AssetNumber'];

    if (isset($_POST['btnupdate'])) {
        // Validate Year Model
        if (!preg_match('/^\d{4}$/', $_POST['txtYearModel'])) {
            echo "<script>alert('Year Model should be a 4-digit number.');</script>";
        } else {
            // Corrected SQL without unintended backslashes
            $sql = "UPDATE tblequipments
                    SET SerialNumber = ?, Type = ?, Manufacturer = ?, YearModel = ?, Description = ?,
                        Branch = ?, Department = ?, Status = ?
                    WHERE AssetNumber = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssssss",
                    $_POST['txtSerialNumber'],
                    $_POST['cmbtype'],
                    $_POST['txtManufacturer'],
                    $_POST['txtYearModel'],
                    $_POST['txtDescription'],
                    $_POST['cmbbranch'],
                    $_POST['cmbDepartment'],
                    $_POST['cmbStatus'],
                    $assetNumber
                );

                if (mysqli_stmt_execute($stmt)) {
                    // Log the update action
                    $log_sql = "INSERT INTO tbllogs (datelog, timelog, action, module, performedto, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                    if ($log_stmt = mysqli_prepare($link, $log_sql)) {
                        $datelog     = date("Y-m-d");
                        $timelog     = date("H:i:s");
                        $action      = "UPDATE";
                        $module      = "Equipment Management";
                        $performedto = $assetNumber;
                        $performedby = $_SESSION['username'];
                        mysqli_stmt_bind_param(
                            $log_stmt,
                            "ssssss",
                            $datelog,
                            $timelog,
                            $action,
                            $module,
                            $performedto,
                            $performedby
                        );
                        mysqli_stmt_execute($log_stmt);
                    }

                    // Redirect to equipment-management.php, showing updated record on top
                    header("Location: equipment-management.php?success=true&updatedAsset=" . urlencode($assetNumber));
                    exit();
                } else {
                    echo "<script>alert('ERROR on updating equipment.');</script>";
                }
            } else {
                echo "<script>alert('ERROR preparing update statement.');</script>";
            }
        }
    } else {
        // Fetch existing record
        $sql = "SELECT * FROM tblequipments WHERE AssetNumber = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $assetNumber);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result);
                    $serialNumber = $row['SerialNumber'];
                    $type         = $row['Type'];
                    $manufacturer = $row['Manufacturer'];
                    $yearModel    = $row['YearModel'];
                    $description  = $row['Description'];
                    $branch       = $row['Branch'];
                    $department   = $row['Department'];
                    $status       = $row['Status'];
                } else {
                    echo "No record found.";
                }
            }
        }
    }
} else {
    header("location: equipment-management.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Equipment Page - AU Equipment Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center; 
            font-weight: bold; 
            font-size: 1.5em; 
            color: #007bff; 
            margin-bottom: 20px;
            position: relative;
            top: 23px;
        }

        .form-container {
            padding: 20px;
            margin: 50px auto;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            position: relative;/* Added to establish positioning context */
            height: 620px;
        }

        .form-container h1 {
            text-align: center;
            font-weight: bold;
            font-size: 1.5em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .form-group {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group select, .form-group textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 20px; /* Added margin to move it slightly down */
        }

        .form-left, .form-right {
            flex: 1;
        }

        .form-group.buttons {
            display: flex;
            justify-content: flex-end; /* Align buttons to the right */
            gap: 10px;
            margin-top: 20px;
            position: absolute; /* Position absolutely */
            right: 20px; /* Position from the right */
        }

        .form-group.buttons .btn-primary {
            background-color: #007bff; /* Default blue for Save button */
            color: #fff;
            padding: 5px 10px; /* Smaller padding for smaller buttons */
            font-size: 0.9rem; /* Smaller font size */
            border-radius: 4px;
            border: none;
            text-decoration: none;
            cursor: pointer;
            width: 100px; /* Fixed width for both buttons */
            text-align: center; /* Center-align text */
            position: relative;
            right: 110px;
        }
        .form-group.buttons .btn-secondary {
            background-color: #007bff; /* Default blue for Save button */
            color: #fff;
            padding: 5px 10px; /* Smaller padding for smaller buttons */
            font-size: 0.9rem; /* Smaller font size */
            border-radius: 4px;
            border: none;
            text-decoration: none;
            cursor: pointer;
            width: 100px; /* Fixed width for both buttons */
            text-align: center; /* Center-align text */
            position: relative;
            bottom: 42px;
        }

        .form-group.buttons .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .form-group.buttons .btn-secondary {
            background-color: #6c757d; /* Gray for Cancel button */
        }

        .form-group.buttons .btn-secondary:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }

        .status-group {
            display: flex;
            gap: 15px;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Update Equipment</h1> <!-- Moved inside the form-container -->
        <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?AssetNumber=" . $assetNumber; ?>" method = "POST">
            <div class = "form-row">
                <div class = "form-left">
                    <div class = "form-group">
                        <label for = "txtAssetNumber">Asset Number:</label>
                        <input type = "text" value = "<?php echo $assetNumber; ?>" disabled style = "width: 100%;" />
                    </div>

                    <div class = "form-group">
                        <label for = "txtSerialNumber">Serial Number:</label>
                        <input type = "text" name = "txtSerialNumber" value ="<?php echo $serialNumber; ?>" required style = "width: 100%;" />
                    </div>

                    <div class = "form-group">
                        <label for = "txtManufacturer">Manufacturer:</label>
                        <input type= "text" name = "txtManufacturer" value = "<?php echo $manufacturer; ?>" required style = "width: 100%;" />
                    </div>

                    <div class = "form-group">
                        <label for = "txtYearModel">Year Model:</label>
                        <input type = "text" name = "txtYearModel" value = "<?php echo $yearModel; ?>" required pattern = "\d{4}"
                        title = "Year Model should be a 4-digit number" style = "width: 100%;" />
                    </div>

                    <div class = "form-group">
                        <label>Status:</label>

                        <div class = "status-group">
                            <input type = "radio" name = "cmbStatus" value = "WORKING" <?php if ($status == "WORKING") echo "checked"; ?>> Working
                            <input type = "radio" name = "cmbStatus" value = "ON-REPAIR" <?php if ($status == "ON-REPAIR") echo "checked"; ?>> On-repair
                            <input type = "radio" name = "cmbStatus" value = "RETIRED" <?php if ($status == "RETIRED") echo "checked"; ?>> Retired
                        </div>
                    </div>
                </div>

                <div class = "form-right">
                    <div class = "form-group">
                        <label for = "cmbtype">Equipment Type:</label>

                        <select name = "cmbtype" id = "cmbtype" required style = "width: 100%;">
                            <option value = "MONITOR" <?php if ($type == "MONITOR") echo "selected"; ?>>MONITOR</option>
                            <option value = "CPU" <?php if ($type == "CPU") echo "selected"; ?>>CPU</option>
                            <option value = "KEYBOARD" <?php if ($type == "KEYBOARD") echo "selected"; ?>>KEYBOARD</option>
                            <option value = "MOUSE" <?php if ($type == "MOUSE") echo "selected"; ?>>MOUSE</option>
                            <option value = "AVR" <?php if ($type == "AVR") echo "selected"; ?>>AVR</option>
                            <option value = "MAC" <?php if ($type == "MAC") echo "selected"; ?>>MAC</option>
                            <option value = "PRINTER" <?php if ($type == "PRINTER") echo "selected"; ?>>PRINTER</option>
                            <option value = "PROJECTOR" <?php if ($type == "PROJECTOR") echo "selected"; ?>>PROJECTOR</option>
                        </select>
                    </div>

                    <div class = "form-group">
                        <label for = "cmbbranch">Branches:</label>

                        <select name = "cmbbranch" id = "cmbbranch" required style = "width: 100%;">
                            <option value = "JUAN SUMULONG CAMPUS" <?php if ($branch == "JUAN SUMULONG CAMPUS") echo "selected"; ?>>JUAN SUMULONG CAMPUS</option>
                            <option value = "JOSE RIZAL CAMPUS" <?php if ($branch == "JOSE RIZAL CAMPUS") echo "selected"; ?>>JOSE RIZAL CAMPUS</option>
                            <option value = "ELISA ESGUERRA CAMPUS" <?php if ($branch == "ELISA ESGUERRA CAMPUS") echo "selected"; ?>>ELISA ESGUERRA CAMPUS</option>
                            <option value = "ANDRES BONIFACIO CAMPUS" <?php if ($branch == "ANDRES BONIFACIO CAMPUS") echo "selected"; ?>>ANDRES BONIFACIO CAMPUS</option>
                            <option value = "PLARIDEL CAMPUS" <?php if ($branch == "PLARIDEL CAMPUS") echo "selected"; ?>>PLARIDEL CAMPUS</option>
                            <option value = "APOLINARIO MABINI CAMPUS" <?php if ($branch == "APOLINARIO MABINI CAMPUS") echo "selected"; ?>>APOLINARIO MABINI CAMPUS</option>
                            <option value = "JOSE ABAD SANTOS CAMPUS" <?php if ($branch == "JOSE ABAD SANTOS CAMPUS") echo "selected"; ?>>JOSE ABAD SANTOS CAMPUS</option>
                        </select>
                    </div>

                    <div class = "form-group">
                        <label for = "cmbDepartment">Departments:</label>
                        <select name = "cmbDepartment" id = "cmbDepartment" required style = "width: 100%;">
                            <option value = "COLLEGE OF ARTS AND SCIENCES" <?php if ($department == "COLLEGE OF ARTS AND SCIENCES") echo "selected"; ?>>COLLEGE OF ARTS AND SCIENCES</option>
                            <option value = "COLLEGE OF CRIMINAL JUSTICE" <?php if ($department == "COLLEGE OF CRIMINAL JUSTICE") echo "selected"; ?>>COLLEGE OF CRIMINAL JUSTICE</option>
                            <option value = "COLLEGE OF ACCOUNTANCY" <?php if ($department == "COLLEGE OF ACCOUNTANCY") echo "selected"; ?>>COLLEGE OF ACCOUNTANCY</option>
                            <option value = "SCHOOL OF COMPUTER STUDIES" <?php if ($department == "SCHOOL OF COMPUTER STUDIES") echo "selected"; ?>>SCHOOL OF COMPUTER STUDIES</option>
                            <option value = "SCHOOL OF BUSINESS ADMINISTRATION" <?php if ($department == "SCHOOL OF BUSINESS ADMINISTRATION") echo "selected"; ?>>SCHOOL OF BUSINESS ADMINISTRATION</option>
                            <option value = "SCHOOL OF EDUCATION" <?php if ($department == "SCHOOL OF EDUCATION") echo "selected"; ?>>SCHOOL OF EDUCATION</option>
                            <option value = "SCHOOL OF LIBRARY SCIENCE" <?php if ($department == "SCHOOL OF LIBRARY SCIENCE") echo "selected"; ?>>SCHOOL OF LIBRARY SCIENCE</option>
                            <option value = "SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT" <?php if ($department == "SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT") echo "selected"; ?>>SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT</option>
                            <option value = "SCHOOL OF NURSING" <?php if ($department == "SCHOOL OF NURSING") echo "selected"; ?>>SCHOOL OF NURSING</option>
                            <option value = "SCHOOL OF MIDWIFERY" <?php if ($department == "SCHOOL OF MIDWIFERY") echo "selected"; ?>>SCHOOL OF MIDWIFERY</option>
                            <option value = "SCHOOL OF PSYCHOLOGY" <?php if ($department == "SCHOOL OF PSYCHOLOGY") echo "selected"; ?>>SCHOOL OF PSYCHOLOGY</option>
                            <option value = "COLLEGE OF RADIOLOGIC TECHNOLOGY" <?php if ($department == "COLLEGE OF RADIOLOGIC TECHNOLOGY") echo "selected"; ?>>COLLEGE OF RADIOLOGIC TECHNOLOGY</option>
                            <option value = "COLLEGE OF PHARMACY" <?php if ($department == "COLLEGE OF PHARMACY") echo "selected"; ?>>COLLEGE OF PHARMACY</option>
                            <option value = "COLLEGE OF MEDICAL TECHNOLOGY" <?php if ($department == "COLLEGE OF MEDICAL TECHNOLOGY") echo "selected"; ?>>COLLEGE OF MEDICAL TECHNOLOGY</option>
                            <option value = "COLLEGE OF PHYSICAL THERAPY" <?php if ($department == "COLLEGE OF PHYSICAL THERAPY") echo "selected"; ?>>COLLEGE OF PHYSICAL THERAPY</option>
                        </select>
                    </div>

                    <div class = "form-group">
                        <label for = "txtDescription">Description:</label>
                        <textarea name = "txtDescription" style = "width: 100%; height: 100px; white-space: pre-wrap;"></textarea>
                    </div>
                </div>
            </div>

            <div class = "form-group buttons">
                <input type = "submit" name = "btnupdate" value = "Save" class = "btn btn-primary">
                <a href = "equipment-management.php" class = "btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Success Modal for Deletion -->
    <div class="modal fade" id="deleteSuccessModal" tabindex="-1" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white"> <!-- Green background for header -->
                    <h5 class="modal-title" id="deleteSuccessModalLabel">Successful!</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Equipment Deleted Successfully!</p>
                </div>
                <div class="modal-footer">
                    <a href="equipment-management.php" class="btn btn-success">Okay</a> <!-- Green "Okay" button -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Example script to trigger the modal (replace with actual logic as needed)
        function showDeleteSuccessModal() {
            var deleteSuccessModal = new bootstrap.Modal(document.getElementById('deleteSuccessModal'));
            deleteSuccessModal.show();
        }

        // Uncomment the line below to test the modal
        // showDeleteSuccessModal();
    </script>
</body>
</html>

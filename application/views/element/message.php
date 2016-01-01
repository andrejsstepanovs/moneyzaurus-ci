<?php
$messages = [
    'error'   => isset($errors)  ? $errors  : null,
    'success' => isset($success) ? $success : null
];

foreach ($messages as $type => $message) {
    if (!empty($message)) {
        ?>
        <div class="message <?php echo $type; ?>-message">
            <span><?php echo $message; ?></span>
            <span class="right message-button">x</span>
        </div>
        <?php
    }
}

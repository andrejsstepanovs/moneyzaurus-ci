<?php

if (!empty($errors)) {
	echo '<div class="message error-message">' . $errors . '</div>';
}

if (!empty($success)) {
	echo '<div class="message success-message">' . $success . '</div>';
}


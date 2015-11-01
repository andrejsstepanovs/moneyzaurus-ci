<?php

if (empty($errors)) {
	return;
}

echo '<div class="message error-message">' . $errors . '</div>';

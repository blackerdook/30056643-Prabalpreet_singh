<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
echo "logged_out"; // Send a response to indicate logout success

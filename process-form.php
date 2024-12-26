<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare data for Formspree
    $data = array(
        'name' => $name,
        'email' => $email,
        'message' => $message
    );
    $dataString = http_build_query($data);

    // Formspree endpoint URL
    $url = 'https://formspree.io/f/xwpebeor';

    // Send data to Formspree
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => $dataString,
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        // Handle error
        echo "There was a problem submitting the form.";
    } else {
        // Redirect or display success message
        echo "Thank you for your message!";
    }
} else {
    // Not a POST request
    echo "Invalid request.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="styles.css">
      <link rel="stylesheet" href="process-form.php">
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .feedback {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Contact Us</h1>
        <form id="contact-form" method="POST" action="https://formspree.io/f/xwpebeor">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
            <br>
            <button type="submit">Send</button>
            <div id="feedback" class="feedback"></div>
        </form>
    </div>
    
    <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var form = event.target;
            var feedback = document.getElementById('feedback');

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    feedback.textContent = 'Thank you for your message!';
                    feedback.style.color = 'green';
                    form.reset(); // Reset the form after successful submission
                } else {
                    feedback.textContent = 'There was a problem submitting your message.';
                    feedback.style.color = 'red';
                }
            })
            .catch(() => {
                feedback.textContent = 'There was a problem submitting your message.';
                feedback.style.color = 'red';
            });
        });
    </script>
</body>
</html>
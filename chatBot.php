<?php
include "studentname.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Chatbot with Selectable Questions</title>
  <style>
    #chat-output {
      height: 500px;
      overflow-y: auto;
      border: 1px solid #ccc;
      margin-bottom: 10px;
      padding: 10px;
    }
    .message {
      margin: 5px 0;
    }
    .user {
      text-align: center;
      background:hsl(186, 72.80%, 79.80%); /* Peach background */
            color: black; /* Dark text */
            padding: 10px 0; /* Thinner header */
            position: fixed center;
            top: 0;
            left: 50px;
            right: 0px;
            z-index: 1000;
            height:30px;
            border: none;
            color: white;
            font-size: 18px;
            letter-spacing: 1px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(128, 128, 128, 0.5), 0 0 25px rgba(128, 128, 128, 0.7);
            transition: background 0.3s ease, transform 0.2s ease;
    }
    .bot {
      text-align: center;
      background: #808080;
            border: none;
            color: white;
            font-size: 18px;
            letter-spacing: 1px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(128, 128, 128, 0.5), 0 0 25px rgba(128, 128, 128, 0.7);
            transition: background 0.3s ease, transform 0.2s ease;
    }
    .bubble {
      background-color: #f0f0f0;
      border: none;
      border-radius: 20px;
      padding: 10px 20px;
      margin: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    .bubble:hover {
      background-color: #dcdcdc;
    }
    #bubble-container {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
  </style>
</head>
<body>
  <div id="chat-output"></div>
  <div id="bubble-container"></div>

  <script>
    const qaData = [

     
      { question: "What qualifications are required?", answer: "We’re looking for passion, commitment, and relevant skills. What’s your area of expertise?" },
      { question: "How long does the internship last?", answer: "Internships usually last 3-6 months, depending on the role. What works best for you?" },
      { question: "Are internships paid?", answer: "Yes, our internships are paid! Compensation depends on the role and location." }
      // Add more questions and answers as needed
    ];

    const chatOutput = document.getElementById('chat-output');
    const bubbleContainer = document.getElementById('bubble-container');

    // Helper function to append a message to the chat output
    function appendMessage(text, className) {
      const messageElement = document.createElement('div');
      messageElement.textContent = text;
      messageElement.className = `message ${className}`;
      chatOutput.appendChild(messageElement);
      chatOutput.scrollTop = chatOutput.scrollHeight;
    }

    // Function to display selectable questions as bubbles
    function displayQuestions() {
      bubbleContainer.innerHTML = ''; // Clear previous bubbles

      qaData.forEach(qa => {
        const bubble = document.createElement('button');
        bubble.textContent = qa.question;
        bubble.className = 'bubble';
        bubble.addEventListener('click', () => {
          appendMessage(qa.question, 'user'); // Display user's selected question
          appendMessage(qa.answer, 'bot'); // Display the bot's response
         // Clear bubbles after selection
        });
        bubbleContainer.appendChild(bubble);
      });
    }

    // Display the questions when the page loads
    displayQuestions();


  </script>
</body></html>

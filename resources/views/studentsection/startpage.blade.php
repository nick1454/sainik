<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exam Instructions</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Body and container styles */
    body {
      background-color: #f8f9fa;
    }

    .instructions-container {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 30px;
      margin-top: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Instructions title */
    .instructions-title {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 20px;
      text-align: center;
    }

    /* Instruction list styling */
    .instruction-list {
      font-size: 1.1rem;
      line-height: 1.6;
    }

    /* Start button at the bottom */
    .start-exam-button {
      display: flex;
      justify-content: center;
      margin-top: 30px;
    }

    .start-exam-button button {
      font-size: 1.2rem;
      padding: 10px 30px;
      border-radius: 5px;
    }

  </style>
</head>
<body>

  <div class="container">
    <form action="{{ route('student.start.exam') }}" id="start-exam" method="post">
      @csrf
      <input type="hidden" name="start" value="1">
    </form>

    <div class="instructions-container">
      <!-- Instructions title -->
      <div class="instructions-title">
        Exam Instructions
      </div>

      <!-- Instructions content -->
        <div class="instruction-list">
            <li>Read each question carefully before answering.</li>
            <li>Ensure that you have a stable internet connection before starting the exam.</li>
            <li>Once the exam is started, you cannot pause or go back.</li>
            <li>Each question has four options, and only one is correct.</li>
            <li>Mark questions you want to review later by selecting the "Mark for Review" button.</li>
            <li>The exam timer will begin as soon as you press the "Start Exam" button.</li>
            <li>Do not refresh or close the browser window during the exam as it may lead to disqualification.</li>
            <li>Click the "Submit" button at the end of the exam to submit your responses.</li>
        </div>

        <!-- Start Exam button -->
        <div class="start-exam-button">
            <button class="btn btn-success" onclick="startExam()">Start Exam</button>
        </div>
    </div>
  </div>

  <script>
    function startExam() {
      document.getElementById("start-exam").submit();
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Multiple Exams</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Body and container styles */
    body {
      background-color: #f8f9fa;
    }

    .exam-selection-container {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 30px;
      margin-top: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Exams title */
    .selection-title {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 20px;
      text-align: center;
    }

    /* Cards for each exam */
    .exam-card {
      background-color: #e8f5e9;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      margin-bottom: 20px;
      position: relative;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .exam-card:hover {
      transform: scale(1.05);
    }

    .exam-card h5 {
      font-size: 1.25rem;
      font-weight: bold;
      color: #2e7d32;
    }

    /* Checkbox positioning */
    .exam-checkbox {
      position: absolute;
      top: 15px;
      left: 15px;
      transform: scale(1.5);
    }

    /* Start button */
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
    <div class="exam-selection-container">
      <!-- Selection title -->
      <div class="selection-title">
        Select Your Exams
      </div>
      <!-- Available exams (Cards) -->
      <form action="{{ route('student.exam') }}" method="post" id="exam-form">
        @csrf
        <input type="hidden" name="exam" id="exam">
      </form>
      <div class="row">
        @foreach ($examList as $value)
        <div class="col-md-4">
          <div class="exam-card" onclick="startSelectedExams({{ $value->id }})">
            <h5>{{ $value->name }}</h5>
            <p>Created Date: {{ $value->created_at }}</p>
            <p>Duration: 150 minutes</p>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Start Exam button -->
      <div class="start-exam-button">
        <button class="btn btn-success" id="startExamBtn" onclick="startSelectedExams()" disabled>Start Selected Exams</button>
      </div>
    </div>
  </div>

  <script>
    function startSelectedExams(id) {
        document.getElementById('exam').value = id;
        document.getElementById('exam-form').submit();
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

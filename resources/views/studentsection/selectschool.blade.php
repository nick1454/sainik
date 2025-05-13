<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>School Selection</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Body background color */
    body {
      background-color: #f8f9fa; /* Light grey background */
    }

    /* School cards */
    .school-card {
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
      cursor: pointer;
      height: 100%;
      border-radius: 15px; /* Rounded corners */
      background-color: #d4edda; /* Light green background */
      border: none; /* Remove default border */
    }

    /* Hover effect */
    .school-card:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Card title styling */
    .card-title {
      font-size: 1.5rem;
      font-weight: bold;
    }

    /* Center content vertically */
    .card-body {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    /* Card container styling */
    .card-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }

    /* Margin for each card */
    .card-col {
      margin-bottom: 1rem;
    }

    /* Heading styling */
    h1 {
      color: #007bff; /* Bright blue color for the heading */
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="text-center my-4">Select Your School</h1>
    <form action="{{ route('student.exam') }}" method="post" id="select-school">
      @csrf
    <div class="row card-container">
      <input type="hidden" name="school" id="school">
      @foreach ($schoolList as $value)
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 card-col">
        <div class="card school-card h-100" onclick="selectSchool('{{ $value }}')">
          <div class="card-body text-center">
            <h5 class="card-title">{{ strtoupper($value) }}</h5>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="text-center mt-4">
      <h3 id="selected-school">
        {{ session()->has('school') ? session('school') : 'No School Selected!!' }}
      </h3>
    </div>
    </form>
  </div>

  <script>
    function selectSchool(schoolName) {
      document.getElementById('selected-school').textContent = 'Selected School: ' + schoolName;
      document.getElementById('school').value=schoolName;
      document.getElementById('select-school').submit();
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

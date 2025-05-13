<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exam Page</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Global styles */
    body {
      background-color: #f8f9fa; /* Light grey background */
    }

    /* Student name section */
    .student-name {
      font-size: 1.2rem;
      font-weight: bold;
      margin-bottom: 10px;
    }

    /* Left section for question and options */
    .question-section {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .direction-line {
      font-size: 1.1rem;
      font-weight: bold;
      margin-bottom: 15px;
      color: #555;
    }

    .unseen-passage {
      background-color: #f1f1f1;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .question-title {
      font-size: 1.25rem;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .options {
      list-style-type: none;
      padding: 0;
    }

    .options li {
      margin-bottom: 10px;
    }

    .option-item {
      width: 25px;
      height: 25px;
      margin-right: 20px;   
    }

    /* Right section for question tiles */
    .tiles-section {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      background-color: #ffffff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .tile {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      color: white;
      margin: 5px;
      cursor: pointer;
    }

    /* Color coding for question tiles */
    .tile.unattempted {
      background-color: #fffa51; /* Grey for unattempted */
    }

    .tile.attempted {
      background-color: #28a745; /* Green for attempted */
    }

    .tile.review {
      background-color: #007bff; /* Blue for under review */
    }

    .tile.nottaken {
      background-color: #6c757d; /* Yellow for not answered */
    }

    /* Button section */
    .button-section {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .button-section button {
      width: 30%;
    }

    /* Responsive adjustments */
    .container-fluid {
      padding-top: 20px;
    }

    .tile:hover {
      transform: scale(1.1);
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Student name display -->
    <div class="row">
      <div class="col-md-4">
        <div class="student-name">Student: {{ auth()->user()->name }}</div>
      </div>
      <div class="col-md-4">
        <b id="exam-timer"></div>
      </div>
    </div>

    <div class="row">
      <!-- Left side: Question and Options -->
      <div class="col-md-6 mb-3">
        <div class="question-section">
          <!-- Direction line -->
          <div class="timer" id="timer"></div>
          <div class="direction-line">Please read the following passage carefully and answer the questions.</div>
          <div class="direction-line" id="direction"></div>
          <div class="direction-line" id="small-instruction"></div>
          <input type="hidden" id="question-id" value="{{$que->id}}">
          <!-- Unseen passage -->
          @if ($que)
          <div id="unseen-passage">
            {{ $que->unseen_passage }}
          </div>

          <!-- Question and options -->
          <h2 class="question-title">
            Question: 
            <span id="exam-question">
            @if ($que->que)
              {{ $que->que }}
            @else
              <img src="{{ asset('/storage/'.$que->quef) }}" alt="">
            @endif
            </span>
          </h2>
          <ul class="options">
            <li>
              <label for="option1">
                <input type="radio" name="option" class="option-item" value="a" id="o1">A. 
                <span id="option1">
                  @if ($que->o1f)
                    <img src="{{ asset('/storage/'.$que->o1f) }}" alt="">
                  @else
                    {{ $que->o1 }}
                  @endif
                </span>
              </label>
            </li>
            <li><label for="option2"><input type="radio" name="option" class="option-item" value="b" id="o2">B. <span id="option2">{{ $que->o2 }}</span></label></li>
            <li><label for="option3"><input type="radio" name="option" class="option-item" value="c" id="o3">C. <span id="option3">{{ $que->o3 }}</span></label></li>
            <li><label for="option4"><input type="radio" name="option" class="option-item" value="d" id="o4">D. <span id="option4">{{ $que->o4 }}</span></label></li>
          </ul>

          <!-- Button Section -->
          <div class="button-section">
            <button class="btn btn-primary" onclick="submitAnswer('under_review')" id="review">Mark for Review Later</button>
            <button class="btn btn-success" onclick="submitAnswer('submit')" id="next">Next</button>
            <button class="btn btn-danger" onclick="submitAnswer('finish')" id="submit">Submit</button>
          </div>
          @endif
        </div>
      </div>

      <!-- Right side: Question Tiles -->
      <div class="col-md-6">
        <div class="tiles-section">
          @foreach ($questions as $key => $value)
          <div class="tile {{$value->getAnswerStatus()}}" id="que-{{$value->id}}" onclick="fetchQuestion({{$value->id}})">{{ $key+1 }}</div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>

    window.onload = testTimer;

    async function submitAnswer(questionStatus) {
      console.log(questionStatus);
      var id = document.getElementById('question-id').value;
      var answer = '';
      var optiona = document.getElementById('o1').checked;
      var optionb = document.getElementById('o2').checked;
      var optionc = document.getElementById('o3').checked;
      var optiond = document.getElementById('o4').checked;

      var questionTile = document.getElementById('que-'+id);

      questionTile.classList.remove('unattempted');
      questionTile.classList.remove('attempted');
      questionTile.classList.remove('review');
      questionTile.classList.remove('nottaken');

      if (questionStatus == 'under_review') {
        questionTile.classList.add('review')
      }

      if (questionStatus == 'submit') {
        if (optiona || optionb || optionc || optiond) {
          questionTile.classList.add('attempted')
        } else {
          questionTile.classList.add('unattempted')
        }
      }

      if (optiona) {
        answer = 'a';
      }

      if (optionb) {
        answer = 'b';
      }

      if (optionc) {
        answer = 'c';
      }

      if (optiond) {
        answer = 'd';
      }
      console.log(optiona);
      if (questionStatus == 'finish') {
        var finish = confirm("Are You Sure You want to End Test?");
        if (finish) {
          var output = await fetch(
          '{{ route('student.submit.test') }}?status='+questionStatus+'&question_id='+id+'&answer='+answer
          ).then((res) => {
            return res.json();
          }).then((data) => {
            window.location.href = data.location;
          })
        }
        return;
      }

      var output = await fetch(
        '{{ route('student.fetch.question') }}?status='+questionStatus+'&question_id='+id+'&answer='+answer
      ).then((res) => {
        return res.json();
      }).then((data) => {
        console.log(data.que);

        if (data && data.test_finished) {

        }

        document.getElementById('direction').innerText = data.que.directions ? data.que.directions : '';
        document.getElementById('unseen-passage').innerText = data.que.unseen_passage ? data.que.unseen_passage : '';
        document.getElementById('small-instruction').innerText = data.que.small_instructions ? data.que.small_instructions : '';
        document.getElementById('question-id').value = data.que.id ? data.que.id : '';
        document.getElementById('exam-question').innerText = data.que.que ? data.que.que : '';

        if (data.que.unseen_passage) {
          document.getElementById('unseen-passage').classList.add('unseen-passage');
        } else {
          document.getElementById('unseen-passage').classList.remove('unseen-passage');
        }

        if (data.que.quef) {
            document.getElementById('exam-question').innerHTML = (data.que.que ? data.que.que : '') + ' <img src="/storage/'+data.que.quef+'" />';
        }

        document.getElementById('option1').innerText = data.que.o1 ? data.que.o1 : '';
        if (data.que.o1f) {
            document.getElementById('option1').innerHTML = (data.que.o1 ? data.que.o1 : '') + ' <img src="/storage/'+data.que.o1f+'" />';
        }

        document.getElementById('option2').innerText = data.que.o2 ? data.que.o2 : '';
        if (data.que.o2f) {
            document.getElementById('option2').innerHTML = (data.que.o2 ? data.que.o2 : '') + ' <img src="/storage/'+data.que.o2f+'" />';
        }

        document.getElementById('option3').innerText = data.que.o3 ? data.que.o3 : '';
        if (data.que.o3f) {
            document.getElementById('option3').innerHTML = (data.que.o3 ? data.que.o3 : '') + ' <img src="/storage/'+data.que.o3f+'" />';
        }

        document.getElementById('option4').innerText = data.que.o4 ? data.que.o4 : '';
        if (data.que.o4f) {
            document.getElementById('option4').innerHTML = (data.que.o4 ? data.que.o4 : '') + ' <img src="/storage/'+data.que.o4f+'" />';
        }

        document.getElementById('o1').checked = false;
        document.getElementById('o2').checked = false;
        document.getElementById('o3').checked = false;
        document.getElementById('o4').checked = false;
      });
    }

    async function fetchQuestion(id) {
      var output = await fetch(
        '{{ route('student.fetch.question') }}?question_id='+id
      ).then((res) => {
        return res.json();
      }).then((data) => {
        console.log(data.que);

        if (data && data.test_finished) {

        }

        if (data.que) {
          document.getElementById('direction').innerText = data.que.directions ? data.que.directions : '';
          document.getElementById('unseen-passage').innerText = data.que.unseen_passage ? data.que.unseen_passage : '';
          document.getElementById('small-instruction').innerText = data.que.small_instructions ? data.que.small_instructions : '';
          document.getElementById('question-id').value = data.que.id ? data.que.id : '';
          document.getElementById('exam-question').innerText = data.que.que ? data.que.que : '';

          if (data.que.unseen_passage) {
            document.getElementById('unseen-passage').classList.add('unseen-passage');
          } else {
            document.getElementById('unseen-passage').classList.remove('unseen-passage');
          }

          if (data.que.quef) {
              document.getElementById('exam-question').innerHTML = (data.que.que ? data.que.que : '') + ' <img src="/storage/'+data.que.quef+'" />';
          }

          document.getElementById('option1').innerText = data.que.o1 ? data.que.o1 : '';
          if (data.que.o1f) {
              document.getElementById('option1').innerHTML = (data.que.o1 ? data.que.o1 : '') + ' <img src="/storage/'+data.que.o1f+'" />';
          }
          
          document.getElementById('option2').innerText = data.que.o2 ? data.que.o2 : '';
          if (data.que.o2f) {
              document.getElementById('option2').innerHTML = (data.que.o2 ? data.que.o2 : '') + ' <img src="/storage/'+data.que.o2f+'" />';
          }

          document.getElementById('option3').innerText = data.que.o3 ? data.que.o3 : '';
          if (data.que.o3f) {
              document.getElementById('option3').innerHTML = (data.que.o3 ? data.que.o3 : '') + ' <img src="/storage/'+data.que.o3f+'" />';
          }

          document.getElementById('option4').innerText = data.que.o4 ? data.que.o4 : '';
          if (data.que.o4f) {
              document.getElementById('option4').innerHTML = (data.que.o4 ? data.que.o4 : '') + ' <img src="/storage/'+data.que.o4f+'" />';
          }

          document.getElementById('o1').checked = false;
          document.getElementById('o2').checked = false;
          document.getElementById('o3').checked = false;
          document.getElementById('o4').checked = false;
        }
      });
    }

    async function testTimer()
    {
        await fetch('{{ route("student.test.timer") }}')
        .then((res) => {
            return res.json();
        }).then((data) => {
            document.getElementById('exam-timer').innerText = data.time;
        });
    }

    setInterval(testTimer, 5000);
  </script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Exam Submission PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .question-block { margin-bottom: 25px; }
        .question { font-weight: bold; }
        .answer { margin-top: 5px; }

        ol, ul {
            margin: 0 0 10px 20px;
            padding: 0;
        }
        li {
            margin-bottom: 5px;
        }
        
    </style>
</head>
<body>
    <h1>Exam Submission</h1>

    @foreach ($questionAnswerList as $item)
        <div class="question-block">
            <div class="question">Q: {{ $item['question'] }}</div>
        <div class="answer">A: {{ $item['answer'] }}</div>
        </div>
    @endforeach

</body>
</html>
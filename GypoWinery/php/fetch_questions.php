<?php
include('config2.php'); 

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d, correct_option FROM quiz_questions ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $formattedQuestions = [];

    foreach ($questions as $question) {
        $options = [
            'A' => $question['option_a'],
            'B' => $question['option_b'],
            'C' => $question['option_c'],
            'D' => $question['option_d']
        ];

        $shuffledKeys = array_keys($options);
        shuffle($shuffledKeys);

        $shuffledOptions = [];
        $correctIndex = null;

        foreach ($shuffledKeys as $index => $key) {
            $shuffledOptions[$index] = $options[$key];
            if ($key === $question['correct_option']) {
                $correctIndex = $index;
            }
        }

        $formattedQuestions[] = [
            'question_text' => $question['question_text'],
            'options' => array_values($shuffledOptions),
            'correct' => $correctIndex
        ];
    }

    echo json_encode($formattedQuestions);
} catch (Exception $e) {
    echo json_encode(['error' => 'Hiba a kérdések lekérésekor: ' . $e->getMessage()]);
}
?>

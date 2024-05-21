<?php


//this was generated by chatgpt. Please let me know if it breaks any copyright rules.
class Player {
    public $rating;
    public $variance;

    public function __construct($rating = 1500, $variance = 400) {
        $this->rating = $rating;
        $this->variance = $variance;
    }
}

function update_ratings(&$playerA, &$playerB, $resultA) {
    $expectedA = expected_score($playerA->rating, $playerA->variance, $playerB->rating, $playerB->variance);
    $expectedB = 1 - $expectedA;

    $k = 32; // Sensitivity factor
    $playerA->rating = $playerA->rating + $k * ($resultA - $expectedA);
    $playerB->rating = $playerB->rating + $k * ((1 - $resultA) - $expectedB);

    $playerA->variance = adjust_variance($playerA->variance, $expectedA, $resultA);
    $playerB->variance = adjust_variance($playerB->variance, $expectedB, 1 - $resultA);
}

function expected_score($ratingA, $varianceA, $ratingB, $varianceB) {
    $combinedVariance = sqrt($varianceA * $varianceA + $varianceB * $varianceB);
    $ratingDifference = $ratingA - $ratingB;
    $expectedA = 1 / (1 + exp(-($ratingDifference) / $combinedVariance));

    return $expectedA;
}

function adjust_variance($currentVariance, $expected, $actual) {
    $learningRate = 1; // Control how quickly the variance changes
    $predictionError = abs($expected - $actual);

    $newVariance = $currentVariance * (1 + $learningRate * ($predictionError - 0.5));
    $newVariance = max(100, $newVariance);

    return $newVariance;
}

// Initialize players
$player1 = new Player();
$player2 = new Player();
$player3 = new Player();
$player4 = new Player();

// Simulate a match where player1 wins
update_ratings($player1, $player2, 1);
update_ratings($player1, $player2, 1);
update_ratings($player4, $player2, 1);
update_ratings($player3, $player4, 0);
update_ratings($player2, $player4, 0);
update_ratings($player1, $player2, 1);
update_ratings($player1, $player2, 1);
update_ratings($player3, $player2, 1);
update_ratings($player4, $player2, 1);
update_ratings($player3, $player4, 0);
update_ratings($player2, $player4, 0);
update_ratings($player1, $player2, 1);

echo "Player 1: Rating = {$player1->rating}, Variance = {$player1->variance}\n";
echo "Player 2: Rating = {$player2->rating}, Variance = {$player2->variance}\n";
echo "Player 3: Rating = {$player3->rating}, Variance = {$player3->variance}\n";
echo "Player 4: Rating = {$player4->rating}, Variance = {$player4->variance}\n";
?>

<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ThingRanker</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<section class="home">
    <h1>THING RANKER</h1>
    <!--<p class="tag">DECIDER FOR THE INDECISIVE</p>-->

    <form action="new.php" method="POST">

        <div class="field">
            <input class="text" id="comp" placeholder="New Competition" type="text" name="name" autocomplete="off"/>
            <p class="helper helper2">Competition Name</p>
        </div>

        <input class="button" type="submit" value="Rank!">
    </form>
    <div class="downarrow"></div>
</section>
<section class="scroll">
    <div class="content">
        <h3 class="info">WHAT IS THINGRANKER?</h3>
        <p1>
            TLDR: ThingRanker is a tool used to rank things.
            <br> <br>
            ThingRanker is a pairwise ranking tool that can be used to make decisions or to rank things based on preference or skill.
            <br> <br>
            It will create a competition that you can add things to, and once you finish setting it up it will create a link that any number of people can follow to vote on pairings.
            These pairings are 1 to 1 decisions on objects, and the user gets to choose between the two.
            After enough pairings have been run, you'll have (mostly) accurate rankings based on winrates and variance.
        </p1>

        <h3 class="info">WHY PAIRWISE?</h3>
        <p1>Well, it's quite simple.
            <br> <br>
            Here's a question: Is it easier to rank 10 items at once, or is it easier to pick between two?
            <br> <br>
            If you answered two, then there's a good chance that pairwise rankings would help you to decide on where to
            eat for lunch.
            If you answered that they were the same, then you either don't need this tool, or you're a computer.
            <br> <br>
            Pairwise competitions allow us to make many different comparisons, and using the results from the
            comparisons, we can rank items based on preference.
            This means that if you're having trouble deciding on something in a group or even by yourself, you can
            create a quick thing ranker to rank your things.
        </p1>
        <h3 class="info">HOW IT WORKS</h3>
        <p2>
            Pairwise competitions are very similar to something you may have interacted with several times already:
            ranked ladders.
            <br> <br>
            In a ranked ladder system, your rank goes up when you win, and your rank goes down when you lose. Simple.
            <br> <br>
            The same happens here. If Item A is preferred more often than Item B, it will win more pairings, and will
            end up with a higher rank.
            If you want specifics, check our GitHub for more information on the algorithm used to calculate the ratings.
        </p2>
    </div>
    <footer>
        <a target="_blank" href="https://github.com/Spheroman/ThingRanker">
            <img src="github-mark.svg"
                 style="filter: invert(15%) sepia(25%) saturate(1236%) hue-rotate(82deg) brightness(94%) contrast(99%); height: 5vh" alt="ThingRanker Github"></a>
    </footer>
</section>
</body>
</html>
<script>
    $("#comp").each(function (type) {

        $(this).keypress(function () {
            $(this).parent().addClass("focusWithText");
        });

        $(this).blur(function () {
            if ($(this).val() === "") {
                $(this).parent().removeClass("focusWithText");
            }
        });
    });
</script>
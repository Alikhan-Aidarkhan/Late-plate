// Late Plate - main.js
// The only JavaScript on the site. It runs the quiz on quiz.html.
// Everything else on the site is plain HTML and CSS.

// ---------- the quiz ----------
const quiz = document.getElementById("quiz");

if (quiz) {
  const questions = quiz.querySelectorAll(".question");
  const resultBox = document.getElementById("quizResult");
  const bar = document.getElementById("quizBar");

  // one running total per dish
  let scores = {};
  let step = 0;

  function reset() {
    scores = { burger: 0, breakfast: 0, plate: 0, sweet: 0 };
    step = 0;

    questions.forEach(function (q, i) {
      q.hidden = i !== 0;
    });
    resultBox.hidden = true;
    quiz.querySelectorAll(".result").forEach(function (r) {
      r.hidden = true;
    });
    drawBar();
  }

  function drawBar() {
    // how far along we are, as a percentage
    bar.style.width = (step / questions.length) * 100 + "%";
  }

  function winner() {
    // start with the first dish, then keep whichever scored higher
    let best = "burger";
    Object.keys(scores).forEach(function (dish) {
      if (scores[dish] > scores[best]) {
        best = dish;
      }
    });
    return best;
  }

  function finish() {
    const dish = winner();
    quiz.querySelector('.result[data-dish="' + dish + '"]').hidden = false;
    resultBox.hidden = false;
  }

  quiz.querySelectorAll(".answer").forEach(function (button) {
    button.addEventListener("click", function () {
      // add a point to the dish this answer points at
      scores[button.dataset.dish] = scores[button.dataset.dish] + 1;

      questions[step].hidden = true;
      step = step + 1;
      drawBar();

      if (step < questions.length) {
        questions[step].hidden = false;
      } else {
        finish();
      }
    });
  });

  document.getElementById("quizRestart").addEventListener("click", reset);

  reset();
}

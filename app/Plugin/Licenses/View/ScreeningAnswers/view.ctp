<h3>Screening Question Answers</h3>
<div id="screening_answer">

    <h4>Question:</h4>
    <div id="question">
        <?php echo $answer['ScreeningAnswer']['screening_question']; ?>
    </div>

    <h4>Answer:</h4>
    <div id="answer">
        <?php echo $this->Html->flag($answer['ScreeningAnswer']['answer'], array('No', 'Yes')); ?>
    </div>

    <h4>Comments:</h4>
    <div id="comment">
        <?php echo $answer['ScreeningAnswer']['comment']; ?>
    </div>

</div>

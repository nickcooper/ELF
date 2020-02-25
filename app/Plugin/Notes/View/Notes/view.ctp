<span class="note_body"><?php echo $note['Note']['note']; ?></span>

<p><span class="timestamp"><?php echo $this->TextProcessing->formatDate($note['Note']['created'], true); ?></span> | <span
class="author"><?php echo $note['Account']['label']; ?></span></p>

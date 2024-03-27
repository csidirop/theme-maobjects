<?php
    queue_css_file('correction');
    echo head();
    $user = current_user();
?>
<?php echo flash(); ?>

<h4>
    <?php
        echo __('Propose new keywords for item "'); 
        echo link_to($item, 'show', metadata($item, array('Dublin Core', 'Title')), array('target' => '_blank'));
        echo '".';
    ?>
</h4>
<p>
    <?php echo __('Please enter your suggestions in the field below. Separate multiple keywords with commas. An administrator will review your contribution.'); ?>
</p>


<form method='post'>
<div class="field">
        <div class="inputs five columns omega">
            <div class="input-block">
                <textarea cols='50' rows='3' name='comment'></textarea>
            </div>
        </div>
    </div>

    <?php if (!$user) : ?>
        <div class="field">
            <div class="two columns alpha">
                <label for='email'><?php echo __('Email'); ?></label>
            </div>
            <div class="inputs five columns omega">
                <p class="explanation"></p>
                <div class="input-block">
                    <input type='text' name='email' />
                </div>
            </div>
        </div>

    <?php endif; ?>

    <div class="field">
        <div class="one columns alpha">
            <label for='may_contact'><?php echo __('Can we contact you?'); ?></label>
        </div>
        <div class="inputs tree columns omega">
            <div class="input-block">
                <input type='checkbox' value='1' name='may_contact' />
            </div>
        </div>
    </div>

    <!-- we dont need this -->
    <!-- <?php
        foreach ($elements as $element) {
            echo "<div class='element-correction' >";
            $elName = $element->name;
            $elSet = $element->getElementSet();
            $elSetName = $elSet->name;
            echo $this->elementForm($element, $corrections_correction);
            echo "<p class='correction-current-data'>" . __('Current data for %s', $elName) . "</p>";
            echo "<p>" . metadata($item, array($elSetName, $elName), array('no_filter' => true)) . "</p>";
            echo "</div>";
        }
    ?> -->

    <?php
    if (!$user) {
        echo $captchaScript;
    }
    echo $this->formSubmit('submit', __('Submit Suggestion'));
    ?>

    <input type='hidden' name='item_id' value='<?php echo $item->id; ?>' />
</form>

<p>
    <?php echo __('<br><i>Thank you for taking the time to improve this site!</i>'); ?>
</p>

<?php echo foot(); ?>
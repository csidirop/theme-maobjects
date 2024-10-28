<?php
    queue_css_file('correction');
    echo head();
    // $user = current_user();
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

<style>
    /* TODO: move everting to left or center and add 'Current data' to the table */
    .element-correction .explanation {
        line-height: 20px;
        font-size: 1rem;
    }
    .element-correction button{
        display: none;
    }
    .element-correction .correction-current-data {
        font-size: 1rem;
        color: #666;
        text-align: right;
        border-top: 0px;
        margin-right: 1rem;
    }
</style>

<form method='post'>
    <?php
        foreach ($elements as $element) {
            echo "<div class='element-correction' >";
            $elName = $element->name;
            $elSetName = $element->getElementSet()->name;
            echo $this->elementForm($element, $corrections_correction);
            $metadatavalues = metadata($item, array($elSetName, $elName), array('no_filter' => true));
            if ($metadatavalues) {
                echo "<p class='correction-current-data'>" . __('Current entry for %s', $elName) .": ". $metadatavalues  . "</p>";
            } else if ($elName == 'Keywords') {
                $metadatavalues = tag_string("item", "find");
                echo "<p class='correction-current-data'>" . __('Current entry for %s', $elName) .": ". $metadatavalues  . "</p>";
            } else {
                echo "<p class='correction-current-data'>" . __('No value for %s', $elName) . "</p>";
            }
            echo "</div>";
        }
    ?>

    <?php
        echo __('<p><br><i>Thank you for taking the time to improve this site!</i></p>');
        // if (!$user) {
        //     echo $captchaScript;
        // }
        echo $this->formSubmit('submit', __('Submit Suggestion'));
    ?>

    <input type='hidden' name='item_id' value='<?php echo $item->id; ?>' />
</form>

<?php echo foot(); ?>
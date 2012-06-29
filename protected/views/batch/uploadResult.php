<div>
    <?php 
    
    if ($model->succeed){
        foreach($model->sheets as $item) {
            echo '<div class="row-wrapper-with-error">';
            echo '<div class="upload-result-row">';
            echo '<span class="title">', $item->title, '</span>';
            echo '<span class="total-read">(Total Read: ', $item->totalRead, '; Valid: ', $item->totalValid, '; Saved: ', $item->totalSaved, ')</span>';
            echo '</div>';
            echo '<div class="error">';
            foreach($item->errors as $error){
                echo '<div class="error-line">', $error->toString(), '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
    }
    else{
        echo '<div style="margin-top: 5px;">';
        echo $model->errorDetails;
        echo '</div>';
    }
    
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.error').hide();
            $('.error').find('.error-line').parent().show();
        });
    </script>
</div>
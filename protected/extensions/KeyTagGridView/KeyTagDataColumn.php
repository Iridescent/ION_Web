<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KeyTagGridColumn
 *
 * @author dshyshen
 */
Yii::import('zii.widgets.grid.CDataColumn');

class KeyTagDataColumn extends CDataColumn {
    public function renderHeaderCell(){
        $this->headerHtmlOptions['id']=$this->id;
        echo CHtml::openTag('th',$this->headerHtmlOptions);
        echo "<div class=\"corner-8-top\">";
        $this->renderHeaderCellContent();
        echo "</div>";
        echo "</th>";
    }
}

?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#personsGrid .items td, #personGrid .items td').wrapInner('<span />');
        $('#personsGrid .items td span').css({
            'max-width' : '100px',
            'word-wrap' : 'break-word',
            'display' : 'block'
        });
        $('#personGrid .items td span').css({
            'max-width' : '150px',
            'word-wrap' : 'break-word',
            'display' : 'block'
        });
        $('.items td a img').parents('td').css({
            'text-align' : 'center',
            'vertical-align' : 'middle'
        })
    });
</script>

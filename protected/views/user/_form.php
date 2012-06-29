<?php  Yii::app()->clientScript->registerScript('MultiGeo', "

var stateList;
var cityList;

var selectedCountryId = 0;
var selectedStateId = 0;
var countryCount;
var stateCount;
var cityCount;

var selectedCountryDiv;
var selectedStateDiv;
var selectedCityDiv;

var countryCheckboxSelector = 'div#countries_list>div>input:checkbox';
var stateCheckboxSelector = 'div#states_list>div>input:checkbox';
var cityCheckboxSelector = 'div#cities_list>div>input:checkbox';

var countryLabelSelector = 'div#countries_list>div>label';
var stateLabelSelector = 'div#states_list>div>label';
var cityLabelSelector = 'div#cities_list>div>label';

var selectedClass = 'selectedLocation';

$(document).ready(function(){
    geoCollection = null;
    if ($.trim($('#geoString').val()) != '') {
        geoCollection = JSON.parse($('#geoString').val());
    }
    
    stateList = $('#states_list');
    cityList = $('#cities_list');
    
    selectedCountryId = parseInt($('#states_parent').val());
    selectedStateId = parseInt($('#cities_parent').val());
    countryCount = $(countryCheckboxSelector).length - 1;
    stateCount = $(stateCheckboxSelector).length - 1;
    cityCount = $(cityCheckboxSelector).length - 1;
    
    selectedCountryDiv = $($(countryLabelSelector)[1]).parent();
    selectedStateDiv = $($(stateLabelSelector)[1]).parent();
    selectedCityDiv = $($(cityLabelSelector)[1]).parent();
    
    SelectItemDiv(selectedCountryDiv, true);
    SelectItemDiv(selectedStateDiv, true);
    SelectItemDiv(selectedCityDiv, true);
       
    InitCountryList();
    InitStateList();
    InitCityList();
    
    $('#user-form').submit(function(event) { 
       var geo = $('#geoString'); 
       geo.val(JSON.stringify(geoCollection)); 
   });
});

function InitCountryList(){
    $(countryLabelSelector).click(function(){
        selectedCountryId = parseInt($(this).attr('for'));
        if (selectedCountryId){
            SelectItemDiv(selectedCountryDiv, false);
            selectedCountryDiv = $(this).parent();
            SelectItemDiv(selectedCountryDiv, true);
            
            $.ajax({
                    type: 'POST',
                    url: '".CController::createUrl('site/StatesByCountry')."',
                    datatype: 'json',
                    traditional: true,
                    data: {countryid: selectedCountryId},
                    success: function(data) {
                        if (data){
                            cityList.empty();
                            stateList.empty();
                            stateList.append(data);
                            InitStateList();
                        }
                    }
            });
        }
    });
    
    $(countryCheckboxSelector).click(OnCountryChecked);
    RefreshCountryList();
}

function InitStateList(){
    $(stateLabelSelector).click(function(){
        selectedStateId = parseInt($(this).attr('for'));
        if (selectedStateId){
            SelectItemDiv(selectedStateDiv, false);
            selectedStateDiv = $(this).parent();
            SelectItemDiv(selectedStateDiv, true);
            
            $.ajax({
                type: 'POST',
                url: '".CController::createUrl('site/CitiesByState')."',
                datatype: 'json',
                traditional: true,
                data: {stateid: selectedStateId},
                success: function(data) {
                    if (data){
                       cityList.empty();
                       cityList.append(data);
                       InitCityList();
                    }
                }
            });
        }
    });
    $(stateCheckboxSelector).click(OnStateChecked);
    
    stateCount = $(stateCheckboxSelector).length - 1;
    RefreshStateList();
}

function InitCityList(){
    $(cityLabelSelector).click(function(){
        selectedCityId = parseInt($(this).attr('for'));
        if (selectedCityId){
            SelectItemDiv(selectedCityDiv, false);
            selectedCityDiv = $(this).parent();
            SelectItemDiv(selectedCityDiv, true);
        }
    });
    $(cityCheckboxSelector).click(OnCityChecked);
    
    cityCount = $(cityCheckboxSelector).length - 1;
    RefreshCityList();
}

function CheckAll(container){
    $(container).attr('checked', true);
}

function UncheckAll(container){
    $(container).attr('checked', false);
}

function RefreshAllLists(){
    RefreshCountryList();
    RefreshStateList();
    RefreshCityList();
}

function RefreshCountryList(){
    if (geoCollection && !IsObjectNotEmpty(geoCollection)){
        CheckAll(countryCheckboxSelector);
    }
    else if(geoCollection) {
        $(countryCheckboxSelector).each(function(){
            var countryId = parseInt($(this).val());
            if(geoCollection[countryId]){
                $(this).attr('checked', true);
            }
            else {
                $(this).attr('checked', false);
            }
        });
    }
    else {
        UncheckAll(countryCheckboxSelector);
    }
}

function RefreshStateList(){
    if ((geoCollection && !IsObjectNotEmpty(geoCollection))
        || (geoCollection && geoCollection[selectedCountryId] && !IsObjectNotEmpty(geoCollection[selectedCountryId]))){
        CheckAll(stateCheckboxSelector);
    }
    else if(geoCollection && geoCollection[selectedCountryId]){
        $(stateCheckboxSelector).each(function(){
            var stateId = parseInt($(this).val());
            if(geoCollection[selectedCountryId][stateId]){
                $(this).attr('checked', true);
            }
            else {
                $(this).attr('checked', false);
            }
        });
    }
    else {
        UncheckAll(stateCheckboxSelector);
    }
}

function RefreshCityList(){
    if ((geoCollection && !IsObjectNotEmpty(geoCollection))
        || (geoCollection && geoCollection[selectedCountryId] && !IsObjectNotEmpty(geoCollection[selectedCountryId]))
        || (geoCollection && geoCollection[selectedCountryId][selectedStateId] && !IsObjectNotEmpty(geoCollection[selectedCountryId][selectedStateId]))){
        CheckAll(cityCheckboxSelector);
    }
    else if (geoCollection && geoCollection[selectedCountryId] && geoCollection[selectedCountryId][selectedStateId]){
        $(cityCheckboxSelector).each(function(){
            var cityId = parseInt($(this).val());
            if(geoCollection[selectedCountryId][selectedStateId][cityId]){
                $(this).attr('checked', true);
            }
            else {
                $(this).attr('checked', false);
            }
        });
    }
    else {
        UncheckAll(cityCheckboxSelector);
    }
}

function OnCountryChecked(){
    var checkbox = $(this);
    var value = parseInt(checkbox.val());
    var isChecked = checkbox.attr('checked');

    if (value){ //not All
        if (isChecked){
            InitCountry();
            geoCollection[value] = {};
            CheckCountryAll();
        }
        else{
            if (!IsObjectNotEmpty(geoCollection)){
                geoCollection = {};
                $(countryLabelSelector).each(function(){
                    var id = parseInt($(this).attr('for'));
                    if (id && id != value){
                        geoCollection[id] = {};
                    }
                });
                ClearCountires();
            }
            else{
                delete geoCollection[value];
                ClearCountires();
            }
        }
    }
    else{ // All
        if (isChecked){
            geoCollection = {};
        }
        else{
            geoCollection = null;
        }
    }
    RefreshAllLists();
}

function OnStateChecked(){
    var checkbox = $(this);
    var value = parseInt(checkbox.val());
    var isChecked = checkbox.attr('checked');
    
    if (isChecked) {
        if (value) {
            InitState();
            geoCollection[selectedCountryId][value] = {};
            CheckStateAll();
        }
        else {
            InitCountry();
            geoCollection[selectedCountryId] = {};
            CheckCountryAll();
        }
    }
    else {
        FillCountries();
        if (value) {
            if (!IsObjectNotEmpty(geoCollection[selectedCountryId])){
                geoCollection[selectedCountryId] = {};
                $(stateLabelSelector).each(function(){
                    var id = parseInt($(this).attr('for'));
                    if (id && id != value){
                        geoCollection[selectedCountryId][id] = {};
                    }
                });
            }
            else{
                delete geoCollection[selectedCountryId][value];
            }
            ClearStates();
        }
        else {
            delete geoCollection[selectedCountryId];
            ClearCountires();
        }
    }
    RefreshAllLists();
}

function OnCityChecked(){
    var checkbox = $(this);
    var value = parseInt(checkbox.val());
    var isChecked = checkbox.attr('checked');
    
    if (isChecked) {
        if (value) {
            InitCity();
            geoCollection[selectedCountryId][selectedStateId][value] = {};
            CheckCityAll();
        }
        else {
            InitState();
            geoCollection[selectedCountryId][selectedStateId] = {};
            CheckStateAll();
        }
    }
    else {
        FillCountries();
        FillStates();
        if (value) {
            if (!IsObjectNotEmpty(geoCollection[selectedCountryId][selectedStateId])){
                geoCollection[selectedCountryId][selectedStateId] = {};
                $(cityLabelSelector).each(function(){
                    var id = parseInt($(this).attr('for'));
                    if (id && id != value){
                        geoCollection[selectedCountryId][selectedStateId][id] = {};
                    }
                });
            }
            else{
                delete geoCollection[selectedCountryId][selectedStateId][value];
            }
            ClearCities();
        }
        else {
            delete geoCollection[selectedCountryId][selectedStateId];
            ClearStates();
        }
    }
    RefreshAllLists();
}

// Check if location collection is empty
function ClearCountires(){
    if (!IsObjectNotEmpty(geoCollection)){
        geoCollection = null;
    }
}

function ClearStates(){
    if (!IsObjectNotEmpty(geoCollection[selectedCountryId])){
        delete geoCollection[selectedCountryId];
    }
    ClearCountires();
}

function ClearCities(){
    if (!IsObjectNotEmpty(geoCollection[selectedCountryId][selectedStateId])){
        delete geoCollection[selectedCountryId][selectedStateId];
    }
    ClearStates();
}

// Check if location collection is full
function CheckCountryAll(){
    if (EmptyObjectKeysCount(geoCollection) == countryCount){
        geoCollection = {};
    }
}

function CheckStateAll(){
    if (EmptyObjectKeysCount(geoCollection[selectedCountryId]) == stateCount){
        geoCollection[selectedCountryId] = {};
        CheckCountryAll();
    }
}

function CheckCityAll(){
    if (ObjectKeysCount(geoCollection[selectedCountryId][selectedStateId]) == cityCount){
        geoCollection[selectedCountryId][selectedStateId] = {};
        CheckStateAll();
    }
}

//Init location storage item
function InitCountry(){
    if (!geoCollection){
        geoCollection = {};
    }
}

function InitState(){
    InitCountry();
    if (!IsObjectNotEmpty(geoCollection[selectedCountryId])){
        geoCollection[selectedCountryId] = {};
    }
}

function InitCity(){
    InitState();
    if (!IsObjectNotEmpty(geoCollection[selectedCountryId][selectedStateId])){
        geoCollection[selectedCountryId][selectedStateId] = {};
    }
}

// Fill locations
function FillCountries(){
    if (!IsObjectNotEmpty(geoCollection)){
        geoCollection = {};
        $(countryLabelSelector).each(function(){
            var id = parseInt($(this).attr('for'));
            if (id){
                geoCollection[id] = {};
            }
        });
    }
}

function FillStates(){
    if (geoCollection && !IsObjectNotEmpty(geoCollection[selectedCountryId])){
        geoCollection[selectedCountryId] = {};
        $(stateLabelSelector).each(function(){
            var id = parseInt($(this).attr('for'));
            if (id){
                geoCollection[selectedCountryId][id] = {};
            }
        });
    }
}

//helper
function EmptyObjectKeysCount(obj){
    var result = 0;
    for (var i in obj){
        if (obj.hasOwnProperty(i) && !IsObjectNotEmpty(obj[i])) result++;
    }
    return result;
}

function ObjectKeysCount(obj){
    var result = 0;
    for (var i in obj){
        if (obj.hasOwnProperty(i)) result++;
    }
    return result;
}

function SelectItemDiv(itemDiv, direction){
    if (itemDiv){
        if (direction){
            itemDiv.addClass(selectedClass);
        }
        else{
            itemDiv.removeClass(selectedClass);
        }
    }
}

");
?>      

<?php ?>


<div class="form">
   
<?php $form=$this->beginWidget('CActiveForm', array('id'=>'user-form','enableAjaxValidation'=>false)); ?>
    <?php echo $form->hiddenField($model, 'ID', array('name'=>'userId')); ?>
    <?php echo CHtml::hiddenField('geoString', $this->location); ?>
    
    <?php  /* Customize error messages here */ echo $form->errorSummary($model); ?>
    <div class="form-left-column styled-form">
	<h2><?php echo $this->getAddEditText($model->ID) ?> User</h2>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	
	<div class="row users-row">
            <div class="left">
                <?php echo $form->labelEx($model,'Login', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'Login',array('size'=>60,'maxlength'=>60)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Login', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>
            <div class="left">
                <?php echo $form->labelEx($model,'Password', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->passwordField($model,'Password',array('class'=>'span3','maxlength'=>32, 'value'=>'')); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'Password', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>
            <div class="left">
                <?php echo $form->labelEx($model,'LastName', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'LastName',array('size'=>60,'maxlength'=>64)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'LastName', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>	
            <div class="left">
                <?php echo $form->labelEx($model,'FirstName', array('class'=>'label')); ?>
                <span class="short-input"><?php echo $form->textField($model,'FirstName',array('size'=>60,'maxlength'=>64)); ?></span>
                <div class="clear"></div>
                <?php echo $form->error($model,'FirstName', array('class'=>'errorMessage left')); ?>
            </div>
            <div class="clear"></div>
	</div>


    <?php  if (Yii::app()->user->checkAccess('manageUsers')): ?>
	<div class="row users-row left-20">
            <div class="left">
            <?php echo $form->labelEx($model,'Role', array('class'=>'label')); ?>
            <?php if(!Yii::app()->user->checkAccess('Super Admin')) {
                  $roleList = CHtml::listData(Roles::model()->findAll(array('order' => 'ID', 'condition'=>'ID!=1')), 'ID', 'Name');      
                  } else {
                      $roleList = CHtml::listData(Roles::model()->findAll(array('order' => 'ID')), 'ID', 'Name');    
                  }
            ?>
            <span class="short-input-select"><?php echo $form->dropDownList($model,'Role', $roleList, array('empty' => 'Select a role:', 'onchange' => 'js: if ($(\'#User_Role\').val() === "1") {$(\'#geo\').css(\'display\', \'none\');} else {$(\'#geo\').css(\'display\', \'block\');}')); //textField($model,'Household'); ?></span>
		
            <div class="clear"></div>
            <?php echo $form->error($model,'Role', array('class'=>'errorMessage right')); ?>
            </div>
            <?php endif; ?>
    

            <?php  $role = Roles::model()->getRoleName($model->Role);?>            
          
            <div id="geo" style="display: <?php if ($role === "Super Admin") {print "none";} else {print "block";} ?>">           
            <?php 
                $countriesList = Locations::selectCountry(false);
                $countryId = key($countriesList);
                $statesList = Locations::selectStates($countryId);
                $stateId = key($statesList);
                $citiesList = Locations::selectCities($stateId);

                echo '<div class="left countries-list-wrapper">';
                echo CHtml::label('Country', 'countries', array('class'=>'label'));
                echo '<div id="countries_list">';
                echo VisualHelper::CheckBoxList($countriesList, $defaultCountry, 'countries', true);
                echo '<div class="clear"></div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="clear"></div>';

                echo '<div class="left state-list-wrapper">';
                echo CHtml::label('State', 'states', array('class'=>'label'));
                echo '<div id="states_list" >';
                echo VisualHelper::CheckBoxList($statesList, $countryId, 'states', true);
                echo '</div>';
                echo '<div class="clear"></div>';
                echo '</div>';        
                echo '<div class="clear"></div>';

                echo '<div class="left city-list-wrapper">';
                echo CHtml::label('City', 'cities', array('class'=>'label'));
                echo '<div id="cities_list">';            
                echo VisualHelper::CheckBoxList($citiesList, $stateId, 'cities', true);
                echo '</div>';
                echo '<div class="clear"></div>';
                echo '</div>';        
                echo '<div class="clear"></div>';
                echo '<div class="left">';    
                echo '<div class="clear"></div>';
            ?>
            </div>
        </div>
    </div>
    
<div class="wrapper-styled-buttons bottom-20-p">
    <span class="styled-bttn right-10"><?php echo CHtml::submitButton(Yii::t('Yii', 'Cancel'), array('name'=>'cancel', 'onclick'=>'onclick="js: history.back();"')); ?></span>  
    <span class="styled-bttn"><?php echo CHtml::submitButton('OK'); ?></span>
</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->

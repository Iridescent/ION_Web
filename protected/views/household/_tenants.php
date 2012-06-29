<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'person-grid2',
    'dataProvider' => $personFilter->search('Household=' . $householdId),
    'filter' => $personFilter,
    'columns' => array(
        /*'ID',
        'BarcodeID',*/
        'FirstName',
        'LastName',
        /*'MiddleName',
        'Household',*/
        'EmailAddress',
        /*'Sex',
        'GradeLevel',
        'Type',
        'School',
        'DateOfBirth',
        'WorkPhone',
        'StayAtHomeParent',*/
        'MobilePhone',
        /*'GuardianRelation',
        'Notes',
        'SpecialCircumstances',
        'PhysicianName',
        'PhysicianPhoneNumber',
        'Allergies',
        'Medications',
        'InsuranceCarrier',
        'InsuranceNumber',
        'LastUpdated',
        'UpdateUserId',*/
        array(
            'class' => 'CButtonColumn',
            'template' => '{view} {delete}',
            'viewButtonUrl' => 'Yii::app()->createUrl("/person/view", array("id" => $data->ID))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/household/excludePerson", array("personId" => $data->ID))'
        ),
    ),
));
?>
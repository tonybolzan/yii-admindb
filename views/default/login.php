<?php

$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'class' => 'form-signin',
    ),
        ));

echo $form->label($model, 'password', array('class' => 'form-signin-heading'));

echo $form->passwordField($model, 'password', array('class' => 'input-large', 'autofocus' => 'autofocus', 'placeholder' => Yii::t('AdmindbModule.core', 'Password')));

echo CHtml::submitButton(Yii::t('AdmindbModule.core', 'Login'), array('class' => 'btn btn-primary pull-right'));

echo $form->error($model, 'password', array('class' => 'alert alert-error'));

$this->endWidget();

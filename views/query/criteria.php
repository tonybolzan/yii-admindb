<?php
$assetsUrl = $this->module->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerCssFile($assetsUrl . '/codemirror/codemirror.min.css');
$cs->registerScriptFile($assetsUrl . '/codemirror/codemirror.min.js');
$cs->registerScriptFile($assetsUrl . '/codemirror/php.min.js');
$cs->registerScriptFile($assetsUrl . '/js/garlic.min.js');

Yii::app()->clientScript->registerScript('codemirror-php', <<<JS
    !(function($){
        window.editor = CodeMirror.fromTextArea(document.getElementById('QuerySqlForm_sql'), {
            lineNumbers: true,
            matchBrackets: true,
            mode: "application/x-httpd-php",
            indentUnit: 4,
            indentWithTabs: true,
            enterMode: "keep",
            tabMode: "shift"
	});
    })(jQuery);
JS
);
?>

<div class="row-fluid">
    <div class="span8">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'QuerySqlForm-form',
            'enableAjaxValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array(
                'data-persist' => 'garlic',
            ),
        ));

        echo $form->error($model, 'sql', array('class' => 'alert alert-error'));

        echo $form->textArea($model, 'sql', array('class' => 'input-block-level', 'rows' => 5));

        echo CHtml::submitButton(Yii::t('AdmindbModule.core', 'Submit'), array('class' => 'btn btn-primary pull-right'));

        $this->endWidget();
        ?>

        <div class="input-prepend input-append">
            <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div>
            <input class="span2" id="appendedPrependedDropdownButton" type="text">
            <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="span4">
        <ul class="db-tables">
            <?php foreach ($tables as $table): ?>
                <li><?php echo $table->rawName; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php
foreach ($providers as $provider) {
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $provider,
        'enableSorting' => true,
        'itemsCssClass' => "table table-bordered table-condensed table-striped",
    ));
}
?>
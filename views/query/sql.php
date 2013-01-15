<?php
$assetsUrl = $this->module->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerCssFile($assetsUrl . '/codemirror/codemirror.min.css');
$cs->registerScriptFile($assetsUrl . '/codemirror/codemirror.min.js');
$cs->registerScriptFile($assetsUrl . '/codemirror/sql.min.js');
$cs->registerScriptFile($assetsUrl . '/js/garlic.min.js');

Yii::app()->clientScript->registerScript('codemirror-sql', <<<JS
    !(function($){
        window.editor = CodeMirror.fromTextArea(document.getElementById('QuerySqlForm_sql'), {
            mode: 'text/x-sql',
            indentWithTabs: true,
            smartIndent: true,
            lineNumbers: true,
            matchBrackets : true,
            autofocus: true,
            showCursorWhenSelecting: true
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
        'nullDisplay'  => 'NULL',
        'enableSorting' => true,
        'itemsCssClass' => "table table-bordered table-condensed table-striped",
    ));
}
?>
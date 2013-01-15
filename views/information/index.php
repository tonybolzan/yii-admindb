<?php
/* @var $db CDbConnection */
$assetsUrl = $this->module->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerScriptFile($assetsUrl . '/js/bootstrap-transition.min.js');
$cs->registerScriptFile($assetsUrl . '/js/bootstrap-modal.min.js');

$tablesQuery = $db->createCommand('SHOW TABLE STATUS')->queryAll();

$caseConst = array('PDO::CASE_NATURAL', 'PDO::CASE_UPPER', 'PDO::CASE_LOWER',);
$nullConst = array('PDO::NULL_NATURAL', 'PDO::NULL_EMPTY_STRING', 'PDO::NULL_TO_STRING',);
?>
<table class="table table-bordered table-condensed table-striped">
    <caption><div class="alert alert-info"><strong><?php echo Yii::t('AdmindbModule.core', "Informations about the database connection"); ?></strong></div></caption>
    <thead>
        <tr>
            <th><?php echo Yii::t('AdmindbModule.core', 'Description'); ?></th>
            <th><?php echo Yii::t('AdmindbModule.core', 'Value'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo Yii::t('AdmindbModule.core', 'Connection status'); ?></td>
            <td><?php echo ($db->active ? ('<span class="label label-success">' . Yii::t('AdmindbModule.core', 'Active') . '</span>') : ('<span class="label label-important">' . Yii::t('AdmindbModule.core', 'Inactive') . '</span>')); ?></td>
        </tr>
        <?php if ($db->active): ?>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'DB driver name'); ?></td>
                <td><?php echo $db->driverName; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'DB driver version'); ?></td>
                <td><?php echo $db->clientVersion; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'Charset'); ?></td>
                <td><?php echo $db->charset; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'PDO Data Source Name (DSN)'); ?></td>
                <td><?php echo $db->connectionString; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'Username'); ?></td>
                <td><?php echo $db->username; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'How the column names case are converted'); ?></td>
                <td>
                <?php echo $caseConst[$db->columnCase]; ?>
                </td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'How the null and empty strings are converted'); ?></td>
                <td><?php echo $nullConst[$db->nullConversion]; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'DBMS server version'); ?></td>
                <td><?php echo $db->serverVersion; ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'DBMS server information'); ?></td>
                <td><?php
                    preg_match_all('/([a-z ]+) *: *([0-9.]+)/i', $db->serverInfo, $matches);
                    foreach ($matches[0] as $key => $value) {
                        if ($matches[1][$key] == 'Uptime') {
                            $number = (gmdate('Y', $matches[2][$key]) - 1970)
                                    . ' ' . Yii::t('AdmindbModule.core', 'Years') . ' '
                                    . (gmdate('m', $matches[2][$key]) - 1)
                                    . ' ' . Yii::t('AdmindbModule.core', 'Months') . ' '
                                    . (gmdate('d', $matches[2][$key]) - 1)
                                    . ' ' . Yii::t('AdmindbModule.core', 'Days') . ' '
                                    . gmdate('H:i:s', $matches[2][$key]);
                        } else {
                            $number = Yii::app()->numberFormatter->formatDecimal($matches[2][$key]);
                        }
                        $pieces[] = $matches[1][$key] . ' : <span class="label">' . $number . '</span>';
                    }
                    echo implode('<br>', $pieces);
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'Number of tables'); ?></td>
                <td><?php echo count($db->schema->tables); ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('AdmindbModule.core', 'Database size'); ?></td>
                <td><?php echo $this->bytesToHuman(array_sum(array_map(create_function('$table', 'return $table["Data_length"] + $table["Index_length"];'), $tablesQuery))); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($db->active): ?>
    <table class="table table-bordered table-condensed table-striped">
        <caption><div class="alert alert-info"><strong><?php echo Yii::t('AdmindbModule.core', "Informations about the database tables"); ?></strong></div></caption>
        <thead>
            <tr>
                <th><?php echo Yii::t('AdmindbModule.core', 'Table'); ?></th>
                <th><?php echo Yii::t('AdmindbModule.core', 'Type'); ?></th>
                <th><?php echo Yii::t('AdmindbModule.core', 'Rows'); ?></th>
                <th><?php echo Yii::t('AdmindbModule.core', 'Size'); ?></th>
                <th><?php echo Yii::t('AdmindbModule.core', 'Collation'); ?></th>
                <th><?php echo Yii::t('AdmindbModule.core', 'Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tablesQuery as $table): ?>
                <tr>
                    <td><?php echo $table['Name']; ?></td>
                    <td><?php echo $table['Engine']; ?></td>
                    <td><?php echo Yii::app()->numberFormatter->formatDecimal($table['Rows']); ?></td>
                    <td><?php echo $this->bytesToHuman($table["Data_length"] + $table["Index_length"]); ?></td>
                    <td><?php echo $table['Collation']; ?></td>
                    <td><a class="btn btn-mini btn-info" data-toggle="modal" href="#<?php echo $table['Name']; ?>"><?php echo Yii::t('AdmindbModule.core', 'Show'); ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php foreach ($db->schema->tables as $table): ?>
        <div class="modal hide fade" id="<?php echo $table->name; ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><?php echo $table->rawName; ?></h3>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-condensed table-striped">
                    <thead>
                        <tr>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Collumn'); ?></th>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Type'); ?></th>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Default'); ?></th>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Null'); ?></th>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Auto Increment'); ?></th>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Is PrimaryKey'); ?></th>
                            <th><?php echo Yii::t('AdmindbModule.core', 'Is ForeignKey'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($table->columns as $column): ?>
                            <tr class="<?php echo ($column->isPrimaryKey ? 'warning' : ($column->isForeignKey ? 'error' : ''));?>">
                                <td><?php echo $column->rawName; ?></td>
                                <td><?php echo $column->dbType; ?></td>
                                <td><?php echo $column->defaultValue; ?></td>
                                <td><?php echo (($column->allowNull) ? Yii::t('AdmindbModule.core', 'Yes') : Yii::t('AdmindbModule.core', 'No')); ?></td>
                                <td><?php echo (($column->autoIncrement) ? Yii::t('AdmindbModule.core', 'Yes') : Yii::t('AdmindbModule.core', 'No')); ?></td>
                                <td><?php echo (($column->isPrimaryKey) ? Yii::t('AdmindbModule.core', 'Yes') : Yii::t('AdmindbModule.core', 'No')); ?></td>
                                <td><?php echo (($column->isForeignKey) ? Yii::t('AdmindbModule.core', 'Yes') : Yii::t('AdmindbModule.core', 'No')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

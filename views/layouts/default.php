<?php $this->beginContent('admindb.views.layouts.main'); ?>
<div class="row-fluid">
    <div class="span3">
        <div class="well well-small sidebar-nav" style="padding: 8px 0;">
            <?php
            $this->widget('zii.widgets.CMenu', array(
                'htmlOptions' => array('class' => 'nav nav-list'),
                'activateParents' => true,
                'items' => array(
                    array(
                        'label' => Yii::t('AdmindbModule.core', 'Welcome to Yii Admin DB'),
                        'itemOptions' => array('class' => 'nav-header'),
                    ),
                    array(
                        'itemOptions' => array('class' => 'divider'),
                    ),
                    array(
                        'label' => Yii::t('AdmindbModule.core', 'Information'),
                        'url' => array('/admindb/information/index'),
                    ),
                    array(
                        'label' => Yii::t('AdmindbModule.core', 'Administrate'),
                        'url' => array('/admindb/administrate/index'),
                    ),
                    array(
                        'label' => Yii::t('AdmindbModule.core', 'Query SQL'),
                        'url' => array('/admindb/query/sql'),
                    ),
                    array(
                        'label' => Yii::t('AdmindbModule.core', 'Query CDbCriteria'),
                        'url' => array('/admindb/query/criteria'),
                    ),
                    array(
                        'itemOptions' => array('class' => 'divider'),
                    ),
                    array(
                        'label' => Yii::t('AdmindbModule.core', 'Logout'),
                        'url' => array('default/logout'),
                    ),
                )
            ));
            ?>
        </div>
    </div>
    <div class="span9">
        <?php
        foreach (Yii::app()->user->getFlashes() as $key => $value) {
            echo '<div class="alert alert-' . $key . '">';
            //echo '<strong>' . ucfirst($key) . ':</strong> ';
            echo $value;
            echo '</div>';
        }
        echo $content;
        ?>
    </div>
</div>
<?php $this->endContent(); ?>
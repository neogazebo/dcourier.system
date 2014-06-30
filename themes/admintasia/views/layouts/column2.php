<?php $this->beginContent('//layouts/main'); ?>
<div id="page-layout" class="fixed">
    <div id="page-content">
        <div id="page-content-wrapper">
            <div id="content-box">
                <?php echo $content; ?>
            </div>
            <div id="sidebar">
                <div class="sidebar-content">
									<div class="portlet-header ui-widget-header">Operations</div>
									<div class="portlet-content">
                    <?php
//                    $this->beginWidget('zii.widgets.CPortlet', array(
//                        'title' => 'Operations',
//                        'htmlOptions' => array('class' => 'operations box ui-widget-content ui-corner-all portlet'),
//                    ));
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => $this->menu,
                        'htmlOptions' => array('class' => 'side-menu layout-options'),
                    ));
//                    $this->endWidget();
                    ?>
									</div>
                </div>
            </div><!-- sidebar -->
        </div><!-- content -->
    </div>
</div>
<?php $this->endContent(); ?>
	
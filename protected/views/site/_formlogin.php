<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <ul>
        <li>
            <?php echo $form->labelEx($model, 'username'); ?>
            <div class="row">
                <?php echo $form->textField($model, 'username',array('class'=>'field full text')); ?>
                <?php echo $form->error($model, 'username'); ?>
            </div>
            
        </li>

        <li>
            <?php echo $form->labelEx($model, 'password'); ?>
            <div class="row">
                <?php echo $form->passwordField($model, 'password',array('class'=>'field full text')); ?>
                <?php echo $form->error($model, 'password'); ?>
            </div>            
            
        </li>


        <li>
            <div>
                <?php echo CHtml::ajaxSubmitButton('Login', array('site/form_login'), array('update' => '#login'), array('class' => 'ui-state-default ui-corner-all float-right ui-button')) ?>
            </div>
        </li>
    </ul>

    <?php $this->endWidget(); ?>
</div><!-- form -->


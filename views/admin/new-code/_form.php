<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('/js/fileUpload/ajaxfileupload.js');
$script = <<< JS
$(document).ready(function(){
   $("#upload-file").change(function(){
       var url  = $(this).attr('data-url');
       var type = $(this).attr('data-type');
       var csrf = $(this).attr('data-csrf');
       $.ajaxFileUpload({
            url: url,
            secureuri: true,
            fileElementId: 'upload-file',
            dataType: 'json',
            data:{_csrf:csrf},
            success: function (data, status) {
                if(data.state){
                    toastr.success('数据包格式检测成功');
                    $(".contents").val(data.msg);
                }else{
                    toastr.error(data.msg);
                }
            }
        });
   })
})
JS;
$this->registerJs($script);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">

                            报警设置与数据包管理
                        </a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2>时时彩 报警时间段 [例: 09时 23时  24小时制度 24=0 最好不要设置 24 或者 0]</h2>
            </header>
            <div class="main-box-body clearfix">

                <?php $form = ActiveForm::begin([
                    'options'=>['class'=>'form-center data-form','enctype'=>'multipart/form-data'],
                    'action'=>Url::to('/admin/new-code/submit'),
                    'method'=>'post'])?>
                <div class="modal-body">
                    <?= $form->field($model, 'start')->label('开始时间 - 开始与结束都为0则全天报警')->textInput(['placeholder'=>'报警开始时间'])?>
                    <?= $form->field($model, 'end')->label('结束时间 - 开始与结束都为0则全天报警')->textInput(['placeholder'=>'报警结束时间'])?>
                    <?= $form->field($model, 'number')->label('报警期数')->textInput()?>
                    <?= $form->field($model, 'status')->label('是否开启报警')->dropDownList([1=>'开启',0=>'关闭'])?>
                    <?= $form->field($model, 'alias')->label('数据包别名')->textInput(['placeholder'=>'数据包别名'])?>
                    <?= $form->field($model, 'contents')->label(false)->hiddenInput(['class'=>'contents'])?>
                    <?= $form->field($model, 'id')->label(false)->hiddenInput()?>

                    <div class="form-group field-cqdata-data_txt required">
                        <label class="control-label" for="cqdata-data_txt">数据包</label>
                        <input type="file" id="upload-file" name="file" accept=".txt" data-csrf="<?= Yii::$app->request->getCsrfToken() ?>" data-url="<?= Url::to('/admin/packet/upload')?>" >

                        <p class="help-block help-block-error"></p>
                    </div>

                    <input type="hidden" name="type" value="<?= isset($_GET['type']) ? $_GET['type'] : false ?>" />
                </div>

                <div class="btn-group pull-right" style="margin-right: 15px">
                    <?= Html::resetButton('重置',['class'=>'btn btn-success'])?>
                    <?= Html::submitButton('保存',['class'=>'btn btn-success right'])?>
                </div>
                <?php ActiveForm::end()?>


            </div>
        </div>
    </div>
</div>
